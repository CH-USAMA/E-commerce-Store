@extends('layouts.frontend')

@section('title', 'Specials - Jabulani Group of Companies')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">Jabulani <span>Specials</span></h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">specials</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    @include('frontend.partials.ticker')

    <div class="page-gallery py-5">
        <div class="container">
            <div class="row gallery-items page-gallery-box">
                <!-- These could be made dynamic via a Promotions model later -->
                <div class="col-lg-4 col-6 mb-4">
                    <div class="photo-gallery wow fadeInUp" data-cursor-text="View">
                        <a href="{{ asset('images/mtfrere_special.png') }}" class="gallery-popup">
                            <figure class="image-anime">
                                <img src="{{ asset('images/mtfrere_special.webp') }}" alt="Mt Frere Specials">
                            </figure>
                        </a>
                        <div class="post-item-content mt-3">
                            <h3>Jabulani Mt Frere Specials</h3>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-6 mb-4">
                    <div class="photo-gallery wow fadeInUp" data-wow-delay="0.2s" data-cursor-text="View">
                        <a href="{{ asset('images/qumbu_special.png') }}" class="gallery-popup">
                            <figure class="image-anime">
                                <img src="{{ asset('images/qumbu_special.webp') }}" alt="Qumbu Specials">
                            </figure>
                        </a>
                        <div class="post-item-content mt-3">
                            <h3>Jabulani Qumbu Specials</h3>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-6 mb-4">
                    <div class="photo-gallery wow fadeInUp" data-wow-delay="0.4s" data-cursor-text="View">
                        <a href="{{ asset('images/tsolo_special.png') }}" class="gallery-popup">
                            <figure class="image-anime">
                                <img src="{{ asset('images/tsolo_special_compressed.webp') }}" alt="Tsolo Specials">
                            </figure>
                        </a>
                        <div class="post-item-content mt-3">
                            <h3>Jabulani Tsolo Specials</h3>
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
            $('.gallery-popup').magnificPopup({
                type: 'image',
                gallery: { enabled: true }
            });
        });
    </script>
@endpush