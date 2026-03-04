@extends('layouts.frontend')

@section('content')
    <!-- Hero Section Start-->
    <div class="hero hero-slider-layout">
        <div class="swiper">
            <div class="swiper-wrapper">
                @foreach ($banners as $banner)
                    <!-- Hero Slide Start -->
                    <div class="swiper-slide">
                        <div class="hero-slide">
                            <div class="hero-slider-image">
                                <img alt="Background Slide" src="{{ asset($banner->image) }}">
                            </div>
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <!-- Hero Content Start -->
                                        <div class="hero-content">
                                            <!-- Section Title Start -->
                                            <div class="section-title">
                                                <div class="typing-title">
                                                    @foreach (explode(', ', $banner->subtitle) as $sub)
                                                        <p>{{ $sub }}</p>
                                                    @endforeach
                                                </div>
                                                <h1 class="text-anime-style-2 titlemain" data-cursor="-opaque">
                                                    {!! $banner->title !!} <br /><span class="typed-title"></span>
                                                </h1>
                                            </div>
                                            <!-- Section Title End -->
                                            <!-- Hero Content Body Start -->
                                            <div class="hero-content-body">
                                                <div class="hero-content-video">
                                                    <div class="video-play-button">
                                                        <a class="popup-video" data-cursor-text="Play"
                                                            href="{{ $banner->video_url ?? 'https://www.youtube.com/watch?v=eQKOShYBd4I' }}">
                                                            <i class="fa-solid fa-play"></i>
                                                        </a>
                                                    </div>
                                                    <div class="learn-more-circle">
                                                        <img alt="" src="{{ asset('images/learn-more-circle.svg') }}" />
                                                    </div>
                                                </div>
                                                <div class="hero-video-content wow fadeInUp">
                                                    <p>{{ $banner->description }}</p>
                                                </div>
                                            </div>
                                            <div class="hero-btn wow fadeInUp" data-wow-delay="0.25s">
                                                <a class="btn-default gettouch" href="{{ route('contact') }}">get in touch</a>
                                            </div>
                                        </div>
                                        <!-- Hero Content End -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Hero Slide End -->
                @endforeach
            </div>
        </div>
    </div>
    <!-- Hero Section End-->

    <!-- Scrolling Ticker Section Start -->
    <div class="our-scrolling-ticker">
        <div class="scrolling-ticker-box">
            <div class="scrolling-content">
                <span><img alt="" src="{{ asset('images/asterisk-icon.svg') }}" />Blocks SABS</span>
                <span><img alt="" src="{{ asset('images/asterisk-icon.svg') }}" />Aluminium &amp; Glass</span>
                <span><img alt="" src="{{ asset('images/asterisk-icon.svg') }}" />Doors &amp; Windows</span>
                <span><img alt="" src="{{ asset('images/asterisk-icon.svg') }}" />Tiles</span>
                <span><img alt="" src="{{ asset('images/asterisk-icon.svg') }}" />Hardware Tools</span>
                <span><img alt="" src="{{ asset('images/asterisk-icon.svg') }}" />Roofing Material</span>
                <span><img alt="" src="{{ asset('images/asterisk-icon.svg') }}" />Boards</span>
                <span><img alt="" src="{{ asset('images/asterisk-icon.svg') }}" />Plumbing Material</span>
                <span><img alt="" src="{{ asset('images/asterisk-icon.svg') }}" />Electrical Appliances</span>
                <span><img alt="" src="{{ asset('images/asterisk-icon.svg') }}" />Wall &amp; Ceiling</span>
            </div>
            <div class="scrolling-content">
                <span><img alt="" src="{{ asset('images/asterisk-icon.svg') }}" />Blocks SABS</span>
                <span><img alt="" src="{{ asset('images/asterisk-icon.svg') }}" />Aluminium &amp; Glass</span>
                <span><img alt="" src="{{ asset('images/asterisk-icon.svg') }}" />Doors &amp; Windows</span>
                <span><img alt="" src="{{ asset('images/asterisk-icon.svg') }}" />Tiles</span>
                <span><img alt="" src="{{ asset('images/asterisk-icon.svg') }}" />Hardware Tools</span>
                <span><img alt="" src="{{ asset('images/asterisk-icon.svg') }}" />Roofing Material</span>
                <span><img alt="" src="{{ asset('images/asterisk-icon.svg') }}" />Boards</span>
                <span><img alt="" src="{{ asset('images/asterisk-icon.svg') }}" />Plumbing Material</span>
                <span><img alt="" src="{{ asset('images/asterisk-icon.svg') }}" />Electrical Appliances</span>
                <span><img alt="" src="{{ asset('images/asterisk-icon.svg') }}" />Wall &amp; Ceiling</span>
            </div>
        </div>
    </div>
    <!-- Scrolling Ticker Section End -->

    <!-- About Jabulani Section Start -->
    <div class="about-agency">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="about-agency-content">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">about us</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque"><span>Jabulani Group</span> of
                                companies - Your Reliable Hardware Partner Since 2002 – Quality You Can Afford</h2>
                        </div>
                        <div class="section-btn wow fadeInUp" data-wow-delay="0.25s">
                            <a class="btn-default" href="{{ route('about') }}">more about jabulani</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-agency-list">
                        <div class="about-agency-item wow fadeInUp">
                            <div class="icon-box">
                                <img alt="" src="{{ asset('images/logo_yellow2.png') }}" />
                            </div>
                            <div class="agency-item-content">
                                <h3>Supplying Quality &amp; Affordability Since 2002</h3>
                                <p>For over two decades, <span style="color: #ffe507;">Jabulani Group of Companies</span>
                                    has been the trusted name in hardware and building materials, masterfully understanding
                                    and meeting customer needs with unwavering commitment.</p>
                            </div>
                        </div>
                        <div class="about-agency-item wow fadeInUp" data-wow-delay="0.2s">
                            <div class="icon-box">
                                <img alt="" src="{{ asset('images/concrete (1).png') }}" />
                            </div>
                            <div class="agency-item-content">
                                <h3>The Biggest Supplier of Blocks in Transkei</h3>
                                <p>As the leading supplier of SABS-approved blocks, we produce 100,000 to 150,000 blocks
                                    daily and deliver 80,000 to 100,000 blocks with our 15 superlink Trucks across the
                                    Eastern Cape.</p>
                            </div>
                        </div>
                        <div class="about-agency-item wow fadeInUp" data-wow-delay="0.6s">
                            <div class="icon-box">
                                <img alt="" src="{{ asset('images/window (1).png') }}" />
                            </div>
                            <div class="agency-item-content">
                                <h3>Manufacturer of Concrete &amp; Aluminum Products</h3>
                                <p><span style="color: #ffe507;">Jabulani Group of Companies</span> takes pride in in-house
                                    production of concrete products like lintels and pillars, along with custom aluminum
                                    doors and windows.</p>
                            </div>
                        </div>
                        <div class="about-agency-item wow fadeInUp" data-wow-delay="0.4s">
                            <div class="icon-box">
                                <img alt="" src="{{ asset('images/free-trade.png') }}" />
                            </div>
                            <div class="agency-item-content">
                                <h3>Expanding Our Reach with Global Imports</h3>
                                <p>We go beyond local sourcing by importing top-quality hardware and building materials from
                                    different foreign countries like China, offering customers even more affordable options.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Our Stores Section Start -->
    <div class="our-portfolio">
        <div class="container">
            <div class="row section-row align-items-center">
                <div class="col-lg-7">
                    <div class="section-title">
                        <h3 class="wow fadeInUp">Our Hardware Stores</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque"><span>Jabulani Hardware Stores</span> your
                            One-Stop Solution.</h2>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="section-title-content wow fadeInUp" data-wow-delay="0.25s">
                        <p>With our stores and main blockyard, we provide top-quality hardware, building materials, and
                            premium products.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="project-item-boxes2 row align-items-center">
                        @foreach ($stores as $store)
                            <div class="col-lg-3 col-md-6 project-item-box2">
                                <div class="project-item wow fadeInUp">
                                    <div class="project-image">
                                        <figure class="image-anime">
                                            <img alt=""
                                                src="{{ $store->image ? (Str::contains($store->image, 'images/') ? asset($store->image) : asset('storage/' . $store->image)) : asset('images/JBshop(small).webp') }}" />
                                        </figure>
                                        <div class="project-btn">
                                            <a href="{{ route('store.detail', $store->id) }}">
                                                <img alt="" src="{{ asset('images/arrow-white.svg') }}" />
                                            </a>
                                        </div>
                                    </div>
                                    <div class="project-content">
                                        <h3>{{ $store->name }}</h3>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-12 text-center mt-4">
                    <a href="{{ route('stores') }}" class="btn-default">View All Stores</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Brands Section -->
    <div class="our-features">
        <div class="container">
            <div class="row section-row align-items-center">
                <div class="col-lg-6">
                    <div class="section-title">
                        <h3 class="wow fadeInUp">Top Brands</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">Trusted <span> Brands</span>, Quality You Can
                            Build On</h2>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="digital-features-box">
                    <div class="digital-features-item agency-supports">
                        <div class="agency-supports-slider">
                            <div class="swiper">
                                <div class="swiper-wrapper">
                                    @foreach ($brands as $brand)
                                        <div class="swiper-slide">
                                            <div class="agency-supports-logo">
                                                <img alt="{{ $brand->name }}" src="{{ asset($brand->logo) }}" />
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Products Section -->
    <div class="our-portfolio py-5">
        <div class="container">
            <div class="row section-row align-items-center">
                <div class="col-lg-7">
                    <div class="section-title">
                        <h3 class="wow fadeInUp">Top Products</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">Top-Quality <span>hardware &amp;
                                building</span> products</h2>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="section-title-content wow fadeInUp" data-wow-delay="0.25s">
                        <p>Supplying top-quality building materials and hardware to power your projects.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="row project-item-boxes align-items-center">
                        @foreach ($featuredProducts as $product)
                            <div class="col-lg-3 col-md-6 project-item-box">
                                <div class="project-item wow fadeInUp">
                                    <div class="project-image">
                                        <figure class="image-anime">
                                            <img alt=""
                                                src="{{ $product->image ? (Str::contains($product->image, 'images/') ? asset($product->image) : asset('storage/' . $product->image)) : asset('images/placeholder.webp') }}" />
                                        </figure>
                                        <div class="project-tag">
                                            <a
                                                href="{{ route('products', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a>
                                        </div>
                                        <div class="project-btn">
                                            <a href="{{ route('product.detail', $product->slug) }}">
                                                <img alt="" src="{{ asset('images/arrow-white.svg') }}" />
                                            </a>
                                        </div>
                                    </div>
                                    <div class="project-content">
                                        <h3>{{ $product->name }}</h3>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-12 text-center mt-4">
                    <a href="{{ route('products') }}" class="btn-default">View All Products</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Blog Section -->
    <div class="latest-posts py-5">
        <div class="container">
            <div class="row section-row align-items-center">
                <div class="col-lg-12">
                    <div class="section-title text-center">
                        <h3 class="wow fadeInUp">latest blog</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">Explore Our <span>Insights</span></h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($latestPosts as $post)
                    <div class="col-lg-4 col-md-6">
                        <div class="post-item wow fadeInUp">
                            <div class="post-featured-image">
                                <figure class="image-anime">
                                    <img src="{{ $post->feature_image ? (Str::contains($post->feature_image, 'images/') ? asset($post->feature_image) : asset('storage/' . $post->feature_image)) : asset('images/placeholder.webp') }}"
                                        alt="">
                                </figure>
                            </div>
                            <div class="post-item-content">
                                <div class="post-item-header">
                                    <p>{{ $post->category->name }}</p>
                                </div>
                                <h3><a href="{{ route('blog.detail', $post->slug) }}">{{ $post->title }}</a></h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection