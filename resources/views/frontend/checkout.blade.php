@extends('layouts.frontend')

@section('title', 'Checkout - Jabulani Group')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">Checkout</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">checkout</li>
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
            <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                @csrf
                <input type="hidden" name="lat" id="checkout-lat">
                <input type="hidden" name="lng" id="checkout-lng">

                <div class="row">
                    <div class="col-lg-8">
                        <div class="section-title mb-4">
                            <h2 class="h3 text-warning">Shipping & Personal Details</h2>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-white">Full Name</label>
                                <input type="text" name="customer_name"
                                    class="form-control bg-dark text-white border-secondary @error('customer_name') is-invalid @enderror"
                                    value="{{ old('customer_name') }}" required>
                                @error('customer_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white">Email Address</label>
                                <input type="email" name="customer_email"
                                    class="form-control bg-dark text-white border-secondary @error('customer_email') is-invalid @enderror"
                                    value="{{ old('customer_email') }}" required>
                                @error('customer_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white">Phone Number</label>
                                <input type="text" name="customer_phone"
                                    class="form-control bg-dark text-white border-secondary @error('customer_phone') is-invalid @enderror"
                                    value="{{ old('customer_phone') }}" required>
                                @error('customer_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label text-white">Shipping Address</label>
                                <textarea name="customer_address"
                                    class="form-control bg-dark text-white border-secondary @error('customer_address') is-invalid @enderror"
                                    rows="3" required>{{ old('customer_address') }}</textarea>
                                @error('customer_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white">City</label>
                                <input type="text" name="customer_city"
                                    class="form-control bg-dark text-white border-secondary @error('customer_city') is-invalid @enderror"
                                    value="{{ old('customer_city') }}" required>
                                @error('customer_city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white">Postal Code</label>
                                <input type="text" name="customer_postal_code"
                                    class="form-control bg-dark text-white border-secondary @error('customer_postal_code') is-invalid @enderror"
                                    value="{{ old('customer_postal_code') }}" required>
                                @error('customer_postal_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="section-title mt-5 mb-4">
                            <h2 class="h3 text-warning">Payment Method</h2>
                        </div>
                        <div class="payment-methods bg-dark p-4 rounded border border-secondary">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="pay-cod" value="cod"
                                    checked>
                                <label class="form-check-label text-white" for="pay-cod">
                                    Cash on Delivery (COD)
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="pay-eft" value="eft">
                                <label class="form-check-label text-white" for="pay-eft">
                                    Bank Transfer (EFT)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="pay-fast"
                                    value="payfast">
                                <label class="form-check-label text-white" for="pay-fast">
                                    Online Payment (PayFast)
                                </label>
                            </div>
                        </div>

                        <div class="section-title mt-5 mb-4">
                            <h2 class="h3 text-warning">Order Type & Branch</h2>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label text-white">Select Service Type</label>
                                <select name="order_type" id="order_type"
                                    class="form-select bg-dark text-white border-secondary">
                                    <option value="delivery" selected>Home Delivery</option>
                                    <option value="pickup">Store Pickup</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white">Nearest Store / Preferred Branch</label>
                                <select name="store_id" id="store_id"
                                    class="form-select bg-dark text-white border-secondary">
                                    @foreach($stores as $store)
                                        <option value="{{ $store->id }}" data-lat="{{ $store->lat }}"
                                            data-lng="{{ $store->lng }}" {{ $loop->first ? 'selected' : '' }}>
                                            {{ $store->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small id="store-distance" class="text-info d-block mt-1"></small>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="form-label text-white">Order Notes (Optional)</label>
                            <textarea name="notes" class="form-control bg-dark text-white border-secondary"
                                rows="2"></textarea>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card bg-dark border-secondary">
                            <div class="card-body">
                                <h4 class="card-title text-warning mb-4">Order Summary</h4>
                                <ul class="list-group list-group-flush mb-4">
                                    @foreach($products as $product)
                                        <li
                                            class="list-group-item bg-transparent text-secondary d-flex justify-content-between align-items-center border-secondary px-0">
                                            <div>
                                                <span class="text-white">{{ $product->name }}</span>
                                                <br><small>Qty: {{ $product->cart_quantity }}</small>
                                            </div>
                                            <span class="text-white">R {{ number_format($product->cart_subtotal, 2) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="d-flex justify-content-between h4 text-warning mb-4">
                                    <span>Total:</span>
                                    <span>R {{ number_format($total, 2) }}</span>
                                </div>
                                <button type="submit" class="btn btn-highlighted w-100 py-3">Place Order Now</button>
                                <p class="text-center mt-3 small text-muted">By placing your order, you agree to our Terms &
                                    Conditions.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const storeSelect = document.getElementById('store_id');
            const distanceText = document.getElementById('store-distance');
            const orderTypeSelect = document.getElementById('order_type');

            function calculateDistance(lat1, lon1, lat2, lon2) {
                const R = 6371; // km
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLon = (lon2 - lon1) * Math.PI / 180;
                const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                return R * c;
            }

            function updateDistances(userLat, userLng) {
                let nearestStoreId = null;
                let minDistance = Infinity;

                Array.from(storeSelect.options).forEach(option => {
                    const storeLat = parseFloat(option.getAttribute('data-lat'));
                    const storeLng = parseFloat(option.getAttribute('data-lng'));

                    if (storeLat && storeLng) {
                        const dist = calculateDistance(userLat, userLng, storeLat, storeLng);
                        option.text = `${option.text.split('(')[0].trim()} (${dist.toFixed(1)} km away)`;
                        
                        if (dist < minDistance) {
                            minDistance = dist;
                            nearestStoreId = option.value;
                        }
                    }
                });

                if (nearestStoreId) {
                    storeSelect.value = nearestStoreId;
                    distanceText.textContent = `Auto-selected nearest branch (~${minDistance.toFixed(1)} km)`;
                }
            }

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(position => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    document.getElementById('checkout-lat').value = lat;
                    document.getElementById('checkout-lng').value = lng;
                    updateDistances(lat, lng);
                });
            }

            orderTypeSelect.addEventListener('change', function() {
                if (this.value === 'pickup') {
                    distanceText.textContent = 'Please select which branch you will collect from.';
                } else {
                    // Re-calculate if delivery
                    const lat = document.getElementById('checkout-lat').value;
                    const lng = document.getElementById('checkout-lng').value;
                    if (lat && lng) updateDistances(lat, lng);
                }
            });
        });
    </script>
@endpush