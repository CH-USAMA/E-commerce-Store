@extends('layouts.frontend')

@section('title', 'Register - Jabulani Group')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">Register Account</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">register</li>
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
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card bg-dark border-secondary p-4">
                        <div class="card-body">
                            <h2 class="h3 text-warning mb-4 text-center">Create Your Account</h2>
                            <form action="{{ route('register') }}" method="POST">
                                @csrf

                                <div class="section-title mb-4 mt-4">
                                    <h3 class="h5 text-warning">Personal Information</h3>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-white">Full Name</label>
                                        <input type="text" name="name"
                                            class="form-control bg-dark text-white border-secondary @error('name') is-invalid @enderror"
                                            value="{{ old('name') }}" required>
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-white">Email Address</label>
                                        <input type="email" name="email"
                                            class="form-control bg-dark text-white border-secondary @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}" required>
                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-white">Password</label>
                                        <input type="password" name="password"
                                            class="form-control bg-dark text-white border-secondary @error('password') is-invalid @enderror"
                                            required>
                                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-white">Confirm Password</label>
                                        <input type="password" name="password_confirmation"
                                            class="form-control bg-dark text-white border-secondary" required>
                                    </div>
                                </div>

                                <div class="section-title mb-4 mt-4">
                                    <h3 class="h5 text-warning">Shipping Address</h3>
                                </div>
                                <div class="row g-3">
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-white">Address Line 1</label>
                                        <input type="text" name="shipping_address_line_1"
                                            class="form-control bg-dark text-white border-secondary @error('shipping_address_line_1') is-invalid @enderror"
                                            value="{{ old('shipping_address_line_1') }}" required>
                                        @error('shipping_address_line_1') <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-white">Address Line 2 (Optional)</label>
                                        <input type="text" name="shipping_address_line_2"
                                            class="form-control bg-dark text-white border-secondary @error('shipping_address_line_2') is-invalid @enderror"
                                            value="{{ old('shipping_address_line_2') }}">
                                        @error('shipping_address_line_2') <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label text-white">City</label>
                                        <input type="text" name="shipping_city"
                                            class="form-control bg-dark text-white border-secondary @error('shipping_city') is-invalid @enderror"
                                            value="{{ old('shipping_city') }}" required>
                                        @error('shipping_city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label text-white">Province</label>
                                        <input type="text" name="shipping_province"
                                            class="form-control bg-dark text-white border-secondary @error('shipping_province') is-invalid @enderror"
                                            value="{{ old('shipping_province') }}" required>
                                        @error('shipping_province') <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label text-white">Postal Code</label>
                                        <input type="text" name="shipping_postal_code"
                                            class="form-control bg-dark text-white border-secondary @error('shipping_postal_code') is-invalid @enderror"
                                            value="{{ old('shipping_postal_code') }}" required>
                                        @error('shipping_postal_code') <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="section-title mb-2 mt-4 d-flex align-items-center justify-content-between">
                                    <h3 class="h5 text-warning mb-0">Billing Address</h3>
                                    <div class="form-check">
                                        <input type="hidden" name="billing_same_as_shipping" value="0">
                                        <input type="checkbox" class="form-check-input" id="billing_same_as_shipping"
                                            name="billing_same_as_shipping" value="1" {{ old('billing_same_as_shipping', true) ? 'checked' : '' }}>
                                        <label class="form-check-label text-white" for="billing_same_as_shipping">Same as
                                            Shipping</label>
                                    </div>
                                </div>
                                <div id="billing-address-section" class="row g-3"
                                    style="display: {{ old('billing_same_as_shipping', true) ? 'none' : 'flex' }};">
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-white">Address Line 1</label>
                                        <input type="text" name="billing_address_line_1"
                                            class="form-control bg-dark text-white border-secondary @error('billing_address_line_1') is-invalid @enderror"
                                            value="{{ old('billing_address_line_1') }}">
                                        @error('billing_address_line_1') <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-white">Address Line 2 (Optional)</label>
                                        <input type="text" name="billing_address_line_2"
                                            class="form-control bg-dark text-white border-secondary @error('billing_address_line_2') is-invalid @enderror"
                                            value="{{ old('billing_address_line_2') }}">
                                        @error('billing_address_line_2') <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label text-white">City</label>
                                        <input type="text" name="billing_city"
                                            class="form-control bg-dark text-white border-secondary @error('billing_city') is-invalid @enderror"
                                            value="{{ old('billing_city') }}">
                                        @error('billing_city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label text-white">Province</label>
                                        <input type="text" name="billing_province"
                                            class="form-control bg-dark text-white border-secondary @error('billing_province') is-invalid @enderror"
                                            value="{{ old('billing_province') }}">
                                        @error('billing_province') <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label text-white">Postal Code</label>
                                        <input type="text" name="billing_postal_code"
                                            class="form-control bg-dark text-white border-secondary @error('billing_postal_code') is-invalid @enderror"
                                            value="{{ old('billing_postal_code') }}">
                                        @error('billing_postal_code') <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-highlighted w-100 py-3 mt-4">Register Account</button>
                                <p class="text-center mt-3 text-white">Already have an account? <a
                                        href="{{ route('login') }}" class="text-warning">Login here</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkbox = document.getElementById('billing_same_as_shipping');
            const billingSection = document.getElementById('billing-address-section');

            checkbox.addEventListener('change', function () {
                if (this.checked) {
                    billingSection.style.display = 'none';
                } else {
                    billingSection.style.display = 'flex';
                }
            });
        });
    </script>
@endpush