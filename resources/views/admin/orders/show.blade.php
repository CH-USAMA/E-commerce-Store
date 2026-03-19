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
                        @if($order->status == 'awaiting_payment')
                            <span class="badge bg-warning text-dark">Awaiting Payment</span>
                        @else
                            <span class="badge bg-primary">{{ ucfirst($order->status) }}</span>
                        @endif
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
                                <option value="awaiting_payment" {{ $order->status == 'awaiting_payment' ? 'selected' : '' }}>Awaiting Payment</option>
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

            <div class="card mb-4">
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

    @if($order->payment_method == 'eft' || $order->payment_screenshot)
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4 border-0 shadow-sm overflow-hidden" style="border-radius: 1rem;">
                    <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white py-3">
                        <h5 class="mb-0 italic font-black uppercase tracking-tight" style="font-size: 1rem;">
                            <i class="fas fa-file-invoice-dollar text-warning me-2"></i> Official Payment Verification Documentation
                        </h5>
                        @if($order->status == 'awaiting_payment')
                            <form action="{{ route('admin.orders.confirm-payment', $order->id) }}" method="POST" onsubmit="return confirm('Verify that the funds have cleared in our institution account?')">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm font-black uppercase tracking-widest px-4 py-2" style="font-size: 0.7rem;">
                                    <i class="fas fa-check-circle me-1"></i> Confirm Transaction Settlement
                                </button>
                            </form>
                        @endif
                    </div>
                    <div class="card-body bg-white p-5">
                        <div class="row align-items-center">
                            <div class="col-md-6 border-end">
                                <div class="mb-4">
                                    <label class="text-uppercase text-muted fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 0.1em;">Settlement Method</label>
                                    <h4 class="fw-black italic text-dark uppercase mb-0">Bank EFT Transfer</h4>
                                </div>
                                <div class="mb-0">
                                    <label class="text-uppercase text-muted fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 0.1em;">Audit Status</label>
                                    <div class="d-flex align-items-center">
                                        @if($order->payment_confirmed_at)
                                            <div class="bg-success-soft text-success px-4 py-3 rounded-3 w-100 d-flex align-items-center">
                                                <i class="fas fa-check-double fa-2x me-3 opacity-50"></i>
                                                <div>
                                                    <p class="mb-0 font-black uppercase tracking-widest" style="font-size: 0.8rem;">Verified & Secured</p>
                                                    <p class="mb-0 text-sm opacity-75">Settled on {{ $order->payment_confirmed_at->format('d M Y H:i') }}</p>
                                                </div>
                                            </div>
                                        @else
                                            <div class="bg-warning-soft text-warning px-4 py-3 rounded-3 w-100 d-flex align-items-center">
                                                <i class="fas fa-shield-halved fa-2x me-3 opacity-50"></i>
                                                <div>
                                                    <p class="mb-0 font-black uppercase tracking-widest" style="font-size: 0.8rem;">Awaiting Verification</p>
                                                    <p class="mb-0 text-sm opacity-75">Manual audit of institution account required.</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 px-lg-5">
                                @if($order->payment_screenshot)
                                    <div class="text-center">
                                        <p class="mb-3 text-uppercase font-black text-muted text-start" style="font-size: 0.7rem; letter-spacing: 0.1em;">Customer Uploaded Proof:</p>
                                        @php $ext = pathinfo($order->payment_screenshot, PATHINFO_EXTENSION); @endphp
                                        @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']))
                                            <a href="{{ asset($order->payment_screenshot) }}" target="_blank" class="d-block shadow-lg rounded-4 overflow-hidden position-relative group">
                                                <img src="{{ asset($order->payment_screenshot) }}" class="img-fluid" style="max-height: 400px; width: 100%; object-fit: contain; background: #f8f9fa;" alt="POP">
                                                <div class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-75 text-white py-2 font-black uppercase text-[10px] opacity-0 group-hover-opacity-100 transition">Click to view full resolution</div>
                                            </a>
                                        @else
                                            <div class="p-5 border rounded-4 bg-light text-center">
                                                <i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
                                                <h5 class="font-black uppercase italic">Document: {{ strtoupper($ext) }}</h5>
                                                <a href="{{ asset($order->payment_screenshot) }}" target="_blank" class="btn btn-dark mt-3 px-5 font-black uppercase tracking-widest" style="font-size: 0.75rem;">Download Documentation</a>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="alert alert-light border border-dashed py-5 text-center">
                                        <i class="fas fa-file-circle-exclamation fa-3x mb-3 text-muted opacity-25"></i>
                                        <p class="mb-0 font-black uppercase tracking-widest text-muted" style="font-size: 0.8rem;">No Proof of Payment documentation uploaded by customer.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    </div>
@endsection