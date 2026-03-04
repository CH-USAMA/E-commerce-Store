@extends('layouts.frontend')

@section('title', 'Our Services - Jabulani Group of Companies')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">Our <span>services</span></h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">services</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Scrolling Ticker Section Start -->
    <div class="our-scrolling-ticker subpages-scrolling-ticker">
        <div class="scrolling-ticker-box">
            <div class="scrolling-content">
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Blocks SABS</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Aluminium & Glass</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Doors & Windows</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Tiles</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Hardware Tools</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Roofing Material</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Boards</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Plumbing Material</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Electrical Appliances</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Wall & Ceiling</span>
            </div>
            <div class="scrolling-content">
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Blocks SABS</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Aluminium & Glass</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Doors & Windows</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Tiles</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Hardware Tools</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Roofing Material</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Boards</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Plumbing Material</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Electrical Appliances</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Wall & Ceiling</span>
            </div>
        </div>
    </div>
    <!-- Scrolling Ticker Section End -->

    <!-- Page Services Start -->
    <div class="page-services py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="service-item wow fadeInUp">
                        <div class="service-item-header">
                            <div class="service-arrow">
                                <a href="{{ route('contact') }}"><img src="{{ asset('images/arrow-accent.svg') }}"
                                        alt=""></a>
                            </div>
                        </div>
                        <div class="service-item-body">
                            <h3>Deliveries</h3>
                            <p>Make transportation hassle-free with our fast and reliable delivery service...</p>
                        </div>
                    </div>
                </div>
                <!-- Repeat for other services -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="service-item wow fadeInUp" data-wow-delay="0.2s">
                        <div class="service-item-header">
                            <div class="service-arrow">
                                <a href="{{ route('contact') }}"><img src="{{ asset('images/arrow-accent.svg') }}"
                                        alt=""></a>
                            </div>
                        </div>
                        <div class="service-item-body">
                            <h3>Custom Aluminium Products</h3>
                            <p>We offer custom-made aluminium doors and windows tailored to your exact measurements...</p>
                        </div>
                    </div>
                </div>
                <!-- Add more services as needed -->
            </div>
        </div>
    </div>

    <!-- Testimonials Section could also be added here if needed -->
@endsection