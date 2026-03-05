@extends('layouts.frontend')

@section('title', 'My Orders - Jabulani Group')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">My Orders</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">orders</li>
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
                        <a href="{{ route('user.dashboard') }}"
                            class="list-group-item list-group-item-action bg-dark text-white border-bottom border-secondary border-0">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        <a href="{{ route('user.orders.index') }}"
                            class="list-group-item list-group-item-action bg-highlighted text-dark fw-bold">
                            <i class="fas fa-box me-2"></i> My Orders
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="list-group-item list-group-item-action bg-dark text-danger border-0">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="card bg-dark border-secondary">
                        <div class="card-body">
                            <h2 class="h4 text-warning mb-4">Order History</h2>

                            @if($orders->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-dark table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Order #</th>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Status</th>
                                                <th>Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($orders as $order)
                                                <tr>
                                                    <td>{{ $order->order_number }}</td>
                                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                                    <td>{{ ucfirst($order->order_type) }}</td>
                                                    <td>
                                                        <span
                                                            class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }}">
                                                            {{ ucfirst($order->status) }}
                                                        </span>
                                                    </td>
                                                    <td>R {{ number_format($order->total, 2) }}</td>
                                                    <td>
                                                        <a href="{{ route('user.orders.show', $order) }}"
                                                            class="btn btn-sm btn-highlighted">View Details & Tracking</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-4">
                                    {{ $orders->links('pagination::bootstrap-5') }}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <p class="text-muted mb-0">You haven't placed any orders yet.</p>
                                    <a href="{{ route('products') }}" class="btn btn-highlighted mt-3">Start Shopping</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection