<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()->latest()->paginate(10);
        return view('user.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
        $order->load(['items.product', 'store']);
        return view('user.orders.show', compact('order'));
    }
}
