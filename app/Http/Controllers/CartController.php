<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Notifications\NewOrderNotification;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class CartController extends Controller
{
    protected $storeService;

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        if (!$lat1 || !$lon1 || !$lat2 || !$lon2)
            return 0;
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    public function __construct(\App\Services\StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    private function syncCartToDb()
    {
        if (auth()->check()) {
            auth()->user()->update(['cart_data' => session()->get('cart', [])]);
        }
    }

    public function index()
    {
        if (auth()->check()) {
            $user = auth()->user();
            $sessionCart = session()->get('cart', []);
            // If session is empty but DB has data, restore it
            if (empty($sessionCart) && !empty($user->cart_data)) {
                session()->put('cart', $user->cart_data);
            }
            // If session has data but DB is empty or different, sync session to DB
            elseif (!empty($sessionCart)) {
                $this->syncCartToDb();
            }
        }

        $cart = session()->get('cart', []);
        $total = 0;

        if (!empty($cart)) {
            $products = \App\Models\Product::whereIn('id', array_keys($cart))->get()->map(function ($product) use ($cart) {
                $product->cart_quantity = $cart[$product->id];
                $product->cart_subtotal = $product->price * $cart[$product->id];
                return $product;
            });
            $total = $products->sum('cart_subtotal');
        } else {
            $products = collect(); // always a Collection, never a plain array
        }

        return view('frontend.cart', compact('products', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $cart = session()->get('cart', []);
        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1;

        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        session()->put('cart', $cart);
        $this->syncCartToDb();

        $totalItems = array_sum($cart);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart!',
            'cart_count' => $totalItems,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = session()->get('cart', []);
        $productId = $request->product_id;

        if ($request->quantity == 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = $request->quantity;
        }

        session()->put('cart', $cart);
        $this->syncCartToDb();

        return response()->json([
            'success' => true,
            'cart_count' => array_sum($cart),
        ]);
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        unset($cart[$request->product_id]);
        session()->put('cart', $cart);
        $this->syncCartToDb();

        return response()->json([
            'success' => true,
            'cart_count' => array_sum($cart),
        ]);
    }

    public function count()
    {
        $cart = session()->get('cart', []);
        return response()->json(['cart_count' => array_sum($cart)]);
    }

    public function clear()
    {
        session()->forget('cart');
        if (auth()->check()) {
            auth()->user()->update(['cart_data' => null]);
        }
        return redirect()->route('cart')->with('success', 'Your cart has been cleared.');
    }

    public function nearestStore(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $store = $this->storeService->findNearestStore($request->lat, $request->lng);

        // ✅ Cart-Aware Redirection Logic
        // If cart has non-"Crush Stone" products AND nearest store is "Jabulani Quarries Tsolo", 
        // redirect to "Jabulani Hardware Tsolo".
        if ($store && str_contains(strtolower($store->name), 'quarries')) {
            $cart = session()->get('cart', []);
            if (!empty($cart)) {
                $hasOtherProducts = \App\Models\Product::whereIn('id', array_keys($cart))
                    ->whereHas('subcategory', function ($query) {
                        $query->where('name', '!=', 'Crush Stone');
                    })->exists();

                if ($hasOtherProducts) {
                    $tsoloHardware = \App\Models\Store::where('name', 'LIKE', '%Hardware Tsolo%')->first();
                    if ($tsoloHardware) {
                        $store = $tsoloHardware;
                    }
                }
            }
        }

        if ($store) {
            return response()->json([
                'success' => true,
                'store' => [
                    'id' => $store->id,
                    'name' => $store->name,
                    'address' => $store->address,
                ]
            ]);
        }

        return response()->json(['success' => false, 'message' => 'No stores found.']);
    }

    public function checkoutAuth()
    {
        if (auth()->check()) {
            return redirect()->route('checkout');
        }
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('products')->with('error', 'Your cart is empty.');
        }

        // Ensure social login returns here
        session()->put('url.intended', route('checkout'));

        return view('frontend.checkout-auth');
    }

    public function guestCheckout()
    {
        session()->put('guest_checkout', true);
        return redirect()->route('checkout');
    }

    public function checkout()
    {
        if (auth()->check()) {
            $user = auth()->user();
            if (empty(session('cart')) && !empty($user->cart_data)) {
                session()->put('cart', $user->cart_data);
            }
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('products')->with('error', 'Your cart is empty.');
        }

        // 1. If logged in, must be verified
        if (auth()->check()) {
            if (!auth()->user()->hasVerifiedEmail()) {
                return redirect()->route('verification.notice')->with('error', 'Please verify your email to proceed with checkout.');
            }
        }
        // 2. If guest, must have guest_checkout session
        elseif (!session()->get('guest_checkout')) {
            return redirect()->route('checkout.auth');
        }

        $products = \App\Models\Product::whereIn('id', array_keys($cart))->get()->map(function ($product) use ($cart) {
            $product->cart_quantity = $cart[$product->id];
            $product->cart_subtotal = $product->price * $cart[$product->id];
            return $product;
        });

        $hasOtherProducts = \App\Models\Product::whereIn('id', array_keys($cart))
            ->whereHas('subcategory', function ($query) {
                $query->where('name', '!=', 'Crush Stone');
            })->exists();
        $cartHasOnlyCrushStone = !$hasOtherProducts;

        $total = $products->sum('cart_subtotal');
        $stores = \App\Models\Store::all();

        $user = auth()->user();
        $defaultShipping = null;
        $addresses = collect();
        if ($user) {
            $addresses = $user->addresses()->latest()->get();
            $defaultShipping = $addresses->where('is_default', true)->first();
        }

        return view('frontend.checkout', compact('products', 'total', 'stores', 'user', 'defaultShipping', 'addresses', 'cartHasOnlyCrushStone'));
    }

    public function processCheckout(Request $request)
    {
        // 1. If logged in, must be verified
        if (auth()->check()) {
            if (!auth()->user()->hasVerifiedEmail()) {
                return redirect()->route('verification.notice')->with('error', 'Please verify your email to proceed with checkout.');
            }
        }
        // 2. If guest, must have guest_checkout session
        elseif (!session()->get('guest_checkout')) {
            return redirect()->route('checkout.auth');
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'customer_city' => 'required|string|max:100',
            'customer_postal_code' => 'nullable|string|max:10',
            'payment_method' => 'required|in:eft,payfast',
            'order_type' => 'required|in:pickup,delivery',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'payment_screenshot' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('products')->with('error', 'Your cart is empty.');
        }

        // Store selection logic
        $storeId = $request->store_id;
        $store = \App\Models\Store::find($storeId);

        // Distance Check for Delivery
        $finalOrderType = $request->order_type;
        if ($request->order_type === 'delivery' && $request->lat && $request->lng && $store) {
            $dist = $this->calculateDistance($request->lat, $request->lng, $store->lat, $store->lng);
            $maxDistance = \App\Models\Setting::where('key', 'max_delivery_km')->first()?->value ?? 300;

            if ($dist > $maxDistance) {
                $finalOrderType = 'pickup';
                session()->flash('info', "Location exceeds {$maxDistance}km delivery radius ({$dist}km). Strategy adjusted to Warehouse Pickup.");
            }
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $products = \App\Models\Product::whereIn('id', array_keys($cart))->get();
            $total = 0;
            foreach ($products as $p) {
                $total += $p->price * $cart[$p->id];
            }

            $orderData = [
                'order_number' => 'JB-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                'user_id' => auth()->id(),
                'store_id' => $storeId,
                'total' => $total,
                'vat' => $total * 0.15,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'order_type' => $finalOrderType,
                'notes' => $request->notes,
                'lat' => $request->lat,
                'lng' => $request->lng,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'customer_city' => $request->customer_city,
                'customer_postal_code' => $request->customer_postal_code,
            ];

            // Auto-update user profile if missing data
            if (auth()->check()) {
                $user = auth()->user();
                $updateData = [];
                if (empty($user->phone))
                    $updateData['phone'] = $request->customer_phone;
                // You might also want to save the address as a default address if none exists
                if (!empty($updateData)) {
                    $user->update($updateData);
                }

                if ($user->addresses()->count() === 0) {
                    $user->addresses()->create([
                        'address_name' => 'Default Site',
                        'address_line_1' => $request->customer_address,
                        'city' => $request->customer_city,
                        'postal_code' => $request->customer_postal_code,
                        'is_default' => true
                    ]);
                }
            }

            if ($request->hasFile('payment_screenshot')) {
                $file = $request->file('payment_screenshot');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('payments'), $filename);
                $orderData['payment_screenshot'] = 'payments/' . $filename;
            }

            // For EFT, we might want to override status
            if ($request->payment_method === 'eft') {
                $orderData['status'] = 'awaiting_payment';
            }

            $order = Order::create($orderData);

            foreach ($products as $p) {
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $p->id,
                    'quantity' => $cart[$p->id],
                    'price' => $p->price,
                    'vat' => ($p->price * $cart[$p->id]) * 0.15,
                ]);
            }

            // Notify Admins of new order
            $admins = \App\Models\User::where('role', 'admin')->get();
            \Illuminate\Support\Facades\Notification::send($admins, new NewOrderNotification($order));

            \Illuminate\Support\Facades\DB::commit();
            session()->forget('cart');
            if (auth()->check()) {
                auth()->user()->update(['cart_data' => null]);
            }

            if ($request->payment_method === 'payfast' || $request->payment_method === 'stripe') {
                $stripeEnabled = \App\Models\Setting::where('key', 'stripe_enabled')->first()?->value === '1';
                $stripeSecret = \App\Models\Setting::where('key', 'stripe_secret_key')->first()?->value;

                if ($stripeEnabled && !empty($stripeSecret)) {
                    try {
                        Stripe::setApiKey($stripeSecret);

                        $lineItems = [];
                        foreach ($products as $p) {
                            $lineItems[] = [
                                'price_data' => [
                                    'currency' => 'zar',
                                    'product_data' => [
                                        'name' => $p->name,
                                    ],
                                    'unit_amount' => (int) ($p->price * 100), // Stripe uses cents
                                ],
                                'quantity' => $cart[$p->id],
                            ];
                        }

                        $session = Session::create([
                            'payment_method_types' => ['card'],
                            'line_items' => $lineItems,
                            'mode' => 'payment',
                            'success_url' => route('order.success') . '?order_number=' . $order->order_number,
                            'cancel_url' => route('checkout'),
                            'metadata' => [
                                'order_id' => $order->id,
                                'order_number' => $order->order_number,
                            ],
                        ]);

                        return redirect($session->url);
                    } catch (\Exception $stripeEx) {
                        \Illuminate\Support\Facades\Log::error('Stripe Session Creation Failed: ' . $stripeEx->getMessage());
                        return redirect()->route('order.success')
                            ->with('order_number', $order->order_number)
                            ->with('order_id', $order->id)
                            ->with('warning', 'Payment gateway initialization failed, but your order has been recorded. Our team will contact you for settlement.');
                    }
                } else {
                    \Illuminate\Support\Facades\Log::warning('Stripe attempted but not fully configured/enabled.');
                    return redirect()->route('order.success')
                        ->with('order_number', $order->order_number)
                        ->with('order_id', $order->id)
                        ->with('warning', 'Online payment is currently unavailable. Your order has been placed as a pending request.');
                }
            }

            return redirect()->route('order.success')->with('order_number', $order->order_number)->with('order_id', $order->id)->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Something went wrong. Please try again. ' . $e->getMessage());
        }
    }

    public function orderSuccess(Request $request)
    {
        $orderNumber = $request->order_number ?? session('order_number');
        if (!$orderNumber) {
            return redirect()->route('home');
        }

        $order = Order::where('order_number', $orderNumber)->first();

        // If coming back from Stripe (payfast) and still pending, mark as processing
        if ($order && $order->payment_method === 'payfast' && $order->status === 'pending') {
            $order->update(['status' => 'processing']);

            // Send confirmation email automatically for Stripe success
            try {
                \Illuminate\Support\Facades\Mail::to($order->customer_email)->send(new \App\Mail\OrderConfirmed($order));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Stripe Order Email Failed: ' . $e->getMessage());
            }
        }

        return view('frontend.order-success', compact('orderNumber', 'order'));
    }
}
