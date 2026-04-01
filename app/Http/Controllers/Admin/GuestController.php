<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class GuestController extends Controller
{
    /**
     * Display a listing of unique guest customers from the orders table.
     */
    public function index()
    {
        // Fetch unique guest customers based on email
        $guests = Order::whereNull('user_id')
            ->select('customer_email', 'customer_name', 'customer_phone', DB::raw('MAX(created_at) as last_order_at'), DB::raw('COUNT(*) as order_count'))
            ->where('customer_email', '!=', '[PURGED]')
            ->groupBy('customer_email', 'customer_name', 'customer_phone')
            ->orderBy('last_order_at', 'desc')
            ->paginate(15);

        return view('admin.guests.index', compact('guests'));
    }

    /**
     * Purge data for a specific guest email.
     */
    public function purge(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->email;

        // Run the artisan command to purge data for this email
        Artisan::call('app:purge-guest-order-data', [
            '--email' => $email,
            '--no-interaction' => true,
        ]);

        return redirect()->route('admin.guests.index')->with('success', "Personal data for guest {$email} has been anonymized.");
    }

    /**
     * Purge all guest data older than 7 years.
     */
    public function purgeOld(Request $request)
    {
        Artisan::call('app:purge-guest-order-data', [
            '--days' => 2555, // 7 years
            '--no-interaction' => true,
        ]);

        return redirect()->route('admin.guests.index')->with('success', "All guest data older than 7 years has been anonymized.");
    }
}
