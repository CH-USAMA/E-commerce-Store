@extends('layouts.frontend')

@section('title', 'Our Services - Jabulani Group')

@section('content')

    <!-- Page Header -->
    <div class="relative pt-32 pb-24 overflow-hidden bg-dark">
        <div class="absolute inset-0 opacity-10">
            <img src="{{ asset('images/quarry_truck.webp') }}" class="w-full h-full object-cover" alt="Services Hero">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-5xl lg:text-7xl font-black mb-6 tracking-tight">Our <span class="gradient-text">Services</span>
            </h1>
            <p class="text-gold-400 font-bold uppercase tracking-[0.3em] text-sm mb-8">Professional Construction Solutions
            </p>
            <nav class="flex justify-center items-center gap-2 text-xs font-bold uppercase tracking-widest text-dark-muted">
                <a href="{{ route('home') }}" class="hover:text-gold-400 transition">Home</a>
                <span class="w-1.5 h-1.5 rounded-full bg-gold-400"></span>
                <span class="text-gray-400">Services</span>
            </nav>
        </div>
    </div>

    <!-- Services Grid -->
    <section class="py-24 bg-[#0a0a0a]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                @php
                    $services = \App\Models\Service::all();
                @endphp

                @foreach($services as $service)
                    <div
                        class="group card-dark p-8 rounded-3xl border-white/5 hover:border-gold-400/30 transition-all duration-500 relative overflow-hidden">

                        @if($service->image)
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-10 transition-opacity duration-500">
                                <img src="{{ asset($service->image) }}" class="w-full h-full object-cover"
                                    alt="{{ $service->title }}">
                            </div>
                        @endif

                        <div
                            class="absolute -right-4 -top-4 w-24 h-24 bg-gold-400/5 rounded-full blur-2xl group-hover:bg-gold-400/10 transition-all">
                        </div>

                        <div
                            class="w-14 h-14 rounded-2xl bg-gold-400/10 flex items-center justify-center mb-8 border border-gold-400/20 group-hover:scale-110 group-hover:bg-gold-400 group-hover:text-dark transition-all duration-500">
                            <i class="{{ $service->icon }} text-2xl"></i>
                        </div>

                        <h3 class="text-xl font-bold text-white mb-4 group-hover:text-gold-400 transition-colors">
                            {{ $service->title }}
                        </h3>
                        <p class="text-gray-400 text-sm leading-relaxed mb-8">{{ $service->description }}</p>

                        <a href="{{ route('contact') }}"
                            class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-widest text-gold-400 hover:gap-4 transition-all">
                            Inquire Now <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                @endforeach

            </div>
        </div>
    </section>

    <!-- Feature Illustration Section -->
    <section class="py-24 bg-dark overflow-hidden relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-dark rounded-[3rem] p-8 md:p-20 relative overflow-hidden border-gold-400/10">
                <div class="absolute inset-0 bg-gradient-to-br from-gold-400/5 to-transparent"></div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center relative z-10">
                    <div>
                        <h4 class="text-gold-400 font-black uppercase tracking-[0.3em] text-xs mb-6">Built to Last</h4>
                        <h2 class="text-4xl md:text-5xl font-black text-white mb-8 leading-tight">Professional <span
                                class="gradient-text">Logistics</span> & Production</h2>
                        <ul class="space-y-6">
                            @foreach(['15+ Superlink Trucks for regional delivery', 'SABS Approved Block Manufacturing', 'Daily production of 150,000 blocks', 'Direct-from-Quarry pricing'] as $feature)
                                <li class="flex items-start gap-4">
                                    <div
                                        class="w-6 h-6 rounded-full bg-gold-400/20 flex items-center justify-center flex-shrink-0 mt-1">
                                        <i class="fas fa-check text-gold-400 text-[10px]"></i>
                                    </div>
                                    <span class="text-gray-300 font-medium">{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-12">
                            <a href="{{ route('contact') }}" class="btn-gold px-10">Get a Professional Quote</a>
                        </div>
                    </div>
                    <div class="relative">
                        <img src="{{ asset('images/JB_About_Hero.webp') }}"
                            class="rounded-3xl shadow-2xl grayscale hover:grayscale-0 transition-all duration-700"
                            alt="Production" loading="lazy">
                        <div class="absolute -top-10 -left-10 w-40 h-40 bg-gold-400/10 rounded-full blur-3xl"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection