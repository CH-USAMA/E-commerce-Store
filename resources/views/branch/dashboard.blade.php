@extends('layouts.branch')

@section('title', 'Branch Stats - ' . $store->name)

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm h-100 bg-white">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="bi bi-cart-check-fill text-primary fs-4"></i>
                        </div>
                        <h6 class="card-subtitle text-muted mb-0">Total Orders</h6>
                    </div>
                    <h3 class="card-title fw-bold mb-1">{{ number_format($stats['total_orders']) }}</h3>
                    <small class="text-success"><i class="bi bi-arrow-up"></i> Lifetime</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100 bg-white border-start border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                            <i class="bi bi-hourglass-split text-warning fs-4"></i>
                        </div>
                        <h6 class="card-subtitle text-muted mb-0">Pending</h6>
                    </div>
                    <h3 class="card-title fw-bold mb-1">{{ number_format($stats['pending_orders']) }}</h3>
                    <small class="text-muted">Awaiting Action</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100 bg-white">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="bi bi-currency-dollar text-success fs-4"></i>
                        </div>
                        <h6 class="card-subtitle text-muted mb-0">Revenue (R)</h6>
                    </div>
                    <h3 class="card-title fw-bold mb-1">{{ number_format($stats['total_revenue'], 2) }}</h3>
                    <small class="text-muted">Completed Only</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100 bg-white border-start border-4 border-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3">
                            <i class="bi bi-exclamation-triangle-fill text-danger fs-4"></i>
                        </div>
                        <h6 class="card-subtitle text-muted mb-0">Low Stock</h6>
                    </div>
                    <h3 class="card-title fw-bold mb-1">{{ number_format($stats['low_stock_count']) }}</h3>
                    <small class="text-danger">Critical Alerts</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 fw-bold">Recent Branch Orders</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Order#</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                    <tr>
                                        <td class="ps-4 fw-bold text-primary">#{{ $order->order_number }}</td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>R {{ number_format($order->total, 2) }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('branch.orders.show', $order->id) }}"
                                                class="btn btn-sm btn-outline-dark">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No orders yet for this branch.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 bg-dark text-white">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Quick Actions</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('branch.orders.index') }}" class="btn btn-jabulani">
                            <i class="bi bi-list-check me-2"></i> Manage All Orders
                        </a>
                        <a href="{{ route('branch.stocks.index') }}" class="btn btn-outline-light">
                            <i class="bi bi-box-seam me-2"></i> Update Inventory
                        </a>
                    </div>

                    <hr class="my-4">

                    <h6 class="fw-bold mb-2">Branch Information</h6>
                    <div class="small">
                        <div class="mb-1 text-muted">Address:</div>
                        <div>{{ $store->address }}</div>
                        <div class="mt-2 text-muted">Province:</div>
                        <div>{{ $store->province }}</div>
                        <div class="mt-2 text-muted">Contact:</div>
                        <div>{{ $store->contact_details }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection