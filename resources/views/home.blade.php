@extends('layouts.frontend')

@push('styles')
    <style>
        .animate-marquee {
            animation: marquee 30s linear infinite;
        }
    </style>
@endpush

@section('title', 'Jabulani Group - Premium Hardware & Building Materials')

@section('content')

    {{-- Hero Section Redesign: Pro Selling Store Feel --}}
    @if(count($banners) > 0)
        <div x-data="{ activeSlide: 0, interval: null }"
            x-init="interval = setInterval(() => { activeSlide = activeSlide === {{ count($banners) - 1 }} ? 0 : activeSlide + 1 }, 7000)"
            class="relative w-full h-[calc(100vh-60px)] min-h-[600px] overflow-hidden bg-black">

            @foreach($banners as $index => $banner)
                <div x-show="activeSlide === {{ $index }}" x-transition:enter="transition ease-in-out duration-1000 transform"
                    x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in-out duration-1000 transform"
                    x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-full"
                    class="absolute inset-0 z-10 w-full h-full" style="display: none;">

                    {{-- Background Image & Subtle Shadows --}}
                    <img src="{{ asset($banner->image) }}" alt="Jabulani Hero"
                        class="absolute inset-0 w-full h-full object-cover object-center" loading="eager" />

                    {{-- Top & Bottom Subtle Overlays for Readability --}}
                    <div
                        class="absolute inset-x-0 top-0 h-64 bg-gradient-to-b from-black/80 via-black/20 to-transparent z-10 pointer-events-none">
                    </div>
                    <div
                        class="absolute inset-x-0 bottom-0 h-64 bg-gradient-to-t from-black/60 to-transparent z-10 pointer-events-none">
                    </div>

                    {{-- Content --}}
                    <div class="relative z-20 h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col justify-center">
                        <div class="max-w-4xl pt-32 md:pt-40">
                            {{-- Bullet Points --}}
                            <div class="flex flex-wrap items-center gap-3 mb-8 animate-fade-in-up" style="animation-delay: 0.1s;">
                                @foreach(['Hardware stores', 'Crush & Quarry', 'Building material', 'Construction solutions'] as $bullet)
                                    <div
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-black/40 border border-white/10 backdrop-blur-md hover:bg-black/60 transition-colors">
                                        <span
                                            class="w-1.5 h-1.5 rounded-full bg-gold-400 shadow-[0_0_10px_rgba(var(--brand-primary-rgb),0.8)]"></span>
                                        <span class="text-[10px] font-black uppercase tracking-widest text-white/90">
                                            {{ $bullet }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            <h1
                                class="text-5xl md:text-7xl font-black text-white leading-[1.1] mb-8 tracking-tighter uppercase italic drop-shadow-2xl">
                                {!! str_replace('Jabulani Group of Companies', '<span class="gradient-text">Jabulani Group</span> <br><span class="text-4xl md:text-6xl text-white/90">of Companies</span>', $banner->title) !!}
                            </h1>

                            <p class="text-xl text-gray-200 mb-10 max-w-2xl font-medium leading-relaxed drop-shadow-md">
                                {{ $banner->description }}
                            </p>

                            <div class="flex flex-wrap items-center gap-6">
                                <a href="{{ route('products') }}"
                                    class="group relative px-10 py-5 bg-gold-400 overflow-hidden rounded-full transition-all duration-300 hover:shadow-[0_0_30px_rgba(var(--brand-primary-rgb),0.4)] hover:-translate-y-1">
                                    <span
                                        class="relative z-10 text-dark font-black uppercase tracking-widest text-sm flex items-center gap-2">
                                        Shop Now <i
                                            class="fas fa-shopping-bag text-xs transition-transform group-hover:scale-125"></i>
                                    </span>
                                </a>
                                <a href="{{ route('contact') }}"
                                    class="px-10 py-5 border-2 border-white/20 rounded-full text-white font-black uppercase tracking-widest text-sm hover:bg-white hover:text-black hover:border-white transition-all duration-300 backdrop-blur-sm">
                                    Get Quote
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Slider Controls --}}
            <div class="absolute bottom-12 left-0 right-0 z-30">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center border-[0]">
                    <div class="flex items-center gap-3">
                        @foreach($banners as $index => $banner)
                            <button @click="activeSlide = {{ $index }}; clearInterval(interval)"
                                :class="activeSlide === {{ $index }} ? 'w-16 bg-gold-400 shadow-[0_0_10px_rgba(var(--brand-primary-rgb),0.5)]' : 'w-4 bg-white/30 hover:bg-white/60'"
                                class="h-1.5 rounded-full transition-all duration-500"></button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Scrolling Ticker --}}
    <style>
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .animate-marquee {
            animation: marquee 30s linear infinite;
            width: max-content;
        }
        .animate-marquee:hover {
            animation-play-state: paused;
        }
    </style>
    <div
        class="py-4 bg-gold-400 border-y border-gold-400/20 overflow-hidden whitespace-nowrap flex relative z-20 shadow-[0_10px_30px_rgba(var(--brand-primary-rgb),0.15)]">
        <div class="flex animate-marquee items-center">
            @for($i = 0; $i < 6; $i++)
                @foreach(['JABULANI HARDWARE', 'JABULANI CRUSH & QUARRY', 'JABULANI BUILDING MATERIAL', 'JABULANI CONSTRUCTION'] as $item)
                    <span
                        class="flex items-center text-[12px] font-black text-dark/80 hover:text-dark transition-colors duration-300 uppercase tracking-[0.4em] px-12 cursor-default">
                        <i class="fas fa-certificate text-[10px] mr-12 text-dark/40 animate-pulse"></i>
                        {{ $item }}
                    </span>
                @endforeach
            @endfor
        </div>
    </div>

    {{-- NEW: PREMIUM CATEGORY SLIDER --}}
    <section class="py-24 bg-[#050505] relative overflow-hidden" x-data="{ currentCat: 0 }">
        <div
            class="absolute top-0 right-0 w-1/3 h-full bg-gold-400/5 blur-[120px] rounded-full -translate-y-1/2 translate-x-1/2">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-8">
                <div>
                    <h3 class="gradient-text text-sm font-black uppercase tracking-[0.4em] mb-4">Discover Our Range</h3>
                    <h2 class="text-5xl md:text-6xl font-black text-white tracking-tighter uppercase italic">Shop By <span
                            class="text-white/20">Category</span></h2>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('products') }}"
                        class="text-xs font-black uppercase tracking-widest text-gold-400 hover:text-white transition group flex items-center gap-2">
                        View All Categories <i class="fas fa-arrow-right group-hover:translate-x-1 transition"></i>
                    </a>
                </div>
            </div>

            <div class="relative">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    @forelse($categories->whereNull('parent_id') as $category)
                        <a href="{{ route('products', ['category' => $category->slug]) }}"
                            class="group relative aspect-[4/5] rounded-[2rem] overflow-hidden bg-dark-card border border-white/5 hover:border-gold-400/40 transition-all duration-500">
                            {{-- Image --}}
                            @php
                                $catImgSrc = 'images/placeholder.webp';
                                if ($category->image && file_exists(public_path($category->image))) {
                                    $catImgSrc = implode('/', array_map('rawurlencode', explode('/', $category->image)));
                                }
                            @endphp
                            <img src="{{ asset($catImgSrc) }}"
                                class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-all duration-700 opacity-80 group-hover:opacity-100"
                                alt="{{ $category->name }}">

                            {{-- Overlay --}}
                            <div
                                class="absolute inset-x-0 bottom-0 p-6 bg-gradient-to-t from-black via-black/40 to-transparent pt-20">
                                <h4
                                    class="text-white font-black uppercase tracking-wider text-sm mb-1 group-hover:text-gold-400 transition-colors">
                                    {{ $category->name }}
                                </h4>
                                <p class="text-[9px] font-bold text-white/40 uppercase tracking-widest">
                                    Explore More <i
                                        class="fas fa-arrow-right ml-1 opacity-0 group-hover:opacity-100 transition translate-x-[-10px] group-hover:translate-x-0"></i>
                                </p>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full py-20 text-center opacity-40">Categories coming soon...</div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    {{-- TOP SELLING PRODUCTS SECTION --}}
    @if($topSellingProducts->count() > 0)
        <section class="py-24 bg-black">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h3 class="text-gold-400 text-xs font-black uppercase tracking-[0.5em] mb-4">Customer Favorites</h3>
                    <h2 class="text-4xl md:text-5xl font-black text-white uppercase italic tracking-tighter">Top Selling <span
                            class="gradient-text">Items</span></h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
                    @foreach($topSellingProducts as $product)
                        @include('frontend.partials.product_card', ['product' => $product])
                    @endforeach
                </div>

                <div class="text-center">
                    <a href="{{ route('products', ['sort' => 'popular']) }}"
                        class="inline-flex items-center gap-4 px-12 py-5 border-2 border-white/5 rounded-full text-white/60 font-black uppercase tracking-[0.2em] text-xs hover:border-gold-400 hover:text-gold-400 transition-all duration-500">
                        View All Top Sellers <i class="fas fa-long-arrow-right"></i>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- NEWLY ADDED PRODUCTS SECTION --}}
    @if($newArrivalProducts->count() > 0)
        <section class="py-24 bg-[#050505]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h3 class="text-gold-400 text-xs font-black uppercase tracking-[0.5em] mb-4">Latest Additions</h3>
                    <h2 class="text-4xl md:text-5xl font-black text-white uppercase italic tracking-tighter">New Arrivals <span
                            class="text-white/20">Catalog</span></h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                    @foreach($newArrivalProducts as $product)
                        @include('frontend.partials.product_card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Rest of the original content (About, Stats, etc.) adjusted for the new theme --}}
    <section class="py-24 bg-[#050505]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                <div class="relative">
                    <div class="absolute -top-10 -left-10 w-40 h-40 bg-gold-400/20 rounded-full blur-3xl"></div>
                    <img src="{{ asset('images/JBshop(blockyard).webp') }}"
                        class="rounded-[3rem] border border-white/10 relative z-10 shadow-2xl" alt="About Jabulani">
                    <div class="absolute -bottom-6 -right-6 bg-gold-400 p-8 rounded-[2rem] z-20 shadow-2xl hidden md:block">
                        <span class="text-4xl font-black text-dark block mb-1">22+</span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-dark opacity-60">Years
                            Excellence</span>
                    </div>
                </div>
                <div>
                    <h3 class="gradient-text text-sm font-black uppercase tracking-[0.4em] mb-4">Our Heritage</h3>
                    <h2
                        class="text-4xl md:text-5xl font-black text-white mb-8 leading-tight italic uppercase tracking-tighter">
                        Building <span class="text-white/20">Legacy</span><br>Since 2002
                    </h2>
                    <p class="text-gray-400 text-lg leading-relaxed mb-10 font-medium opacity-80">
                        Jabulani Group has evolved from a local retail store into a cornerstone of South Africa's
                        construction supply chain. We don't just sell materials; we enable dreams.
                    </p>
                    <div class="grid grid-cols-2 gap-8 mb-10">
                        @foreach(['Reliable Logistics', 'SABS Approved', 'Wholesale Prices', 'Expert Support'] as $feature)
                            <div class="flex items-center gap-4 text-white hover:text-gold-400 transition group">
                                <div
                                    class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center group-hover:bg-gold-400 transition duration-500">
                                    <i class="fas fa-check text-xs group-hover:text-dark"></i>
                                </div>
                                <span class="font-black uppercase tracking-widest text-[10px]">{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('about') }}"
                        class="btn-gold px-12 py-5 rounded-full text-xs uppercase tracking-widest font-black">
                        Learn More About Us
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script>
        function addToCart(productId, productName, price) {
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ product_id: productId, quantity: 1 })
            })
                .then(r => r.json())
                .then(data => {
                    if (window.showToast) window.showToast(productName + ' added to cart!', 'success');
                    if (data.cart_count !== undefined && window.updateCartBadge) window.updateCartBadge(data.cart_count);
                })
                .catch(() => {
                    if (window.showToast) window.showToast('Error adding to cart', 'error');
                });
        }
    </script>

    {{-- Our Brands Start --}}
    <div class="our-features py-24 bg-[#050505] border-t border-white/5">
        <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="row flex flex-col md:flex-row align-items-center mb-16 gap-8 text-white">
                <div class="col-lg-6">
                    {{-- Section Title Start --}}
                    <div class="section-title">
                        <h3 class="gradient-text text-sm font-black uppercase tracking-[0.4em] mb-4">Top Brands</h3>
                        <h2 class="text-4xl md:text-5xl font-black text-white uppercase italic tracking-tighter">Trusted
                            <span class="text-white/20"> Brands</span>, <br>Quality You Can Build On
                        </h2>
                    </div>
                </div>
                <div class="col-lg-6 text-gray-400 text-lg leading-relaxed font-medium opacity-80">
                    <p class="mb-8">Providing the best brands for durability, strength, and quality craftsmanship for every
                        build from foundation to finish.</p>
                    <a class="btn-gold px-12 py-5 rounded-full text-xs uppercase tracking-widest font-black inline-block text-dark"
                        href="{{ route('contact') }}">Learn More</a>
                </div>
            </div>

            <div class="col-lg-12 mt-12">
                {{-- Digital Features Box Start --}}
                <div
                    class="digital-features-box bg-dark-card border border-white/5 p-8 md:p-12 rounded-[2rem] overflow-hidden relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-gold-400/5 to-transparent pointer-events-none"></div>

                    <div
                        class="flex flex-col md:flex-row items-center justify-between mb-12 relative z-10 gap-6 border-b border-white/5 pb-10">
                        <div class="text-white">
                            <h3 class="text-2xl font-black mb-2">Trusted and reliable Brands</h3>
                            <p class="text-white/60 text-sm">Supplying trusted brands and quality materials to build your
                                vision since 2002.</p>
                        </div>
                    </div>

                    {{-- Agency Support Slider Start --}}
                    <div class="agency-supports-slider relative z-10 overflow-hidden w-full">
                        <div class="flex animate-marquee-brands items-center min-w-max">
                            @for($i = 0; $i < 4; $i++)
                                @foreach(['images/jab_logo.png', 'images/Ingco_Logo.png', 'images/hendok.png', 'images/harvey.png', 'images/makita.png', 'images/Duramlogo.png', 'images/Afrisam.png', 'images/PGBISON.png', 'images/Lasher.png', 'images/Roofco.png', 'images/geo.png', 'images/jojotanks.png', 'images/Eureka.png', 'images/Flash.png', 'images/Corobrik.png'] as $logo)
                                    <div
                                        class="px-8 opacity-50 hover:opacity-100 grayscale hover:grayscale-0 transition-all duration-300 flex justify-center items-center h-24">
                                        <img alt="Brand Logo" src="{{ asset($logo) }}"
                                            class="max-h-full max-w-[120px] object-contain" />
                                    </div>
                                @endforeach
                            @endfor
                        </div>
                    </div>
                    <style>
                        @keyframes marquee-brands {
                            0% {
                                transform: translateX(0);
                            }

                            100% {
                                transform: translateX(-50%);
                            }
                        }

                        .animate-marquee-brands {
                            animation: marquee-brands 60s linear infinite;
                            width: max-content;
                        }

                        .animate-marquee-brands:hover {
                            animation-play-state: paused;
                        }
                    </style>
                </div>
            </div>
        </div>
    </div>
    {{-- Our Brands End --}}

    @include('frontend.sections.cta_contact')

@endsection

@push('styles')
    <style>
        @keyframes slide-left {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .animate-marquee {
            animation: slide-left 40s linear infinite;
        }

        .stat-card-glow {
            box-shadow: 0 0 30px rgba(var(--brand-primary-rgb), 0.05);
        }

        .gradient-text {
            background: linear-gradient(to right, var(--brand-primary), var(--brand-primary-faded));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
@endpush

@push('seo')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "Organization",
  "name": "Jabulani Group of Companies",
  "url": "{{ config('app.url') }}",
  "logo": "{{ asset('images/logo_yellow2.png') }}",
  "sameAs": [
    "https://www.facebook.com/share/18Ca1HgEJG/",
    "https://www.instagram.com/jabulani_group_hardware",
    "https://youtube.com/@jabulanigroup",
    "https://www.tiktok.com/@jabulani.logistic",
    "https://www.linkedin.com/company/jabulani-group-of-companies/"
  ]
}
</script>
@endpush