@extends('layouts.frontend')

@section('title', $product->name . ' - Jabulani Group of Companies')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">{{ $product->name }}</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('products') }}">products</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    @include('frontend.partials.ticker')

    <div class="page-project-single py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="product-image wow fadeInUp">
                        <img src="{{ $product->image ? (Str::contains($product->image, 'images/') ? asset($product->image) : asset('storage/' . $product->image)) : asset('images/placeholder.webp') }}"
                            alt="{{ $product->name }}" class="img-fluid rounded shadow-sm">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="product-details wow fadeInUp" data-wow-delay="0.2s">
                        <h2 class="mb-3">{{ $product->name }}</h2>
                        <p class="category text-muted mb-4">Category: {{ $product->category->name }}</p>
                        <h3 class="price text-primary mb-4">R {{ number_format($product->price, 2) }}</h3>

                        <div class="description mb-4">
                            {!! $product->description !!}
                        </div>

                        <div class="product-actions mt-5">
                            <button class="btn-highlighted mb-3 w-100 add-to-cart" data-id="{{ $product->id }}">Add to
                                Cart</button>
                            <a href="https://wa.me/27660684585?text=Hi, I am interested in {{ $product->name }}"
                                class="btn btn-outline-success w-100" target="_blank">
                                <i class="fab fa-whatsapp"></i> Inquire on WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="service-footer wow fadeInUp py-4 bg-light">
        <div class="container text-center">
            <p>Get a quote for any product — listed here or not — <a href="{{ route('contact') }}"
                    class="text-primary font-weight-bold">get free quote</a></p>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
        <div id="cartToast" class="toast align-items-center text-bg-success border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body" id="cartToastMsg">Product added to cart!</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    document.querySelector('.add-to-cart').addEventListener('click', function () {
        let productId = this.dataset.id;
        let btn = this;
        btn.disabled = true;
        btn.textContent = 'Adding...';

        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ product_id: productId, quantity: 1 })
        })
        .then(r => r.json())
        .then(data => {
            btn.disabled = false;
            btn.textContent = 'Add to Cart';
            
            // Update cart badge
            let badge = document.getElementById('cart-badge');
            if (badge) badge.textContent = data.cart_count;

            // Show toast
            document.getElementById('cartToastMsg').textContent = data.message;
            let toast = new bootstrap.Toast(document.getElementById('cartToast'));
            toast.show();
        })
        .catch(() => {
            btn.disabled = false;
            btn.textContent = 'Add to Cart';
        });
    });
</script>
@endpush