@extends('layouts.branch')

@section('title', 'Branch Orders - ' . $store->name)

@section('content')
    <div class="mb-4">
        <h5 class="mb-0 fw-bold">Recent Orders</h5>
        <small class="text-muted">Fulfill orders assigned to your branch based on customer proximity.</small>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Order Number</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Total Amount</th>
                            <th>Date</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-primary">#{{ $order->order_number }}</span>
                                </td>
                                <td>{{ $order->user->name }}</td>
                                <td>
                                    <span
                                        class="badge @if($order->status == 'pending') bg-warning @elseif($order->status == 'completed') bg-success @else bg-secondary @endif text-uppercase">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td>R {{ number_format($order->total, 2) }}</td>
                                <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('branch.orders.show', $order) }}"
                                        class="btn btn-sm btn-outline-dark">View & Process</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No orders found for this branch.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-top">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection