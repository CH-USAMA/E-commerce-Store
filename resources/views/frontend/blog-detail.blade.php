@extends('layouts.frontend')

@section('title', $post->title . ' - Jabulani Blog')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">{{ $post->title }}</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('blog') }}">blog</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($post->title, 30) }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    @include('frontend.partials.ticker')

    <!-- Page Single Post Start -->
    <div class="page-single-post">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Post Featured Image Start -->
                    <div class="post-image">
                        <figure class="image-anime">
                            <img src="{{ $post->feature_image ? (Str::contains($post->feature_image, 'images/') ? asset($post->feature_image) : asset('' . $post->feature_image)) : asset('images/placeholder.webp') }}"
                                alt="">
                        </figure>
                    </div>
                    <!-- Post Featured Image End -->

                    <!-- Post Single Content Start -->
                    <div class="post-content">
                        <!-- Post Entry Start -->
                        <div class="post-entry">
                            {!! $post->content !!}
                        </div>
                        <!-- Post Entry End -->

                        <!-- Post Tag Links Start -->
                        <div class="post-tag-links">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <div class="post-tags wow fadeInUp" data-wow-delay="0.5s">
                                        <span class="tag-links">
                                            Category: <a href="#">{{ $post->category->name ?? 'Uncategorized' }}</a>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="post-social-sharing wow fadeInUp" data-wow-delay="0.5s">
                                        <ul>
                                            <li><a href="https://www.facebook.com/share/18Ca1HgEJG/?mibextid=wwXIfr"
                                                    target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
                                            <li><a href="https://www.instagram.com/jabulani_group_hardware"
                                                    target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
                                            <li><a href="https://youtube.com/@jabulanigroup" target="_blank"><i
                                                        class="fa-brands fa-youtube"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Post Tag Links End -->
                    </div>
                    <!-- Post Single Content End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Single Post End -->
@endsection