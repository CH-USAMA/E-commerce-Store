@extends('layouts.branch')

@section('title', 'Order #' . $order->order_number)

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 fw-bold">Order Details</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead class="bg-light">
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->product->name }}</strong><br>
                                            <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">R {{ number_format($item->price, 2) }}</td>
                                        <td class="text-end">R {{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-top">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">VAT
                                        ({{ $order->items->first()->product->vat_rate ?? 15 }}%)</td>
                                    <td class="text-end">R {{ number_format($order->vat, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold fs-5">Total Amount</td>
                                    <td class="text-end fw-bold fs-5 text-jabulani">R {{ number_format($order->total, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 fw-bold">Customer Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Name</p>
                            <p class="fw-bold">{{ $order->user->name }}</p>
                            <p class="mb-1 text-muted small">Email</p>
                            <p class="fw-bold">{{ $order->user->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Payment Method</p>
                            <p class="fw-bold uppercase">{{ $order->payment_method }}</p>
                            @if($order->notes)
                                <p class="mb-1 text-muted small">Notes</p>
                                <p class="fw-bold">{{ $order->notes }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 fw-bold">Update Order Status</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('branch.orders.status', $order) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Current Status: <span
                                    class="badge bg-dark">{{ strtoupper($order->status) }}</span></label>
                            <select name="status" class="form-select mb-3">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing
                                </option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed
                                </option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                                </option>
                            </select>
                            <button type="submit" class="btn btn-jabulani w-100">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body text-center py-4">
                    <p class="mb-2">Need to contact the customer?</p>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', '+27...') }}" class="btn btn-success">
                        <i class="bi bi-whatsapp me-2"></i> WhatsApp Customer
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection