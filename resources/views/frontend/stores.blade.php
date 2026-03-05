@extends('layouts.frontend')

@section('title', 'Our Hardware Stores - Jabulani Group of Companies')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">Our <span>Hardware Stores</span></h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Stores</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    @include('frontend.partials.ticker')

    <!-- Page Project Start -->
    <div class="page-project">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <!-- Choose Our Product Nav start -->
                    <div class="our-Project-nav2 wow fadeInUp" data-wow-delay="0.25s">
                        <ul>
                            <li><a href="#" class="active-btn2" data-filter=".all">All</a></li>
                            @php
                                $locations = $stores->pluck('province')->unique();
                            @endphp
                            @foreach($locations as $location)
                                <li><a href="#" data-filter=".{{ Str::slug($location) }}">{{ $location }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- Choose Our Product Nav End -->
                </div>

                <div class="col-lg-12">
                    <!-- Project Item Boxes start -->
                    <div class="row project-item-boxes2 align-items-center">
                        @foreach($stores as $store)
                            <div class="col-lg-3 col-md-6 project-item-box2 all {{ Str::slug($store->province) }}">
                                <!-- Project Item Start -->
                                <div class="project-item wow fadeInUp">
                                    <div class="project-image">
                                        <figure class="image-anime">
                                            <img src="{{ $store->image ? (Str::contains($store->image, 'images/') ? asset($store->image) : asset('' . $store->image)) : asset('images/logo_yellow2.png') }}"
                                                alt="{{ $store->name }}">
                                        </figure>
                                        <div class="project-btn">
                                            <a href="{{ route('store.detail', $store->id) }}"><img
                                                    src="{{ asset('images/arrow-white.svg') }}" alt=""></a>
                                        </div>
                                    </div>
                                    <div class="project-content">
                                        <h3>{{ $store->name }}</h3>
                                        <p class="small text-muted">{{ $store->address }}</p>
                                    </div>
                                </div>
                                <!-- Project Item End -->
                            </div>
                        @endforeach
                    </div>
                    <!-- Project Item Boxes End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Project End -->
@endsection