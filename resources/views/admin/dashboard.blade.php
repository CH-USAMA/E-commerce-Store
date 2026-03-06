@extends('layouts.admin')

@section('title', 'Dashboard Overview')

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-white shadow-sm border-0 mb-4 p-3 border-start border-4 border-warning">
                <div class="text-muted small text-uppercase fw-bold">Total Revenue</div>
                <div class="h2 fw-bold mb-0">R {{ number_format($stats['total_revenue'], 2) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white shadow-sm border-0 mb-4 p-3 border-start border-4 border-dark">
                <div class="text-muted small text-uppercase fw-bold">Total Orders</div>
                <div class="h2 fw-bold mb-0">{{ $stats['total_orders'] }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white shadow-sm border-0 mb-4 p-3 border-start border-4 border-secondary">
                <div class="text-muted small text-uppercase fw-bold">Products</div>
                <div class="h2 fw-bold mb-0">{{ $stats['total_products'] }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white shadow-sm border-0 mb-4 p-3 border-start border-4 border-info">
                <div class="text-muted small text-uppercase fw-bold">Active Stores</div>
                <div class="h2 fw-bold mb-0">{{ $stats['total_stores'] }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white shadow-sm border-0 mb-4 p-3 border-start border-4 border-primary">
                <div class="text-muted small text-uppercase fw-bold">System Email</div>
                <form action="{{ route('admin.system.test-email') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-primary mt-2 w-100">Send Test Email</button>
                </form>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Recent Orders</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Order #</th>
                                    <th>Customer</th>
                                    <th>Store</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stats['recent_orders'] as $order)
                                    <tr>
                                        <td class="ps-4 fw-bold">#{{ $order->order_number }}</td>
                                        <td>{{ $order->user->name ?? 'Guest' }}</td>
                                        <td>{{ $order->store->name }}</td>
                                        <td>R {{ number_format($order->total, 2) }}</td>
                                        <td>
                                            <span
                                                class="badge rounded-pill bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="#" class="btn btn-sm btn-outline-dark">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">No recent orders found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection