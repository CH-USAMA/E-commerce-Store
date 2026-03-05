@extends('layouts.frontend')

@section('title', 'Order ' . $order->order_number . ' - Jabulani Group')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">Order Details</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('user.orders.index') }}">orders</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $order->order_number }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <div class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 mb-4">
                    <div class="list-group bg-dark border-secondary rounded overflow-hidden">
                        <a href="{{ route('user.dashboard') }}" class="list-group-item list-group-item-action bg-dark text-white border-bottom border-secondary border-0">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        <a href="{{ route('user.orders.index') }}" class="list-group-item list-group-item-action bg-highlighted text-dark fw-bold">
                            <i class="fas fa-box me-2"></i> My Orders
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="list-group-item list-group-item-action bg-dark text-danger border-0">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-9">
                    <!-- Tracking Status Box -->
                    <div class="card bg-dark border-secondary mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3 class="h4 text-warning mb-0">Order Status: <span class="text-white">{{ ucfirst($order->status) }}</span></h3>
                                <p class="text-muted mb-0">Order Date: {{ $order->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                            
                            <!-- Simplified Tracking UI -->
                            <div class="position-relative m-4">
                                <div class="progress" style="height: 4px;">
                                    @php
                                        $progress = 0;
                                        if($order->status == 'pending') $progress = 25;
                                        elseif($order->status == 'processing') $progress = 50;
                                        elseif($order->status == 'ready_for_pickup' || $order->status == 'out_for_delivery') $progress = 75;
                                        elseif($order->status == 'completed') $progress = 100;
                                        elseif($order->status == 'cancelled') $progress = 100; // Red bar
                                    @endphp
                                    <div class="progress-bar {{ $order->status == 'cancelled' ? 'bg-danger' : 'bg-warning' }}" role="progressbar" style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between position-absolute top-0 w-100" style="margin-top: -10px;">
                                    <div class="text-center">
                                        <div class="rounded-circle {{ $progress >= 25 ? ($order->status == 'cancelled' ? 'bg-danger' : 'bg-warning') : 'bg-secondary' }} d-inline-block" style="width: 24px; height: 24px;"></div>
                                        <div class="mt-2 text-white small">Pending</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="rounded-circle {{ $progress >= 50 ? ($order->status == 'cancelled' ? 'bg-danger' : 'bg-warning') : 'bg-secondary' }} d-inline-block" style="width: 24px; height: 24px;"></div>
                                        <div class="mt-2 text-white small">Processing</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="rounded-circle {{ $progress >= 75 ? ($order->status == 'cancelled' ? 'bg-danger' : 'bg-warning') : 'bg-secondary' }} d-inline-block" style="width: 24px; height: 24px;"></div>
                                        <div class="mt-2 text-white small">{{ $order->order_type == 'pickup' ? 'Ready' : 'Shipping' }}</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="rounded-circle {{ $progress == 100 ? ($order->status == 'cancelled' ? 'bg-danger' : 'bg-warning') : 'bg-secondary' }} d-inline-block" style="width: 24px; height: 24px;"></div>
                                        <div class="mt-2 text-white small">{{ $order->status == 'cancelled' ? 'Cancelled' : 'Completed' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="card bg-dark border-secondary h-100">
                                <div class="card-body">
                                    <h4 class="h5 text-warning mb-3">Customer Details</h4>
                                    <p class="text-white mb-1"><strong>Name:</strong> {{ $order->customer_name }}</p>
                                    <p class="text-white mb-1"><strong>Email:</strong> {{ $order->customer_email }}</p>
                                    <p class="text-white mb-1"><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                                    <p class="text-white mb-0"><strong>Address:</strong><br>{{ $order->customer_address }}<br>{{ $order->customer_city }}, {{ $order->customer_postal_code }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-dark border-secondary h-100">
                                <div class="card-body">
                                    <h4 class="h5 text-warning mb-3">Order Info</h4>
                                    <p class="text-white mb-1"><strong>Type:</strong> {{ ucfirst($order->order_type) }}</p>
                                    <p class="text-white mb-1"><strong>Payment:</strong> {{ strtoupper($order->payment_method) }}</p>
                                    @if($order->store)
                                        <p class="text-white mb-1"><strong>Store:</strong> {{ $order->store->name }}</p>
                                        @if($order->order_type == 'pickup')
                                            <p class="text-white small mb-0">{{ $order->store->address }}</p>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-dark border-secondary">
                        <div class="card-body">
                            <h4 class="h5 text-warning mb-3">Order Items</h4>
                            <div class="table-responsive">
                                <table class="table table-dark table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Qty</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td>
                                                    @if($item->product)
                                                        <a href="{{ route('product.detail', $item->product->slug) }}" class="text-white text-decoration-none">
                                                            {{ $item->product->name }}
                                                        </a>
                                                    @else
                                                        Unknown Product
                                                    @endif
                                                </td>
                                                <td>R {{ number_format($item->price, 2) }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td class="text-end">R {{ number_format($item->price * $item->quantity, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end text-muted">Subtotal (excl VAT)</td>
                                            <td class="text-end text-white">R {{ number_format($order->total - $order->vat, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end text-muted">VAT (15%)</td>
                                            <td class="text-end text-white">R {{ number_format($order->vat, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end fw-bold text-warning h5">Total</td>
                                            <td class="text-end fw-bold text-warning h5">R {{ number_format($order->total, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
