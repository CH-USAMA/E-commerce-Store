@extends('layouts.frontend')

@section('title', 'Checkout Authentication - Jabulani Group')

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
                                <li class="breadcrumb-item"><a href="{{ route('cart') }}">cart</a></li>
                                <li class="breadcrumb-item active" aria-current="page">checkout auth</li>
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
            <div class="row align-items-stretch justify-content-center g-4">
                <div class="col-lg-5 col-md-6 d-flex">
                    <div class="card bg-dark border-secondary w-100 p-4">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <h2 class="h3 text-warning mb-4">New to Jabulani?</h2>
                            <p class="text-white mb-4">Create an account to track your orders, save your details for faster
                                checkout, and view your order history.</p>
                            <a href="{{ route('register') }}" class="btn btn-highlighted w-100 py-3 mb-3">Register
                                Account</a>
                            <div class="text-white mb-3">- OR -</div>
                            <a href="{{ route('checkout.guest') }}" class="btn btn-outline-warning w-100 py-3">Continue as
                                Guest</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 col-md-6 d-flex">
                    <div class="card bg-dark border-secondary w-100 p-4">
                        <div class="card-body">
                            <h2 class="h3 text-warning mb-4 text-center">Returning Customer</h2>
                            <form action="{{ url('/login') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label text-white">Email Address</label>
                                    <input type="email" name="email"
                                        class="form-control bg-dark text-white border-secondary @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" required>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="form-label text-white">Password</label>
                                    <input type="password" name="password"
                                        class="form-control bg-dark text-white border-secondary" required>
                                </div>
                                <div class="mb-4 form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="form-check-label text-white" for="remember">Remember Me</label>
                                </div>
                                <button type="submit" class="btn btn-highlighted w-100 py-3">Login to Checkout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection