@extends('layouts.frontend')

@section('title', 'Testimonials - Jabulani Group of Companies')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">Testimonial</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">pages</li>
                                <li class="breadcrumb-item active" aria-current="page">testimonial</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    @include('frontend.partials.ticker')

    <!-- Page Testimonial Start -->
    <div class="page-testimonial">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="testimonial-box-list">
                        <!-- Static Testimonials as per HTML -->
                        <div class="testimonial-box-item wow fadeInUp">
                            <div class="client-author-image">
                                <figure class="image-anime">
                                    <img src="{{ asset('images/customer-3.JPG') }}"
                                        alt="">
                                </figure>
                            </div>
                            <div class="client-testimonial-content">
                                <div class="client-testimonial-rating">
                                    <ul>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                    </ul>
                                </div>
                                <div class="client-testimonial-info">
                                    <p><span style="color: #FFE507;">Jabulani Group of Companies</span> has been our go-to
                                        supplier for SABS-approved blocks and aluminium products. Their quality is
                                        unmatched, and their reliable deliveries keep our projects on schedule. Thanks to
                                        their competitive prices and exceptional service, we’ve saved costs without
                                        compromising on durability!.</p>
                                </div>
                                <div class="client-author-content">
                                    <div class="client-author-title">
                                        <h3>Saleem Nazar</h3>
                                        <p>CEO Cingimiso Construction</p>
                                    </div>
                                    <div class="client-author-logo">
                                        <img src="{{ asset('images/HMART.JPG') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="testimonial-box-item wow fadeInUp" data-wow-delay="0.4s">
                            <div class="client-author-image">
                                <figure class="image-anime">
                                    <img src="{{ asset('images/customer-1.png') }}" alt="">
                                </figure>
                            </div>
                            <div class="client-testimonial-content">
                                <div class="client-testimonial-rating">
                                    <ul>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                    </ul>
                                </div>
                                <div class="client-testimonial-info">
                                    <p><span style="color: #FFE507;">Jabulani Group of Companies</span> is my first choice
                                        for all my building materials. Their products are high quality, their prices are
                                        fair, and their service is always reliable.</p>
                                </div>
                                <div class="client-author-content">
                                    <div class="client-author-title">
                                        <h3>Aya Bulila</h3>
                                        <p>Cashier Shoprite</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection