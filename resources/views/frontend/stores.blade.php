@extends('layouts.frontend')

@section('title', 'Our Hardware Stores - Jabulani Group')

@section('content')

    <!-- Page Header -->
    <div class="relative pt-32 pb-24 overflow-hidden bg-dark">
        <div class="absolute inset-0 opacity-10">
            <img src="{{ asset('images/yellow_fleet.webp') }}" class="w-full h-full object-cover" alt="Stores Hero">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-5xl lg:text-7xl font-black mb-6 tracking-tight">Our <span class="gradient-text">Network</span>
            </h1>
            <p class="text-gold-400 font-bold uppercase tracking-[0.3em] text-sm mb-8">Serving Transkei Across 8 Major
                Branches</p>
            <nav class="flex justify-center items-center gap-2 text-xs font-bold uppercase tracking-widest text-dark-muted">
                <a href="{{ route('home') }}" class="hover:text-gold-400 transition">Home</a>
                <span class="w-1.5 h-1.5 rounded-full bg-gold-400"></span>
                <span class="text-gray-400">Our Stores</span>
            </nav>
        </div>
    </div>

    <!-- Store Navigation & Grid -->
    <section class="py-24 bg-[#0a0a0a]" x-data="{ filter: 'all' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Filters -->
            <div class="flex flex-wrap justify-center gap-4 mb-16">
                <button @click="filter = 'all'"
                    :class="filter === 'all' ? 'bg-gold-400 text-dark border-gold-400' : 'text-gray-400 border-dark-border hover:border-gold-400/50 bg-white/5'"
                    class="px-8 py-3 rounded-full border text-xs font-black uppercase tracking-widest transition-all duration-300">
                    All Locations
                </button>
                @php
                    $locations = $stores->pluck('province')->unique();
                @endphp
                @foreach($locations as $location)
                    <button @click="filter = '{{ Str::slug($location) }}'"
                        :class="filter === '{{ Str::slug($location) }}' ? 'bg-gold-400 text-dark border-gold-400' : 'text-gray-400 border-dark-border hover:border-gold-400/50 bg-white/5'"
                        class="px-8 py-3 rounded-full border text-xs font-black uppercase tracking-widest transition-all duration-300">
                        {{ $location }}
                    </button>
                @endforeach
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($stores as $store)
                    <div x-show="filter === 'all' || filter === '{{ Str::slug($store->province) }}'"
                        class="group relative card-dark p-3 rounded-[2rem] border-white/5 hover:border-gold-400/30 transition-all duration-500 hover:-translate-y-2"
                        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100">

                        <div class="relative h-64 overflow-hidden rounded-[1.8rem] mb-6 shadow-xl">
                            @php
                                $storeImage = $store->image;
                                if ($storeImage && !Str::startsWith($storeImage, ['http', 'https'])) {
                                    if (!Str::startsWith($storeImage, 'images/')) {
                                        $storeImage = 'images/' . ltrim($storeImage, '/');
                                    }
                                    $storeImage = asset($storeImage);
                                } else {
                                    $storeImage = $storeImage ?: asset('images/logo_yellow2.png');
                                }
                            @endphp
                            <img src="{{ $storeImage }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                alt="{{ $store->name }}" loading="lazy">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-opacity">
                            </div>
                        </div>

                        <div class="px-4 pb-4">
                            <h3 class="text-lg font-black text-white group-hover:text-gold-400 transition-colors mb-2">
                                {{ $store->name }}
                            </h3>
                            <p class="text-xs text-dark-muted font-medium mb-6 line-clamp-2 min-h-[2.5rem] leading-relaxed">
                                {{ $store->address }}
                            </p>

                            <div class="flex items-center justify-between pt-4 border-t border-white/5">
                                <span
                                    class="text-[10px] font-black uppercase tracking-widest text-gold-400/60">{{ $store->province }}</span>
                                <a href="{{ route('store.detail', $store->id) }}"
                                    class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:bg-gold-400 hover:text-dark transition shadow-lg">
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- Support CTA -->
    <section class="py-24 bg-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-dark rounded-[3rem] p-12 md:p-20 text-center relative overflow-hidden border-gold-400/10">
                <div class="absolute inset-0 opacity-5">
                    <img src="{{ asset('images/asterisk-icon.svg') }}" class="w-full h-full object-contain" alt="Pattern">
                </div>
                <div class="relative z-10">
                    <h2 class="text-4xl md:text-5xl font-black text-white mb-6">Can't find a <span
                            class="gradient-text">store</span> near you?</h2>
                    <p class="text-gray-400 text-lg mb-10 max-w-2xl mx-auto leading-relaxed">Don't worry. We deliver across
                        the Eastern Cape. You can browse our products online and have them shipped directly to your site.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-6 justify-center">
                        <a href="{{ route('products') }}" class="btn-gold px-12">Shop Online Now</a>
                        <a href="{{ route('contact') }}" class="btn-outline-gold px-12">Inquire via WhatsApp</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection