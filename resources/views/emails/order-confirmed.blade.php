<x-mail::message>
    # Order Confirmed!

    Hello **{{ $order->customer_name }}**,

    Thank you for choosing **Jabulani Group**. We're excited to let you know that we've received your order and it is
    now being processed.

    <x-mail::panel>
        **Order Number:** #{{ $order->order_number }}
        **Total Amount:** R{{ number_format($order->total, 2) }}
        **Payment Method:** {{ strtoupper($order->payment_method) }}
    </x-mail::panel>

    ### Delivery Details
    **Address:** {{ $order->customer_address }}, {{ $order->customer_city }}
    **Order Type:** {{ ucfirst($order->order_type) }}

    You can track the real-time status of your order using our tracking portal.

    <x-mail::button :url="route('order.track', ['order_number' => $order->order_number])">
        Track Your Order
    </x-mail::button>

    If you have any questions, feel free to reply to this email or contact us via WhatsApp.

    Thanks for your trust,<br>
    **The Jabulani Team**
</x-mail::message>