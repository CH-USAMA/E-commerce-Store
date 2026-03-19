<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $orders = auth()->user()->orders()->latest()->paginate($perPage)->withQueryString();
        return view('user.orders.index', compact('orders'));
    }

    public function export(Request $request)
    {
        $orders = auth()->user()->orders()->latest()->get();

        $filename = "my-orders-export-" . date('Y-m-d-His') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Order #', 'Total', 'Status', 'Type', 'Date'];

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->total,
                    $order->status,
                    $order->order_type,
                    $order->created_at->format('Y-m-d H:i')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
