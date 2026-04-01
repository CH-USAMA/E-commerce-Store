@extends('layouts.admin')

@section('title', 'Order Details')

@push('css')
<style>
    .order-info-section .form-label {
        color: var(--text-secondary) !important;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }
    .order-info-section .info-value {
        color: #ffffff !important;
        font-weight: 500;
        font-size: 0.88rem !important;
    }
</style>
@endpush

@section('content')

    {{-- Back button + order header --}}
    <div class="d-flex align-items-center gap-3 mb-3">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
        <div class="flex-grow-1">
            <div class="fw-bold" style="font-size: 0.83rem;">
                Order #<span style="color: var(--orange-400); font-family: var(--font-code);">{{ $order->order_number }}</span>
            </div>
            <div style="font-size: 0.72rem; color: var(--text-muted);">Placed {{ $order->created_at->format('d M Y, H:i') }}</div>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-info">{{ ucfirst($order->order_type) }}</span>
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
            <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-file-pdf me-1"></i> Invoice
            </a>
        </div>
    </div>

    <div class="row g-3">

        {{-- LEFT: Items + Customer --}}
        <div class="col-lg-8">

            {{-- Order Items --}}
            <div class="card mb-3">
                <div class="card-header">
                    <div class="fw-bold" style="font-size: 0.83rem;">Order Items</div>
                </div>
                <div class="card-body" style="padding: 0 !important;">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Product</th>
                                    <th>Unit Price</th>
                                    <th>Qty</th>
                                    <th class="pe-4 text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td class="ps-4 fw-semibold" style="font-size: 0.83rem;">{{ $item->product->name }}</td>
                                        <td style="font-size: 0.83rem;">R {{ number_format($item->price, 2) }}</td>
                                        <td>
                                            <span class="badge bg-secondary">× {{ $item->quantity }}</span>
                                        </td>
                                        <td class="pe-4 text-end fw-semibold" style="font-size: 0.83rem;">
                                            R {{ number_format($item->price * $item->quantity, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="border-top: 1px solid var(--border-default);">
                                    <td colspan="3" class="ps-4 fw-bold text-end" style="font-size: 0.83rem; padding-top: 0.75rem !important; padding-bottom: 0.75rem !important;">
                                        Order Total
                                    </td>
                                    <td class="pe-4 text-end fw-bold" style="color: var(--orange-400); font-size: 1rem; padding-top: 0.75rem !important; padding-bottom: 0.75rem !important;">
                                        R {{ number_format($order->total, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Customer Information --}}
            <div class="card">
                <div class="card-header">
                    <div class="fw-bold" style="font-size: 0.83rem;">Customer Information</div>
                </div>
                <div class="card-body order-info-section">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex flex-column gap-2">
                                <div>
                                    <div class="form-label">Name</div>
                                    <div class="info-value">{{ $order->customer_name }}</div>
                                </div>
                                <div>
                                    <div class="form-label">Email</div>
                                    <div class="info-value">{{ $order->customer_email }}</div>
                                </div>
                                <div>
                                    <div class="form-label">Phone</div>
                                    <div class="info-value">{{ $order->customer_phone }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex flex-column gap-2">
                                <div>
                                    <div class="form-label">Address</div>
                                    <div class="info-value">{{ $order->customer_address }}</div>
                                </div>
                                <div>
                                    <div class="form-label">City</div>
                                    <div class="info-value">{{ $order->customer_city }}</div>
                                </div>
                                <div>
                                    <div class="form-label">Postal Code</div>
                                    <div class="info-value">{{ $order->customer_postal_code }}</div>
                                </div>
                                @if($order->lat && $order->lng)
                                    <div>
                                        <a href="https://www.google.com/maps?q={{ $order->lat }},{{ $order->lng }}" target="_blank"
                                           class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-map-marker-alt me-1"></i> View on Maps
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT: Status + Store + EFT --}}
        <div class="col-lg-4">

            {{-- Update Status --}}
            <div class="card mb-3">
                <div class="card-header">
                    <div class="fw-bold" style="font-size: 0.83rem;">Update Status</div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-2">
                            <select name="status" class="form-select">
                                <option value="awaiting_payment" {{ $order->status == 'awaiting_payment' ? 'selected' : '' }}>Awaiting Payment</option>
                                <option value="pending"          {{ $order->status == 'pending'          ? 'selected' : '' }}>Pending</option>
                                <option value="processing"       {{ $order->status == 'processing'       ? 'selected' : '' }}>Processing</option>
                                <option value="shipped"          {{ $order->status == 'shipped'          ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered"        {{ $order->status == 'delivered'        ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled"        {{ $order->status == 'cancelled'        ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-jabulani btn-sm w-100">
                            <i class="fas fa-save me-1"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>

            {{-- Store / Branch --}}
            <div class="card mb-3">
                <div class="card-header">
                    <div class="fw-bold" style="font-size: 0.83rem;">Store (Branch) Details</div>
                </div>
                <div class="card-body order-info-section">
                    <div class="d-flex flex-column gap-2">
                        <div>
                            <div class="form-label">Branch</div>
                            <div class="info-value">{{ $order->store->name ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <div class="form-label">Contact</div>
                            <div class="info-value">{{ $order->store->phone ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <div class="form-label">Address</div>
                            <div class="info-value">{{ $order->store->address ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- EFT / Payment Verification --}}
    @if($order->payment_method == 'eft' || $order->payment_screenshot)
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-file-invoice-dollar" style="color: var(--orange-400);"></i>
                    <span class="fw-bold" style="font-size: 0.83rem;">Payment Verification Documentation</span>
                </div>
                @if($order->status == 'awaiting_payment')
                    <form action="{{ route('admin.orders.confirm-payment', $order) }}" method="POST"
                          onsubmit="return confirm('Verify that funds have cleared in our account?')">
                        @csrf
                        <button type="submit" class="btn btn-jabulani btn-sm">
                            <i class="fas fa-check-circle me-1"></i> Confirm Payment
                        </button>
                    </form>
                @endif
            </div>
            <div class="card-body">
                <div class="row g-4 align-items-start">
                    <div class="col-md-5">
                        <div class="mb-3">
                            <div class="form-label">Settlement Method</div>
                            <div class="fw-bold" style="font-size: 0.9rem;">Bank EFT Transfer</div>
                        </div>
                        <div>
                            <div class="form-label">Audit Status</div>
                            @if($order->payment_confirmed_at)
                                <div class="d-flex align-items-center gap-2 p-3 rounded-2"
                                     style="background: rgba(25,135,84,0.1); border: 1px solid rgba(25,135,84,0.2);">
                                    <i class="fas fa-check-double" style="color: var(--success-color);"></i>
                                    <div>
                                        <div class="fw-bold" style="font-size: 0.78rem; color: var(--success-color);">Verified & Cleared</div>
                                        <div style="font-size: 0.72rem; color: var(--text-muted);">Settled on {{ $order->payment_confirmed_at->format('d M Y H:i') }}</div>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex align-items-center gap-2 p-3 rounded-2"
                                     style="background: rgba(255,193,7,0.1); border: 1px solid rgba(255,193,7,0.2);">
                                    <i class="fas fa-shield-halved" style="color: var(--warning-color);"></i>
                                    <div>
                                        <div class="fw-bold" style="font-size: 0.78rem; color: var(--warning-color);">Awaiting Verification</div>
                                        <div style="font-size: 0.72rem; color: var(--text-muted);">Manual audit of institution account required.</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-7">
                        @if($order->payment_screenshot)
                            <div class="form-label">Customer Uploaded Proof of Payment</div>
                            @php $ext = pathinfo($order->payment_screenshot, PATHINFO_EXTENSION); @endphp
                            @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']))
                                <a href="{{ asset($order->payment_screenshot) }}" target="_blank"
                                   style="display: block; border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--border-default);">
                                    <img src="{{ asset($order->payment_screenshot) }}" class="img-fluid"
                                         style="max-height: 380px; width: 100%; object-fit: contain; background: var(--carbon-800);" alt="Proof of Payment">
                                </a>
                                <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 6px; text-align: center;">
                                    Click image to view full resolution
                                </div>
                            @else
                                <div class="p-4 text-center rounded-2" style="background: var(--carbon-800); border: 1px dashed var(--border-default);">
                                    <i class="fas fa-file-pdf fa-3x mb-2" style="color: var(--error-color); opacity: 0.6;"></i>
                                    <div class="fw-bold mb-2" style="font-size: 0.83rem;">Document: {{ strtoupper($ext) }}</div>
                                    <a href="{{ asset($order->payment_screenshot) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-download me-1"></i> Download Documentation
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="p-4 text-center rounded-2" style="background: var(--carbon-800); border: 1px dashed var(--border-default);">
                                <i class="fas fa-file-circle-exclamation fa-2x d-block mb-2 opacity-20"></i>
                                <div style="font-size: 0.78rem; color: var(--text-muted);">
                                    No Proof of Payment uploaded by customer.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection