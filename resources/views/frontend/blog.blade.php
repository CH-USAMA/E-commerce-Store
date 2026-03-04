@extends('layouts.frontend')

@section('title', 'Latest Blog - Jabulani Group of Companies')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">Latest <span>blog</span></h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">blog</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    @include('frontend.partials.ticker')

    <!-- Page Blog Start -->
    <div class="page-blog">
        <div class="container">
            <div class="row">
                @forelse($posts as $post)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="post-item wow fadeInUp">
                            <div class="post-featured-image">
                                <figure class="image-anime">
                                    <img src="{{ $post->feature_image ? (Str::contains($post->feature_image, 'images/') ? asset($post->feature_image) : asset('storage/' . $post->feature_image)) : asset('images/placeholder.webp') }}" alt="">
                                </figure>
                            </div>
                            <div class="post-item-body">
                                <div class="post-item-content">
                                    <p class="text-muted small mb-2">
                                        {{ $post->published_at ? $post->published_at->format('M d, Y') : 'Recent' }} |
                                        {{ $post->category->name ?? 'News' }}</p>
                                    <h3><a href="{{ route('blog.detail', $post->slug) }}">{{ $post->title }}</a></h3>
                                </div>
                                <div class="post-item-btn">
                                    <a href="{{ route('blog.detail', $post->slug) }}">read more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p>No blog posts found at the moment. Check back soon!</p>
                    </div>
                @endforelse

                @if($posts->hasPages())
                    <div class="col-lg-12">
                        <div class="page-pagination wow fadeInUp" data-wow-delay="0.5s">
                            {{ $posts->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Page Blog End -->
@endsection