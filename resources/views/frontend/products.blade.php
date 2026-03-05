@extends('layouts.frontend')

@section('title', 'Our Products - Jabulani Group of Companies')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">Our <span>Products</span></h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">products</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Scrolling Ticker -->
    @include('frontend.partials.ticker')

    <div class="page-project py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="our-Project-nav wow fadeInUp" data-wow-delay="0.25s">
                        <ul>
                            <li><a href="{{ route('products') }}" class="{{ !request('category') ? 'active-btn' : '' }}">All
                                    Products</a></li>
                            @foreach($categories as $category)
                                <li><a href="{{ route('products', ['category' => $category->slug]) }}"
                                        class="{{ request('category') == $category->slug ? 'active-btn' : '' }}">{{ $category->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="row project-item-boxes align-items-center">
                        @foreach($products as $product)
                            <div class="col-lg-4 col-md-6 project-item-box">
                                <div class="project-item wow fadeInUp">
                                    <div class="project-image">
                                        <figure class="image-anime">
                                            <img src="{{ $product->image ? (Str::contains($product->image, 'images/') ? asset($product->image) : asset('' . $product->image)) : asset('images/placeholder.webp') }}"
                                                alt="{{ $product->name }}" loading="lazy">
                                        </figure>
                                        <div class="project-tag">
                                            <a href="#">{{ $product->category->name }}</a>
                                        </div>
                                        <div class="project-btn">
                                            <a href="{{ route('product.detail', $product->slug) }}"><img
                                                    src="{{ asset('images/arrow-white.svg') }}" alt="" loading="lazy"></a>
                                        </div>
                                    </div>
                                    <div class="project-content">
                                        <h3>{{ $product->name }}</h3>
                                        <p class="price">R {{ number_format($product->price, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection