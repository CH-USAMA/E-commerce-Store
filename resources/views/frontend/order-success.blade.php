@extends('layouts.frontend')

@section('title', 'Order Success - Jabulani Group')

@section('content')
    <div class="py-5">
        <div class="container text-center py-5">
            <div class="mb-4">
                <i class="fas fa-check-circle fa-5x text-success"></i>
            </div>
            <h1 class="text-anime-style-2 mb-3">Order Placed Successfully!</h1>
            <p class="h4 text-warning mb-4">Order Number: #{{ $orderNumber }}</p>
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="bg-dark p-4 rounded border border-warning shadow-lg">
                        <p class="text-white mb-4">Thank you for shopping with Jabulani Group. Your order has been received
                            and will be processed soon. Our branch manager will contact you shortly.</p>
                        <div class="d-grid gap-3 d-md-flex justify-content-md-center">
                            <a href="{{ route('home') }}" class="btn btn-default">Return to Home</a>
                            <a href="{{ route('products') }}" class="btn btn-highlighted">Continue Shopping</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection