<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    /**
     * Display the order tracking form.
     */
    public function index()
    {
        return view('frontend.orders.track');
    }

    /**
     * Track an order by its order number.
     */
    public function track(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string|max:50',
        ]);

        $order = Order::with(['items.product', 'store'])
            ->where('order_number', $request->order_number)
            ->first();

        if (!$order) {
            return back()->with('error', 'Order not found. Please check your order number.')->withInput();
        }

        return view('frontend.orders.track', compact('order'));
    }
}
