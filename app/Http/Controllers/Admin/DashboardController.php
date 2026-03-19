<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_revenue' => \App\Models\Order::whereIn('status', ['completed', 'processing', 'shipped', 'delivered'])->sum('total'),
            'total_orders' => \App\Models\Order::count(),
            'total_products' => \App\Models\Product::count(),
            'total_stores' => \App\Models\Store::count(),
            'recent_orders' => \App\Models\Order::with('store', 'user')->latest()->take(5)->get(),
            'sales_chart' => \App\Models\Order::select(\DB::raw('DATE(created_at) as date'), \DB::raw('SUM(total) as total'))
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get(),
            'top_products' => \App\Models\OrderItem::select('product_id', \DB::raw('SUM(quantity) as total_qty'))
                ->with('product')
                ->groupBy('product_id')
                ->orderBy('total_qty', 'DESC')
                ->take(5)
                ->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
