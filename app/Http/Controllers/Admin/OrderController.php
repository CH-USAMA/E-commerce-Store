<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Order::with(['store', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('store_id')) {
            $query->where('store_id', $request->store_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(20);
        $stores = \App\Models\Store::all();

        return view('admin.orders.index', compact('orders', 'stores'));
    }

    public function createFakeOrder()
    {
        $store = \App\Models\Store::inRandomOrder()->first();
        if (!$store) {
            return back()->with('error', 'No stores found to create fake order.');
        }

        $product = \App\Models\Product::inRandomOrder()->first();
        if (!$product) {
            return back()->with('error', 'No products found to create fake order.');
        }

        \DB::transaction(function () use ($store, $product) {
            $order = \App\Models\Order::create([
                'order_number' => 'ORD-' . strtoupper(\Str::random(8)),
                'user_id' => auth()->id(),
                'store_id' => $store->id,
                'customer_name' => 'Fake Customer ' . rand(100, 999),
                'customer_email' => 'fake' . rand(100, 999) . '@example.com',
                'customer_phone' => '+27' . rand(100000000, 999999999),
                'customer_address' => '123 Fake Street, Johannesburg',
                'customer_city' => 'Johannesburg',
                'customer_postal_code' => '2000',
                'total' => $product->price,
                'status' => 'pending',
                'payment_method' => 'cod',
                'order_type' => rand(0, 1) ? 'pickup' : 'delivery',
            ]);

            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => $product->price,
            ]);
        });

        return back()->with('success', 'Fake order created successfully for verification!');
    }

    public function show(\App\Models\Order $order)
    {
        $order->load(['items.product', 'store', 'user']);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, \App\Models\Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated successfully.');
    }

    public function destroy(\App\Models\Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }
}
