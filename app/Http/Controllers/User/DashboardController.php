<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $recentOrders = $user->orders()->latest()->take(5)->get();
        $totalSpent = $user->orders()->whereIn('status', ['completed', 'delivered', 'paid', 'processing'])->sum('total');
        
        return view('user.dashboard', compact('user', 'recentOrders', 'totalSpent'));
    }
}
