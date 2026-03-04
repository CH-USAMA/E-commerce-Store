@extends('layouts.frontend')

@section('title', $store->name . ' - Jabulani Group')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">Jabulani <span>Store</span></h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('stores') }}">stores</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $store->name }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    @include('frontend.partials.ticker')

    <!-- Page Store Detail Start -->
    <div class="page-team-single">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 order-lg-1 order-2">
                    <div class="team-single-content">
                        @php
                            $contactParts = explode(' | ', $store->contact_details ?? '');
                            $storePhone = trim($contactParts[1] ?? '');
                            $storeEmail = trim($contactParts[0] ?? '');
                        @endphp
                        <div class="team-info-box">
                            <div class="team-info-header">
                                <div class="team-info-title">
                                    <p class="wow fadeInUp" style="display: flex; gap: 16px; align-items: center;">
                                        <img src="{{ asset('images/maintenance.png') }}" height="50px" width="50px"
                                            alt="icon">
                                        Shop Name
                                    </p>
                                    <h2 class="wow fadeInUp" data-wow-delay="0.25s">{{ $store->name }}</h2>
                                </div>
                            </div>

                            <div class="team-contect-list">
                                <div class="team-contact-box wow fadeInUp">
                                    <div class="icon-box">
                                        <img src="{{ asset('images/icon-phone.svg') }}" alt="">
                                    </div>
                                    <div class="team-contact-content">
                                        <h3>call</h3>
                                        <p>Mobile: <span>{{ $storePhone ?: 'N/A' }}</span></p>
                                    </div>
                                </div>

                                <div class="team-contact-box wow fadeInUp" data-wow-delay="0.2s">
                                    <div class="icon-box">
                                        <img src="{{ asset('images/icon-mail.svg') }}" alt="">
                                    </div>
                                    <div class="team-contact-content">
                                        <h3>email address</h3>
                                        <p>{{ $storeEmail ?: 'N/A' }}</p>
                                    </div>
                                </div>

                                <div class="team-contact-box wow fadeInUp" data-wow-delay="0.4s">
                                    <div class="icon-box">
                                        <img src="{{ asset('images/icon-location.svg') }}" alt="">
                                    </div>
                                    <div class="team-contact-content">
                                        <h3>address</h3>
                                        <p>{{ $store->address }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 order-lg-2 order-1">
                    <div class="team-single-sidebar">
                        <div class="team-single-image video-gallery-image" data-cursor-text="Play">
                            <a href="{{ $store->video_url ?? '#' }}" class="popup-video">
                                <figure class="image-anime reveal">
                                    <img src="{{ $store->image ? (Str::contains($store->image, 'images/') ? asset($store->image) : asset('storage/' . $store->image)) : asset('images/logo_yellow2.png') }}"
                                        alt="{{ $store->name }}">
                                </figure>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Google Map -->
                <div class="google-map order-3 order-lg-3 mt-5">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="google-map-iframe">
                                    <iframe width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                        src="https://www.google.com/maps?q={{ $store->lat }},{{ $store->lng }}&hl=en&z=14&output=embed">
                                    </iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            $('.popup-video').magnificPopup({
                type: 'iframe',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                preloader: false,
                fixedContentPos: false
            });
        });
    </script>
@endpush