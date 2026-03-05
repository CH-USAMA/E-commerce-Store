<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Order;

class CartController extends Controller
{
    protected $storeService;

    public function __construct(\App\Services\StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    public function index()
    {
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

    public function checkoutAuth()
    {
        if (auth()->check()) {
            return redirect()->route('checkout');
        }
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('products')->with('error', 'Your cart is empty.');
        }
        return view('frontend.checkout-auth');
    }

    public function guestCheckout()
    {
        session()->put('guest_checkout', true);
        return redirect()->route('checkout');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('products')->with('error', 'Your cart is empty.');
        }

        if (!auth()->check() && !session()->get('guest_checkout')) {
            return redirect()->route('checkout.auth');
        }

        $products = \App\Models\Product::whereIn('id', array_keys($cart))->get()->map(function ($product) use ($cart) {
            $product->cart_quantity = $cart[$product->id];
            $product->cart_subtotal = $product->price * $cart[$product->id];
            return $product;
        });
        $user = auth()->user();
        $defaultShipping = null;
        if ($user) {
            $defaultShipping = $user->addresses()->where('type', 'shipping')->where('is_default', true)->first();
        }

        return view('frontend.checkout', compact('products', 'total', 'stores', 'user', 'defaultShipping'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'customer_city' => 'required|string|max:100',
            'customer_postal_code' => 'required|string|max:10',
            'payment_method' => 'required|in:cod,eft,payfast',
            'order_type' => 'required|in:pickup,delivery',
            'store_id' => 'required|exists:stores,id',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('products')->with('error', 'Your cart is empty.');
        }

        // Store selection logic
        $storeId = $request->store_id;
        // If it was supposed to be auto-detected but user didn't change it, we stick with what they sent or what JS calculated.

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $products = \App\Models\Product::whereIn('id', array_keys($cart))->get();
            $total = 0;
            foreach ($products as $p) {
                $total += $p->price * $cart[$p->id];
            }

            $order = Order::create([
                'order_number' => 'JB-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                'user_id' => auth()->id(),
                'store_id' => $storeId,
                'total' => $total,
                'vat' => $total * 0.15, // Assuming 15% VAT
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'order_type' => $request->order_type,
                'notes' => $request->notes,
                'lat' => $request->lat,
                'lng' => $request->lng,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'customer_city' => $request->customer_city,
                'customer_postal_code' => $request->customer_postal_code,
            ]);

            foreach ($products as $p) {
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $p->id,
                    'quantity' => $cart[$p->id],
                    'price' => $p->price,
                    'vat' => ($p->price * $cart[$p->id]) * 0.15,
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();
            session()->forget('cart');

            return redirect()->route('order.success')->with('order_number', $order->order_number);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Something went wrong. Please try again. ' . $e->getMessage());
        }
    }

    public function orderSuccess()
    {
        $orderNumber = session('order_number');
        if (!$orderNumber)
            return redirect()->route('home');
        return view('frontend.order-success', compact('orderNumber'));
    }
}
