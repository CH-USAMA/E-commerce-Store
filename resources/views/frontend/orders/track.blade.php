@extends('layouts.frontend')

@section('title', 'Track Your Order - Jabulani Group')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">Track <span>Your Order</span></h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">track order</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    @include('frontend.partials.ticker')

    <div class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="order-tracking-card bg-dark p-4 p-md-5 rounded shadow-lg border border-secondary mb-5">
                        <h2 class="h3 text-warning mb-4 text-center">Enter Your Order Number</h2>
                        <form action="{{ route('order.track.submit') }}" method="POST">
                            @csrf
                            <div class="input-group input-group-lg">
                                <input type="text" name="order_number"
                                    class="form-control bg-transparent text-white border-secondary"
                                    placeholder="e.g. JB-20240304-X1Y2Z3"
                                    value="{{ old('order_number', $order->order_number ?? '') }}" required>
                                <button class="btn btn-highlighted px-4" type="submit">Track Now</button>
                            </div>
                            <p class="text-muted mt-3 text-center small">Your order number can be found in your confirmation
                                message or email.</p>
                        </form>
                    </div>

                    @if(isset($order))
                        <div class="order-details-card bg-dark p-4 rounded border border-secondary wow fadeInUp">
                            <div
                                class="d-flex justify-content-between align-items-center mb-4 border-bottom border-secondary pb-3">
                                <div>
                                    <h3 class="h4 text-warning mb-1">Order #{{ $order->order_number }}</h3>
                                    <p class="text-muted mb-0">Placed on {{ $order->created_at->format('M d, Y') }}</p>
                                </div>
                                <div class="text-end">
                                    <span
                                        class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }} fs-6 px-3 py-2 text-capitalize">
                                        {{ $order->status }}
                                    </span>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <h4 class="h6 text-warning text-uppercase mb-3">Order Information</h4>
                                    <p class="text-white mb-1"><strong>Customer:</strong> {{ $order->customer_name }}</p>
                                    <p class="text-white mb-1"><strong>Store:</strong> {{ $order->store->name ?? 'N/A' }}</p>
                                    <p class="text-white mb-1"><strong>Type:</strong> {{ ucfirst($order->order_type) }}</p>
                                    <p class="text-white mb-0"><strong>Payment:</strong>
                                        {{ strtoupper($order->payment_method) }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="h6 text-warning text-uppercase mb-3">Shipping Address</h4>
                                    <p class="text-white mb-0">
                                        {{ $order->customer_address }}<br>
                                        {{ $order->customer_city }}, {{ $order->customer_postal_code }}
                                    </p>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-dark table-borderless align-middle">
                                    <thead class="border-bottom border-secondary">
                                        <tr>
                                            <th>Product</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-end">Price</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            @php
                                                                $image = $item->product->image ?? '';
                                                                $imageSrc = $image ? (Str::contains($image, 'images/') ? asset($image) : asset('' . $image)) : asset('images/placeholder.webp');
                                                            @endphp
                                                            <img src="{{ $imageSrc }}" class="rounded shadow-sm"
                                                                style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #333;">
                                                        </div>
                                                        <span
                                                            class="text-white">{{ $item->product->name ?? 'Deleted Product' }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-center text-white">{{ $item->quantity }}</td>
                                                <td class="text-end text-white">R {{ number_format($item->price, 2) }}</td>
                                                <td class="text-end text-white">R
                                                    {{ number_format($item->price * $item->quantity, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="border-top border-secondary">
                                        <tr>
                                            <td colspan="3" class="text-end text-muted fw-bold py-3">Subtotal:</td>
                                            <td class="text-end text-white py-3">R
                                                {{ number_format($order->total - $order->vat, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end text-muted fw-bold py-2">VAT (15%):</td>
                                            <td class="text-end text-white py-2">R {{ number_format($order->vat, 2) }}</td>
                                        </tr>
                                        <tr class="fs-5">
                                            <td colspan="3" class="text-end text-warning fw-bold py-3">Total Amount:</td>
                                            <td class="text-end text-warning fw-bold py-3">R
                                                {{ number_format($order->total, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            @if($order->notes)
                                <div class="mt-4 p-3 bg-secondary bg-opacity-10 rounded border border-secondary border-dashed">
                                    <h5 class="h6 text-warning mb-2">Order Notes:</h5>
                                    <p class="text-muted mb-0 italic">{{ $order->notes }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .order-tracking-card {
            background: linear-gradient(145deg, #1a1a1a, #0d0d0d);
        }

        .order-details-card {
            background: #111;
        }

        .table-dark {
            background: transparent;
        }

        .border-dashed {
            border-style: dashed !important;
        }

        .italic {
            font-style: italic;
        }
    </style>
@endpush