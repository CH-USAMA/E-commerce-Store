<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmed;

class PaystackController extends Controller
{
    /**
     * Initialize Paystack Payment
     */
    public function initialize(Order $order)
    {
        $secretKey = Setting::where('key', 'paystack_secret_key')->first()?->value;

        if (empty($secretKey)) {
            Log::error('Paystack Secret Key is missing.');
            return redirect()->route('checkout')->with('error', 'Payment gateway is not properly configured.');
        }

        // Paystack amount is in cents
        $amount = (int) ($order->total * 100);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $secretKey,
                'Content-Type'  => 'application/json',
            ])->post('https://api.paystack.co/transaction/initialize', [
                'email'        => $order->customer_email,
                'amount'       => $amount,
                'reference'    => $order->order_number,
                'callback_url' => route('paystack.callback'),
                'metadata'     => [
                    'order_id' => $order->id,
                ],
            ]);

            $result = $response->json();

            if ($response->successful() && $result['status']) {
                return redirect($result['data']['authorization_url']);
            }

            Log::error('Paystack Initialization Failed', ['response' => $result]);
            return redirect()->route('checkout')->with('error', 'Could not initialize Paystack payment.');

        } catch (\Exception $e) {
            Log::error('Paystack Error: ' . $e->getMessage());
            return redirect()->route('checkout')->with('error', 'An error occurred during payment initiation.');
        }
    }

    /**
     * Paystack Callback Handler
     */
    public function callback(Request $request)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return redirect()->route('home')->with('error', 'No payment reference found.');
        }

        $secretKey = Setting::where('key', 'paystack_secret_key')->first()?->value;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $secretKey,
            ])->get("https://api.paystack.co/transaction/verify/{$reference}");

            $result = $response->json();

            if ($response->successful() && $result['status'] && $result['data']['status'] === 'success') {
                $order = Order::where('order_number', $reference)->first();

                if ($order && $order->status === 'pending') {
                    $order->update(['status' => 'processing']);

                    // Send confirmation email
                    try {
                        Mail::to($order->customer_email)->send(new OrderConfirmed($order));
                    } catch (\Exception $e) {
                        Log::error('Paystack Order Email Failed: ' . $e->getMessage());
                    }

                    return redirect()->route('order.success', ['order_number' => $order->order_number])
                        ->with('success', 'Payment successful!');
                }

                return redirect()->route('order.success', ['order_number' => $reference]);
            }

            Log::error('Paystack Verification Failed', ['response' => $result]);
            return redirect()->route('home')->with('error', 'Payment verification failed.');

        } catch (\Exception $e) {
            Log::error('Paystack Callback Error: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'An error occurred during payment verification.');
        }
    }
}
