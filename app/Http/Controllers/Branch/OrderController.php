<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $store = auth()->user()->managedStore;

        if (!$store) {
            return view('branch.no_store');
        }

        $orders = \App\Models\Order::where('store_id', $store->id)
            ->with('user')
            ->latest()
            ->paginate(15);

        return view('branch.orders.index', compact('orders', 'store'));
    }

    public function show(\App\Models\Order $order)
    {
        $store = auth()->user()->managedStore;

        // Ensure the order belongs to this store
        if ($order->store_id !== $store->id) {
            abort(403);
        }

        $order->load(['user', 'items.product']);

        return view('branch.orders.show', compact('order', 'store'));
    }

    public function updateStatus(Request $request, \App\Models\Order $order)
    {
        $store = auth()->user()->managedStore;

        if ($order->store_id !== $store->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated to ' . $request->status);
    }
}
