<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $store = $user->managedStore;

        if (!$store) {
            return view('branch.no_store');
        }

        $stats = [
            'total_orders' => \App\Models\Order::where('store_id', $store->id)->count(),
            'pending_orders' => \App\Models\Order::where('store_id', $store->id)->where('status', 'pending')->count(),
            'total_revenue' => \App\Models\Order::where('store_id', $store->id)
                ->where('status', 'completed')
                ->sum('total'),
            'low_stock_count' => \App\Models\ProductStoreStock::where('store_id', $store->id)
                ->where('quantity', '<', 10)
                ->count(),
        ];

        $recentOrders = \App\Models\Order::where('store_id', $store->id)
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('branch.dashboard', compact('store', 'stats', 'recentOrders'));
    }
}
