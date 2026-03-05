@extends('layouts.frontend')

@section('title', 'Shopping Cart - Jabulani Group')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">Shopping Cart</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">cart</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    @include('frontend.partials.ticker')

    <div class="py-5">
        <div class="container">
            <!-- Nearest Store Information -->
            <div id="nearest-store-box" class="nearest-store-box mb-4">
                <div class="row align-items-center">
                    <div class="col-md-1 col-3">
                        <img id="nearest-store-img" src="" alt="" class="img-fluid rounded shadow-sm border border-warning">
                    </div>
                    <div class="col-md-8 col-9">
                        <h5 class="mb-1 text-primary">Nearest Store Identified!</h5>
                        <p class="mb-0 small">Based on your location, your nearest store for pickup/delivery is: <strong
                                id="nearest-store-name" class="text-warning"></strong></p>
                        <small id="nearest-store-address" class="text-muted"></small>
                    </div>
                    <div class="col-md-3 mt-3 mt-md-0 text-md-end">
                        <a id="nearest-store-link" href="#" class="btn btn-sm btn-highlighted">Go to Store</a>
                    </div>
                </div>
            </div>

            @if($products->count() > 0)
                <div class="table-responsive">
                    <table class="table cart-table-premium align-middle">
                        <thead>
                            <tr>
                                <th style="width: 100px;">Image</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th style="width: 120px;">Quantity</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr id="cart-row-{{ $product->id }}">
                                    <td>
                                        <img src="{{ $product->image ? (Str::contains($product->image, 'images/') ? asset($product->image) : asset('' . $product->image)) : asset('images/placeholder.webp') }}"
                                            alt="{{ $product->name }}" class="rounded shadow-sm"
                                            style="width: 70px; height: 70px; object-fit: cover; border: 1px solid var(--divider-color);">
                                    </td>
                                    <td>
                                        <a href="{{ route('product.detail', $product->slug) }}" class="fw-bold text-decoration-none"
                                            style="color: var(--primary-color);">{{ $product->name }}</a>
                                        <br><small class="text-muted">{{ $product->category->name ?? '' }}</small>
                                    </td>
                                    <td>R {{ number_format($product->price, 2) }}</td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm cart-qty"
                                            data-id="{{ $product->id }}" data-price="{{ $product->price }}"
                                            value="{{ $product->cart_quantity }}" min="1" max="99">
                                    </td>
                                    <td class="subtotal-{{ $product->id }} fw-bold text-warning">R
                                        {{ number_format($product->cart_subtotal, 2) }}
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-danger cart-remove border-0"
                                            data-id="{{ $product->id }}"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background: transparent;">
                                <td colspan="4" class="text-end fw-bold h4">Total:</td>
                                <td class="fw-bold cart-total h4 text-warning" colspan="2">R {{ number_format($total, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <div class="d-flex gap-3">
                        <a href="{{ route('products') }}" class="btn-default">Continue Shopping</a>
                        <a href="{{ route('checkout') }}" class="btn btn-highlighted px-4" style="border-radius: 100px;">Check
                            Out</a>
                    </div>
                    <a href="https://wa.me/27660684585?text=Hi, I would like to place an order for the items in my cart."
                        class="btn-highlighted" target="_blank">
                        <i class="fab fa-whatsapp"></i> Order via WhatsApp
                    </a>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-4x mb-4 text-muted opacity-25"></i>
                    <h3>Your cart is empty</h3>
                    <p class="text-muted mb-4">Browse our products and add items to your cart.</p>
                    <a href="{{ route('products') }}" class="btn-default">Browse Products</a>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('js')
    <script>
        // Geolocation for nearest store
        document.addEventListener('DOMContentLoaded', function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(position => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    fetch('{{ route("cart.nearest-store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ lat, lng })
                    })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById('nearest-store-name').textContent = data.store.name;
                                document.getElementById('nearest-store-address').textContent = data.store.address;
                                document.getElementById('nearest-store-img').src = data.store.image;
                                document.getElementById('nearest-store-link').href = '/store/' + data.store.slug;
                                document.getElementById('nearest-store-box').style.display = 'block';
                            }
                        });
                }, error => {
                    console.warn("Geolocation failed or denied.");
                });
            }
        });

        // Update quantity
        document.querySelectorAll('.cart-qty').forEach(input => {
            input.addEventListener('change', function () {
                let id = this.dataset.id;
                let qty = parseInt(this.value);
                let price = parseFloat(this.dataset.price);

                fetch('{{ route("cart.update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ product_id: id, quantity: qty })
                })
                    .then(r => r.json())
                    .then(data => {
                        document.querySelector('.subtotal-' + id).textContent = 'R ' + (price * qty).toFixed(2);
                        updateCartBadge(data.cart_count);
                        location.reload(); // Refresh totals
                    });
            });
        });

        // Remove item
        document.querySelectorAll('.cart-remove').forEach(btn => {
            btn.addEventListener('click', function () {
                let id = this.dataset.id;
                fetch('{{ route("cart.remove") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ product_id: id })
                })
                    .then(r => r.json())
                    .then(data => {
                        document.getElementById('cart-row-' + id).remove();
                        updateCartBadge(data.cart_count);
                        location.reload();
                    });
            });
        });

        function updateCartBadge(count) {
            let badge = document.getElementById('cart-badge');
            if (badge) badge.textContent = count;
        }
    </script>
@endpush