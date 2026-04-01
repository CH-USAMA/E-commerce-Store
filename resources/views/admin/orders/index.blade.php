@extends('layouts.admin')

@section('title', 'Orders')

@section('content')

    {{-- Filters Card --}}
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="fw-bold" style="font-size: 0.83rem;">Filter Orders</div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.orders.export', request()->all()) }}" class="btn btn-outline-success btn-sm">
                    <i class="fas fa-file-csv me-1"></i> Export CSV
                </a>
                <a href="{{ route('admin.orders.fake') }}" class="btn btn-outline-secondary btn-sm">
                    Create Demo Order
                </a>
            </div>
        </div>
        <div class="card-body" style="padding: 1rem 1.25rem !important;">
            <form action="{{ route('admin.orders.index') }}" method="GET">
                <div class="row g-2 align-items-end">
                    <div class="col-sm-6 col-md-3 col-xl-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All Statuses</option>
                            <option value="pending"          {{ request('status') == 'pending'          ? 'selected' : '' }}>Pending</option>
                            <option value="processing"       {{ request('status') == 'processing'       ? 'selected' : '' }}>Processing</option>
                            <option value="shipped"          {{ request('status') == 'shipped'          ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered"        {{ request('status') == 'delivered'        ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled"        {{ request('status') == 'cancelled'        ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-3 col-xl-2">
                        <label class="form-label">Branch (Store)</label>
                        <select name="store_id" class="form-select form-select-sm">
                            <option value="">All Branches</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                                    {{ $store->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-2 col-xl-2">
                        <label class="form-label">From Date</label>
                        <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-sm-6 col-md-2 col-xl-2">
                        <label class="form-label">To Date</label>
                        <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-sm-6 col-md-2 col-xl-2">
                        <label class="form-label">Per Page</label>
                        <select name="per_page" class="form-select form-select-sm">
                            <option value="10"  {{ request('per_page') == 10  ? 'selected' : '' }}>10</option>
                            <option value="20"  {{ request('per_page') == 20  || !request('per_page') ? 'selected' : '' }}>20</option>
                            <option value="50"  {{ request('per_page') == 50  ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-auto col-xl-2">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-jabulani btn-sm flex-fill">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                                Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Orders Table --}}
    <div class="card">
        <div class="card-header">
            <div class="fw-bold" style="font-size: 0.83rem;">All Customer Orders</div>
        </div>
        <div class="card-body" style="padding: 0 !important;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Order #</th>
                            <th>Customer</th>
                            <th>Store (Branch)</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="pe-4 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold" style="font-family: var(--font-code); font-size: 0.78rem; color: var(--orange-400);">
                                        #{{ $order->order_number }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold" style="font-size: 0.82rem;">{{ $order->customer_name }}</div>
                                    <div style="font-size: 0.72rem; color: var(--text-muted);">{{ $order->customer_phone }}</div>
                                </td>
                                <td style="font-size: 0.82rem; color: var(--text-secondary);">{{ $order->store->name ?? 'N/A' }}</td>
                                <td class="fw-semibold" style="font-size: 0.83rem;">R {{ number_format($order->total, 2) }}</td>
                                <td>
                                    @php
                                        $statusMap = [
                                            'pending'          => 'bg-warning',
                                            'processing'       => 'bg-info',
                                            'shipped'          => 'bg-primary',
                                            'delivered'        => 'bg-success',
                                            'completed'        => 'bg-success',
                                            'cancelled'        => 'bg-danger',
                                            'awaiting_payment' => 'bg-warning',
                                        ];
                                        $sc = $statusMap[$order->status] ?? 'bg-secondary';
                                    @endphp
                                    <span class="badge {{ $sc }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                                </td>
                                <td style="font-size: 0.78rem; color: var(--text-muted);">{{ $order->created_at->format('d M Y, H:i') }}</td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-dark btn-sm">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5" style="color: var(--text-muted);">
                                    <i class="fas fa-database fa-2x d-block mb-2 opacity-20"></i>
                                    No orders found matching the selected filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-3 py-2 border-top" style="border-color: var(--border-default) !important;">
                {{ $orders->appends(request()->all())->links() }}
            </div>
        </div>
    </div>

@endsection