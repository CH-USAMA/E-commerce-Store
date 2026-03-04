<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_revenue' => \App\Models\Order::where('status', 'completed')->sum('total'),
            'total_orders' => \App\Models\Order::count(),
            'total_products' => \App\Models\Product::count(),
            'total_stores' => \App\Models\Store::count(),
            'recent_orders' => \App\Models\Order::with('store', 'user')->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
