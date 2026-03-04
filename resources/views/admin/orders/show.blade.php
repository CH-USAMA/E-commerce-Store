@extends('layouts.admin')

@section('title', 'Order Details')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Order: {{ $order->order_number }}</h5>
                    <div>
                        <span class="badge bg-info text-white me-2">{{ ucfirst($order->order_type) }}</span>
                        <span class="badge bg-primary">{{ ucfirst($order->status) }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <h6>Order Items</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name }}</td>
                                        <td>R {{ number_format($item->price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td class="text-end">R {{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total</th>
                                    <th class="text-end text-primary">R {{ number_format($order->total, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> {{ $order->customer_name }}</p>
                            <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                            <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Address:</strong> {{ $order->customer_address }}</p>
                            <p><strong>City:</strong> {{ $order->customer_city }}</p>
                            <p><strong>Postal Code:</strong> {{ $order->customer_postal_code }}</p>
                            @if($order->lat && $order->lng)
                                <p><strong>Location:</strong>
                                    <a href="https://www.google.com/maps?q={{ $order->lat }},{{ $order->lng }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary mt-1">
                                        <i class="fas fa-map-marker-alt"></i> View on Maps
                                    </a>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Update Status</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <select name="status" class="form-select">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing
                                </option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered
                                </option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                                </option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-jabulani w-100">Update Status</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Store (Branch) Details</h5>
                </div>
                <div class="card-body">
                    <p><strong>Branch:</strong> {{ $order->store->name ?? 'N/A' }}</p>
                    <p><strong>Contact:</strong> {{ $order->store->phone ?? 'N/A' }}</p>
                    <p><strong>Address:</strong> {{ $order->store->address ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection