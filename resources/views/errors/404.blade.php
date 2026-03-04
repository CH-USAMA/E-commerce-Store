@extends('layouts.frontend')

@section('title', 'Page Not Found - Jabulani Group')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">Page <span>Not</span> Found</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">404 error page</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    @include('frontend.partials.ticker')

    <!-- error section start -->
    <div class="error-page py-5">
        <div class="container text-center">
            <div class="row">
                <div class="col-lg-12">
                    <div class="error-page-image wow fadeInUp">
                        <img src="{{ asset('images/404-error-img.png') }}" alt="404" class="img-fluid"
                            style="max-width: 500px;">
                    </div>
                    <div class="error-page-content mt-4">
                        <div class="section-title">
                            <h2 class="wow fadeInUp" data-wow-delay="0.25s">Oops! <span>Page Not Found</span></h2>
                        </div>
                        <div class="error-page-content-body mt-3">
                            <p class="wow fadeInUp" data-wow-delay="0.5s">The page you are looking for does not exist or has
                                been moved.</p>
                            <a class="btn-default wow fadeInUp mt-4" data-wow-delay="0.75s" href="{{ route('home') }}">back
                                to home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- error section end -->
@endsection