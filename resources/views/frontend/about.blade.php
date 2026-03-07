@extends('layouts.frontend')

@section('title', 'About Us - Jabulani Group')

@section('content')

    <!-- Page Header -->
    <div class="relative pt-10 pb-24 overflow-hidden bg-dark">
        <div class="absolute inset-0 opacity-15">
            <img src="{{ asset('images/JABULANI_Fleet.webp') }}" class="w-full h-full object-cover" alt="About Hero"
                loading="lazy">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-5xl lg:text-7xl font-black mb-6 tracking-tight uppercase">Our <span
                    class="gradient-text">Legacy</span>
            </h1>
            <p class="text-gold-400 font-bold uppercase tracking-[0.3em] text-sm mb-8">Building South Africa Since 2002</p>
            <nav class="flex justify-center items-center gap-2 text-xs font-bold uppercase tracking-widest text-dark-muted">
                <a href="{{ route('home') }}" class="hover:text-gold-400 transition">Home</a>
                <span class="w-1.5 h-1.5 rounded-full bg-gold-400"></span>
                <span class="text-gray-400">About Us</span>
            </nav>
        </div>
    </div>

    <!-- Scrolling Ticker -->
    <div class="bg-gold-400 py-4 overflow-hidden select-none border-y border-white/10 relative z-20">
        <div class="flex whitespace-nowrap animate-marquee items-center">
            @for($i = 0; $i < 4; $i++)
                <div class="flex items-center gap-8 px-4">
                    <span class="text-dark font-black uppercase tracking-tighter text-sm flex items-center gap-3">
                        <i class="fas fa-star text-[8px] opacity-30"></i> Blocks SABS
                    </span>
                    <span class="text-dark font-black uppercase tracking-tighter text-sm flex items-center gap-3">
                        <i class="fas fa-star text-[8px] opacity-30"></i> Aluminium & Glass
                    </span>
                    <span class="text-dark font-black uppercase tracking-tighter text-sm flex items-center gap-3">
                        <i class="fas fa-star text-[8px] opacity-30"></i> Tiles & Flooring
                    </span>
                    <span class="text-dark font-black uppercase tracking-tighter text-sm flex items-center gap-3">
                        <i class="fas fa-star text-[8px] opacity-30"></i> Roofing Material
                    </span>
                    <span class="text-dark font-black uppercase tracking-tighter text-sm flex items-center gap-3">
                        <i class="fas fa-star text-[8px] opacity-30"></i> Hardware Tools
                    </span>
                </div>
            @endfor
        </div>
    </div>

    <style>
        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .animate-marquee {
            animation: marquee 30s linear infinite;
            display: flex;
            width: max-content;
        }
    </style>

    <!-- Gallery Swiper Section -->
    <section class="py-20 bg-[#050505]" x-data="{ active: 0 }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="space-y-8">
                    <h2 class="text-4xl font-black text-white leading-tight">Your Reliable <span
                            class="text-gold-400">Hardware Partner</span></h2>
                    <div class="space-y-6 text-gray-400 leading-relaxed text-lg font-light">
                        <p>For over two decades, <span class="text-white font-bold">Jabulani Group of Companies</span> has
                            been the trusted name in hardware and building materials, offering top-quality products at the
                            lowest prices across the Eastern Cape.</p>
                        <p>As the leading supplier of SABS-approved blocks, we produce up to 150,000 blocks daily, powering
                            major construction projects across the region.</p>
                    </div>
                    <div class="flex gap-4 pt-4">
                        <a href="{{ route('contact') }}" class="btn-gold group">
                            Work With Us <i
                                class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>

                <!-- Swiper Styled Grid -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-4">
                        <img src="{{ asset('images/JABULANI_Fleet.webp') }}"
                            class="rounded-2xl border border-dark-border shadow-2xl" alt="Fleet">
                        <img src="{{ asset('images/meeting.webp') }}"
                            class="rounded-2xl border border-dark-border shadow-2xl" alt="Meeting">
                    </div>
                    <div class="space-y-4 pt-12">
                        <img src="{{ asset('images/window_pro.webp') }}"
                            class="rounded-2xl border border-dark-border shadow-2xl" alt="Production">
                        <img src="{{ asset('images/big_machine.webp') }}"
                            class="rounded-2xl border border-dark-border shadow-2xl" alt="Machinery">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-24 bg-dark relative overflow-hidden">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-gold-400/5 blur-[120px] rounded-full"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div
                    class="text-center p-8 card-dark bg-white/5 border-white/5 group hover:border-gold-400/30 transition-all duration-500">
                    <h2
                        class="text-4xl md:text-5xl font-black text-gold-400 mb-2 group-hover:scale-110 transition-transform">
                        35K+</h2>
                    <p class="text-[10px] font-black uppercase tracking-widest text-dark-muted">Happy Customers</p>
                </div>
                <div
                    class="text-center p-8 card-dark bg-white/5 border-white/5 group hover:border-gold-400/30 transition-all duration-500">
                    <h2
                        class="text-4xl md:text-5xl font-black text-gold-400 mb-2 group-hover:scale-110 transition-transform">
                        150K+</h2>
                    <p class="text-[10px] font-black uppercase tracking-widest text-dark-muted">Daily Blocks</p>
                </div>
                <div
                    class="text-center p-8 card-dark bg-white/5 border-white/5 group hover:border-gold-400/30 transition-all duration-500">
                    <h2
                        class="text-4xl md:text-5xl font-black text-gold-400 mb-2 group-hover:scale-110 transition-transform">
                        120+</h2>
                    <p class="text-[10px] font-black uppercase tracking-widest text-dark-muted">Trusted Suppliers</p>
                </div>
                <div
                    class="text-center p-8 card-dark bg-white/5 border-white/5 group hover:border-gold-400/30 transition-all duration-500">
                    <h2
                        class="text-4xl md:text-5xl font-black text-gold-400 mb-2 group-hover:scale-110 transition-transform">
                        600+</h2>
                    <p class="text-[10px] font-black uppercase tracking-widest text-dark-muted">Dedicated Experts</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision & Mission -->
    <section class="py-24 bg-[#050505]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Mission -->
                <div class="card-dark group overflow-hidden">
                    <div class="h-64 overflow-hidden">
                        <img src="{{ asset('images/MISSION.JPG') }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                            alt="Mission">
                    </div>
                    <div class="p-8">
                        <div class="w-12 h-12 rounded-xl bg-gold-400 text-dark flex items-center justify-center mb-6">
                            <i class="fas fa-bullseye text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-4">Our Mission</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">To make construction accessible to all, ensuring
                            everyone can build with confidence regardless of financial status.</p>
                    </div>
                </div>

                <!-- Vision -->
                <div class="card-dark group overflow-hidden">
                    <div class="h-64 overflow-hidden">
                        <img src="{{ asset('images/CEO2.png') }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                            alt="Vision">
                    </div>
                    <div class="p-8">
                        <div class="w-12 h-12 rounded-xl bg-gold-400 text-dark flex items-center justify-center mb-6">
                            <i class="fas fa-eye text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-4">Our Vision</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">Sourcing globally and manufacturing locally to
                            offer the best quality at the lowest prices, as envisioned by CEO Naeem Ahmad.</p>
                    </div>
                </div>

                <!-- Values -->
                <div class="card-dark group overflow-hidden">
                    <div class="h-64 overflow-hidden">
                        <img src="{{ asset('images/Values2.JPG') }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                            alt="Values">
                    </div>
                    <div class="p-8">
                        <div class="w-12 h-12 rounded-xl bg-gold-400 text-dark flex items-center justify-center mb-6">
                            <i class="fas fa-heart text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-4">Our Values</h3>
                        <ul class="text-gray-400 text-sm space-y-2">
                            <li class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-gold-400"></span>
                                Integrity & Fair Pricing</li>
                            <li class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-gold-400"></span>
                                Unmatched SABS Quality</li>
                            <li class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-gold-400"></span>
                                Customer-Centric Focus</li>
                            <li class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-gold-400"></span>
                                Global Innovation</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-24 bg-dark relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h4 class="text-gold-400 font-black uppercase tracking-[0.3em] text-xs mb-4">The Experts</h4>
                <h2 class="text-4xl md:text-5xl font-black text-white mb-6">Team <span class="gradient-text">Jabulani</span>
                </h2>
                <p class="text-gray-400">Our dedicated team drives innovation across 8 stores, 4 shop yards, and our main
                    Blockyard production facility.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($teamMembers as $member)
                    @php
                        $imageSrc = $member->image;
                        if ($imageSrc && !Str::startsWith($imageSrc, ['http', 'https'])) {
                            if (!Str::startsWith($imageSrc, 'images/')) {
                                $imageSrc = 'images/' . ltrim($imageSrc, '/');
                            }
                            $imageSrc = asset($imageSrc);
                        } else {
                            $imageSrc = $imageSrc ?: asset('images/logo_yellow2.png');
                        }
                    @endphp
                    <div class="group">
                        <div
                            class="card-dark p-2 rounded-[2rem] overflow-hidden transition-all duration-500 group-hover:border-gold-400/50 group-hover:-translate-y-2 shadow-xl">
                            <div class="h-80 overflow-hidden rounded-[1.8rem] relative">
                                <img src="{{ $imageSrc }}"
                                    class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700 scale-105 group-hover:scale-100"
                                    alt="{{ $member->name }}" loading="lazy">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-60">
                                </div>
                                <div class="absolute bottom-6 left-6 right-6 text-center">
                                    <h3 class="text-white font-black tracking-tight text-lg">{{ $member->name }}</h3>
                                    <p class="text-gold-400 text-[10px] font-black uppercase tracking-widest mt-1">
                                        {{ $member->designation }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-16 text-center">
                <a href="{{ route('contact') }}" class="btn-outline-gold group px-10 py-4">
                    Join Our Growing Team <i class="fas fa-users ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- FAQ & Partners -->
    <section class="py-24 bg-[#050505]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center mb-24">
                <div class="relative">
                    <img src="{{ asset('images/who_we.png') }}" class="rounded-3xl border border-dark-border shadow-2xl"
                        alt="FAQ" loading="lazy">
                    <div class="absolute -bottom-6 -right-6 p-8 bg-gold-400 rounded-3xl shadow-2xl hidden md:block">
                        <p class="text-dark font-black text-2xl tracking-tighter">22+ Years</p>
                        <p class="text-dark/70 text-[10px] font-black uppercase tracking-widest">Industry Leadership</p>
                    </div>
                </div>
                <div class="space-y-10">
                    <h2 class="text-3xl font-black text-white leading-tight">Expertise in <span
                            class="text-gold-400">Building Supplies</span></h2>
                    <div class="space-y-6" x-data="{ active: 1 }">
                        <div class="card-dark border-white/5 overflow-hidden">
                            <button @click="active = 1"
                                class="w-full px-6 py-5 text-left flex justify-between items-center group">
                                <span class="font-bold text-gray-200 group-hover:text-gold-400 transition">What products do
                                    you offer?</span>
                                <i class="fas fa-chevron-down text-xs transition-transform"
                                    :class="active === 1 ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="active === 1" class="px-6 pb-5 text-sm text-gray-400 leading-relaxed"
                                style="display:none;">
                                We provide a wide range of hardware and building materials, including SABS-approved blocks,
                                lintels, pillars, custom aluminum doors and windows, fencing materials, and more.
                            </div>
                        </div>
                        <div class="card-dark border-white/5 overflow-hidden">
                            <button @click="active = 2"
                                class="w-full px-6 py-5 text-left flex justify-between items-center group">
                                <span class="font-bold text-gray-200 group-hover:text-gold-400 transition">Do you supply
                                    bulk orders?</span>
                                <i class="fas fa-chevron-down text-xs transition-transform"
                                    :class="active === 2 ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="active === 2" class="px-6 pb-5 text-sm text-gray-400 leading-relaxed"
                                style="display:none;">
                                Absolutely! We manufacture and supply in bulk for contractors, businesses, and retailers
                                across the Eastern Cape with 15 superlink trucks.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Partners Area -->
            <div class="pt-24 border-t border-white/5">
                <div class="text-center mb-12">
                    <h4 class="text-gold-400 font-black uppercase tracking-[0.3em] text-[10px]">Strategic Alliances</h4>
                </div>
                <div
                    class="flex flex-wrap justify-center items-center gap-12 opacity-40 hover:opacity-100 transition-opacity duration-700 grayscale hover:grayscale-0">
                    <img src="{{ asset('images/makita.png') }}" class="h-8 w-auto object-contain" alt="Makita"
                        loading="lazy">
                    <img src="{{ asset('images/Ingco_Logo.png') }}" class="h-8 w-auto object-contain" alt="Ingco"
                        loading="lazy">
                    <img src="{{ asset('images/Flash.png') }}" class="h-10 w-auto object-contain" alt="Flash"
                        loading="lazy">
                    <img src="{{ asset('images/Eureka.png') }}" class="h-8 w-auto object-contain" alt="Eureka"
                        loading="lazy">
                    <img src="{{ asset('images/Roofco.png') }}" class="h-6 w-auto object-contain" alt="Roofco"
                        loading="lazy">
                    <img src="{{ asset('images/Afrisam.png') }}" class="h-8 w-auto object-contain" alt="Afrisam"
                        loading="lazy">
                </div>
            </div>
        </div>
    </section>

@endsection