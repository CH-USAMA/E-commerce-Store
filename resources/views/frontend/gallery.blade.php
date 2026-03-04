@extends('layouts.frontend')

@section('title', 'Jabulani Gallery - Jabulani Group of Companies')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">Jabulani <span>Gallery</span></h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Gallery</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    @include('frontend.partials.ticker')

    <!-- Photo Gallery Section Start -->
    <div class="page-gallery py-5">
        <div class="container">
            <div class="row gallery-items page-gallery-box">
                @forelse($galleryItems as $item)
                    <div class="col-lg-4 col-sm-6 mb-4">
                        <div class="photo-gallery wow fadeInUp" data-cursor-text="View">
                            @php
                                $imageSrc = $item->image;
                                if ($imageSrc && !Str::startsWith($imageSrc, ['http', 'https'])) {
                                    $imageSrc = Str::contains($imageSrc, 'images/') ? asset($imageSrc) : asset('storage/' . $imageSrc);
                                }
                            @endphp
                            <a href="{{ $imageSrc }}" class="gallery-popup">
                                <figure class="image-anime">
                                    <img src="{{ $imageSrc }}" loading="lazy" alt="{{ $item->title ?? 'Gallery Image' }}">
                                </figure>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p>Gallery is currently being updated. Please check back later.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <!-- Photo Gallery Section End -->
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            $('.gallery-popup').magnificPopup({
                type: 'image',
                gallery: {
                    enabled: true
                }
            });
        });
    </script>
@endpush