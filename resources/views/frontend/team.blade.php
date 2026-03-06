@extends('layouts.frontend')

@section('title', 'Jabulani Team - Jabulani Group')

@section('content')

    <!-- Page Header -->
    <div class="relative pt-32 pb-24 overflow-hidden bg-dark">
        <div class="absolute inset-0 opacity-10">
            <img src="{{ asset('images/meeting.webp') }}" class="w-full h-full object-cover" alt="Team Hero">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-5xl lg:text-7xl font-black mb-6 tracking-tight">Our <span class="gradient-text">Experts</span></h1>
            <p class="text-gold-400 font-bold uppercase tracking-[0.3em] text-sm mb-8">The Visionaries Behind Jabulani Group</p>
            <nav class="flex justify-center items-center gap-2 text-xs font-bold uppercase tracking-widest text-dark-muted">
                <a href="{{ route('home') }}" class="hover:text-gold-400 transition">Home</a>
                <span class="w-1.5 h-1.5 rounded-full bg-gold-400"></span>
                <span class="text-gray-400">Our Team</span>
            </nav>
        </div>
    </div>

    <!-- CEO Highlight Section -->
    <section class="py-24 bg-[#0a0a0a] relative overflow-hidden">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-gold-400/5 blur-[150px] -z-10 rounded-full"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-dark rounded-[3rem] p-8 md:p-16 border-white/5 relative bg-gradient-to-br from-[#111] to-dark overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div class="order-2 lg:order-1">
                        <h4 class="text-gold-400 font-black uppercase tracking-[0.3em] text-xs mb-4">Founder & CEO</h4>
                        <h2 class="text-4xl md:text-5xl font-black text-white mb-8 leading-tight">Naeem <span class="gradient-text">Ahmed</span></h2>
                        <div class="space-y-6 text-gray-400 leading-relaxed text-lg font-light text-justify">
                            <p><span class="text-white font-bold">Naeem Ahmad</span>, born on May 2, 1978, is a visionary entrepreneur with over 32 years of experience in the hardware and building materials industry. Before founding <span class="text-gold-400">Jabulani Group of Companies</span> in 2002, he spent 10 years mastering the trade as a retailer and salesperson.</p>
                            <p>His mission has always been clear—to give back to the community by making high-quality building materials affordable for everyone. This commitment led him to expand Jabulani, opening eight stores across Qumbu, Tsolo, Mount Frere, and Mthatha, ensuring each branch serves its local community with excellence.</p>
                            <p>To further reduce costs without compromising quality, Naeem began importing premium hardware from international markets and established state-of-the-art production plants for SABS-approved blocks, bricks, and custom aluminum products. Under his leadership, Jabulani has grown into a regional powerhouse.</p>
                        </div>
                        <div class="mt-12 flex gap-4">
                            <div class="text-center">
                                <p class="text-2xl font-black text-gold-400">2002</p>
                                <p class="text-[10px] uppercase tracking-widest text-dark-muted">Founded</p>
                            </div>
                            <div class="w-px h-10 bg-white/10 mx-4"></div>
                            <div class="text-center">
                                <p class="text-2xl font-black text-gold-400">32+</p>
                                <p class="text-[10px] uppercase tracking-widest text-dark-muted">Years Exp.</p>
                            </div>
                        </div>
                    </div>
                    <div class="order-1 lg:order-2 relative group">
                        <div class="aspect-[4/5] rounded-[2.5rem] overflow-hidden border-4 border-gold-400/20 shadow-2xl relative">
                            <img src="{{ asset('images/CEO2.png') }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Naeem Ahmed">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        </div>
                        <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-gold-400 rounded-3xl flex items-center justify-center text-dark font-black text-center p-4 shadow-2xl rotate-3 group-hover:rotate-0 transition-transform">
                            <span class="text-sm uppercase tracking-tighter">Visionary Leadership</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Professional Grid -->
    <section class="py-24 bg-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h4 class="text-gold-400 font-black uppercase tracking-[0.3em] text-xs mb-4">Core Leadership</h4>
                <h2 class="text-4xl md:text-5xl font-black text-white mb-6">Management <span class="gradient-text">Team</span></h2>
                <p class="text-gray-400">Our diverse team of experts brings together decades of experience in manufacturing, logistics, and customer service.</p>
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
                        <div class="card-dark p-2 rounded-[2.5rem] overflow-hidden transition-all duration-500 hover:border-gold-400/50 hover:-translate-y-2 shadow-2xl relative">
                            <div class="h-96 overflow-hidden rounded-[2.2rem] relative">
                                <img src="{{ $imageSrc }}" 
                                     class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700 scale-105 group-hover:scale-100" 
                                     alt="{{ $member->name }}" loading="lazy">
                                
                                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-60"></div>
                                
                                <div class="absolute bottom-6 left-6 right-6">
                                    <h3 class="text-white font-black tracking-tight text-xl leading-tight">{{ $member->name }}</h3>
                                    <p class="text-gold-400 text-[10px] font-black uppercase tracking-widest mt-2">{{ $member->designation }}</p>
                                    
                                    @if($member->location)
                                    <div class="mt-4 pt-4 border-t border-white/10 flex items-center justify-between">
                                        <span class="text-[9px] font-black uppercase tracking-widest text-dark-muted">
                                            <i class="fas fa-location-dot mr-1 text-gold-400/50"></i> {{ $member->location }}
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Join Us CTA -->
            <div class="mt-24 text-center">
                <div class="inline-flex flex-col md:flex-row items-center gap-8 bg-white/5 p-8 md:p-12 rounded-[3rem] border border-white/5">
                    <div class="text-left">
                        <h3 class="text-2xl font-bold text-white mb-2">Want to join the family?</h3>
                        <p class="text-gray-400 text-sm">We are always looking for passionate people to join our growing team.</p>
                    </div>
                    <a href="{{ route('contact') }}" class="btn-gold px-12 whitespace-nowrap">Apply Today</a>
                </div>
            </div>
        </div>
    </section>

@endsection
