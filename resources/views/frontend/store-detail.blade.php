@extends('layouts.frontend')

@section('meta_title', $store->name . ' — Jabulani Group')
@section('meta_description', 'Visit Jabulani ' . $store->name . ' in ' . $store->province . '. ' . Str::limit($store->address, 100))
@if($store->image)
    @section('og_image', asset($store->image))
@endif

@section('content')

    <!-- Page Header -->
    <div class="relative py-24 overflow-hidden bg-dark">
        <div class="absolute inset-0 opacity-10">
            <img src="{{ $store->image ? (Str::contains($store->image, 'images/') ? asset($store->image) : asset('' . $store->image)) : asset('images/logo_yellow2.png') }}" 
                 class="w-full h-full object-cover blur-sm scale-110" alt="Background">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h4 class="text-gold-400 font-black uppercase tracking-[0.3em] text-xs mb-4">Store Location</h4>
            <h1 class="text-5xl lg:text-7xl font-black mb-6 tracking-tight">{{ $store->name }}</h1>
            <nav class="flex justify-center items-center gap-2 text-xs font-bold uppercase tracking-widest text-dark-muted">
                <a href="{{ route('home') }}" class="hover:text-gold-400 transition">Home</a>
                <span class="w-1.5 h-1.5 rounded-full bg-gold-400"></span>
                <a href="{{ route('stores') }}" class="hover:text-gold-400 transition">Our Stores</a>
                <span class="w-1.5 h-1.5 rounded-full bg-gold-400"></span>
                <span class="text-gray-400">{{ $store->province }}</span>
            </nav>
        </div>
    </div>

    @php
        $contactParts = explode(' | ', $store->contact_details ?? '');
        $storePhone = trim($contactParts[1] ?? '');
        $storeEmail = trim($contactParts[0] ?? '');
    @endphp

    <section class="py-24 bg-[#0a0a0a]" x-data="{ 
        isOpen: false, 
        videoUrl: '{{ $store->video_url ?? '' }}'.replace('watch?v=', 'embed/'),
        open() { if(this.videoUrl) { this.isOpen = true; document.body.style.overflow = 'hidden'; } },
        close() { this.isOpen = false; document.body.style.overflow = 'auto'; }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
                
                <!-- Information -->
                <div class="space-y-12">
                    <div class="card-dark p-12 rounded-[3rem] border-white/5 bg-gradient-to-br from-[#111] to-dark shadow-2xl">
                        <div class="flex items-center gap-4 mb-10">
                            <div class="w-16 h-16 rounded-2xl bg-gold-400/10 flex items-center justify-center border border-gold-400/20">
                                <img src="{{ asset('images/maintenance.png') }}" class="w-10 h-10 object-contain" alt="icon">
                            </div>
                            <div>
                                <h2 class="text-3xl font-black text-white">Contact Details</h2>
                                <p class="text-dark-muted text-xs font-black uppercase tracking-widest mt-1">Visit or call us directly</p>
                            </div>
                        </div>

                        <div class="space-y-8">
                            <!-- Phone -->
                            <div class="flex items-start gap-6 group">
                                <div class="w-12 h-12 rounded-full bg-white/5 flex items-center justify-center text-gold-400 group-hover:bg-gold-400 group-hover:text-dark transition-all">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-dark-muted mb-1">Mobile Support</p>
                                    <p class="text-xl font-bold text-white tracking-tight">{{ $storePhone ?: 'Available On Request' }}</p>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="flex items-start gap-6 group">
                                <div class="w-12 h-12 rounded-full bg-white/5 flex items-center justify-center text-gold-400 group-hover:bg-gold-400 group-hover:text-dark transition-all">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-dark-muted mb-1">Email Address</p>
                                    <p class="text-xl font-bold text-white tracking-tight break-all">{{ $storeEmail ?: 'sales@jabulanigroup.co.za' }}</p>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="flex items-start gap-6 group">
                                <div class="w-12 h-12 rounded-full bg-white/5 flex items-center justify-center text-gold-400 group-hover:bg-gold-400 group-hover:text-dark transition-all">
                                    <i class="fas fa-location-dot"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-dark-muted mb-1">Physical Address</p>
                                    <p class="text-lg font-medium text-gray-300 leading-relaxed">{{ $store->address }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-12 pt-10 border-t border-white/5">
                            <a href="https://www.google.com/maps?q={{ $store->lat }},{{ $store->lng }}" target="_blank" class="btn-gold w-full flex justify-center items-center gap-3">
                                <i class="fas fa-directions"></i> Get Directions
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="card-dark p-8 rounded-[2rem] border-white/5 text-center">
                            <p class="text-3xl font-black text-gold-400 mb-1">07:30</p>
                            <p class="text-[10px] font-black uppercase tracking-widest text-dark-muted">Opening Time</p>
                        </div>
                        <div class="card-dark p-8 rounded-[2rem] border-white/5 text-center">
                            <p class="text-3xl font-black text-gold-400 mb-1">17:00</p>
                            <p class="text-[10px] font-black uppercase tracking-widest text-dark-muted">Closing Time</p>
                        </div>
                    </div>
                </div>

                <!-- Media -->
                <div class="space-y-12">
                    <div class="group relative aspect-[4/3] rounded-[3rem] overflow-hidden border-4 border-gold-400/20 shadow-2xl cursor-pointer"
                         @click="open()">
                        <img src="{{ $store->image ? (Str::contains($store->image, 'images/') ? asset($store->image) : asset('' . $store->image)) : asset('images/logo_yellow2.png') }}" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $store->name }}">
                        
                        @if($store->video_url)
                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center group-hover:bg-black/20 transition-all">
                            <div class="w-20 h-20 rounded-full bg-gold-400 text-dark flex items-center justify-center scale-75 group-hover:scale-100 transition-all duration-500 shadow-2xl">
                                <i class="fas fa-play text-2xl ml-1"></i>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Map Embed -->
                    <div class="rounded-[2.5rem] overflow-hidden border border-white/5 shadow-2xl h-96">
                        <iframe width="100%" height="100%" style="border:0; filter: invert(90%) hue-rotate(180deg) brightness(85%) contrast(90%);" allowfullscreen="" loading="lazy"
                            src="https://www.google.com/maps?q={{ $store->lat }},{{ $store->lng }}&hl=en&z=15&output=embed">
                        </iframe>
                    </div>
                </div>

            </div>
        </div>

        <!-- Video Modal -->
        @if($store->video_url)
        <div x-show="isOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 p-4 md:p-10"
             style="display: none;"
             @keydown.escape.window="close()">
            
            <button @click="close()" class="absolute top-10 right-10 text-white hover:text-gold-400 transition text-4xl">
                <i class="fas fa-times"></i>
            </button>

            <div class="w-full max-w-5xl aspect-video rounded-3xl overflow-hidden border border-white/10" @click.away="close()">
                <iframe class="w-full h-full" :src="videoUrl" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
        @endif
    </section>

    <!-- Support CTA -->
    <section class="py-24 bg-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-black text-white mb-6">Need Immediate Assistance?</h2>
            <p class="text-gray-400 mb-10 max-w-2xl mx-auto">Our branch managers are ready to assist with bulk quotes, delivery scheduling, and technical product support.</p>
            <div class="flex justify-center flex-wrap gap-6">
                <a href="https://wa.me/27725920000" class="btn-outline-gold px-10">WhatsApp Branch</a>
                <a href="{{ route('contact') }}" class="btn-gold px-10">Visit Contact Page</a>
            </div>
        </div>
    </section>

@endsection
