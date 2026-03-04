@extends('layouts.frontend')

@section('title', 'Video Showcase - Jabulani Group of Companies')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">Jabulani <span>Video Showcase</span></h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Video Showcase</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    @include('frontend.partials.ticker')

    <!-- Page Video Gallery Start -->
    <div class="page-video-gallery py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="video-gallery-image wow fadeInUp" data-wow-delay="0.2s" data-cursor-text="Play">
                        <a href="https://www.youtube.com/watch?v=eQKOShYBd4I" class="popup-video">
                            <figure class="image-anime">
                                <img src="{{ asset('images/who_we.png') }}" alt="Who we are">
                            </figure>
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="video-gallery-image wow fadeInUp" data-wow-delay="0.4s" data-cursor-text="Play">
                        <a href="https://www.youtube.com/watch?v=jM2VQnxPmEs" class="popup-video">
                            <figure class="image-anime">
                                <img src="{{ asset('images/logistics_truck.webp') }}" alt="Logistics">
                            </figure>
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="video-gallery-image wow fadeInUp" data-wow-delay="0.6s" data-cursor-text="Play">
                        <a href="https://www.youtube.com/watch?v=bi8GmsG8Jx4" class="popup-video">
                            <figure class="image-anime">
                                <img src="{{ asset('images/tile_adhesive_machine.webp') }}" alt="Tile Adhesive Machine">
                            </figure>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Video Gallery End -->
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