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

    /**
     * "Order again" — re-place a previous order.
     *
     * Deliberately dual-mode, decided server-side so the button never needs changing:
     *
     *   hide_pricing = 1  → hand the items to WhatsApp, matching how enquiries are taken
     *                       today. /cart/* is behind `pricing.enabled` and would just
     *                       bounce the customer to /contact.
     *   hide_pricing = 0  → merge the items into the cart and go there.
     *
     * Re-enabling pricing therefore switches this to a real cart reorder on its own.
     */
    public function reorder(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product');

        // Products can be deleted or deactivated between orders; skip those rather than
        // failing the whole action, and tell the customer what was dropped.
        $available = $order->items->filter(
            fn ($item) => $item->product && $item->product->status === 'active'
        );
        $skipped = $order->items->count() - $available->count();

        if ($available->isEmpty()) {
            return back()->with('error', 'None of the items on that order are available any more.');
        }

        $hidePricing = \App\Models\Setting::where('key', 'hide_pricing')->value('value') === '1';

        if ($hidePricing) {
            return redirect()->away($this->whatsappReorderUrl($order, $available));
        }

        // Merge into the existing cart rather than replacing it.
        $cart = session()->get('cart', []);
        foreach ($available as $item) {
            $cart[$item->product_id] = ($cart[$item->product_id] ?? 0) + $item->quantity;
        }
        session()->put('cart', $cart);

        // Mirrors CartController::syncCartToDb(). Kept local on purpose — CartController
        // is the payment path and is deliberately not being refactored here.
        if (auth()->check()) {
            auth()->user()->update(['cart_data' => $cart]);
        }

        return redirect()->route('cart')->with(
            'success',
            $skipped === 0
                ? 'Added ' . $available->count() . ' item(s) from order ' . $order->order_number . ' to your cart.'
                : 'Added ' . $available->count() . ' item(s) to your cart. ' . $skipped . ' item(s) are no longer available.'
        );
    }

    /**
     * Pre-filled WhatsApp message listing the previous order's items.
     *
     * Uses the same `invoice_company_phone` setting as the storefront's Contact Us CTA
     * (see frontend/partials/price_or_contact.blade.php).
     */
    private function whatsappReorderUrl(Order $order, $items): string
    {
        $phone = preg_replace(
            '/[^0-9]/',
            '',
            \App\Models\Setting::where('key', 'invoice_company_phone')->value('value') ?? ''
        );

        $lines = $items->map(
            fn ($item) => '• ' . $item->quantity . ' x ' . $item->product->name
        )->implode("\n");

        $message = "Hi, I'd like to order these again (previous order {$order->order_number}):\n\n{$lines}";

        return 'https://wa.me/' . $phone . '?text=' . rawurlencode($message);
    }
}
