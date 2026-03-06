@extends('layouts.frontend')

@section('title', 'Video Showcase - Jabulani Group')

@section('content')

    <!-- Page Header -->
    <div class="relative py-24 overflow-hidden bg-dark">
        <div class="absolute inset-0 opacity-10">
            <img src="{{ asset('images/logistics_truck.webp') }}" class="w-full h-full object-cover" alt="Video Hero">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-5xl lg:text-7xl font-black mb-6 tracking-tight">Digital <span
                    class="gradient-text">Showcase</span></h1>
            <p class="text-gold-400 font-bold uppercase tracking-[0.3em] text-sm mb-8">Experience Our Operations in Motion
            </p>
            <nav class="flex justify-center items-center gap-2 text-xs font-bold uppercase tracking-widest text-dark-muted">
                <a href="{{ route('home') }}" class="hover:text-gold-400 transition">Home</a>
                <span class="w-1.5 h-1.5 rounded-full bg-gold-400"></span>
                <span class="text-gray-400">Video Gallery</span>
            </nav>
        </div>
    </div>

    <!-- Video Grid Section -->
    <section class="py-24 bg-[#0a0a0a]" x-data="{ 
            isOpen: false, 
            videoUrl: '', 
            open(url) {
                this.videoUrl = url.replace('watch?v=', 'embed/');
                this.isOpen = true;
                document.body.style.overflow = 'hidden';
            },
            close() {
                this.isOpen = false;
                this.videoUrl = '';
                document.body.style.overflow = 'auto';
            }
        }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">

                @php
                    $videos = [
                        ['title' => 'Who We Are', 'url' => 'https://www.youtube.com/watch?v=eQKOShYBd4I', 'img' => asset('images/who_we.png')],
                        ['title' => 'Logistics Excellence', 'url' => 'https://www.youtube.com/watch?v=jM2VQnxPmEs', 'img' => asset('images/logistics_truck.webp')],
                        ['title' => 'Production Innovation', 'url' => 'https://www.youtube.com/watch?v=bi8GmsG8Jx4', 'img' => asset('images/tile_adhesive_machine.webp')]
                    ];
                @endphp

                @foreach($videos as $vid)
                    <div class="group relative card-dark p-2 rounded-[2.5rem] border-white/5 hover:border-gold-400/30 transition-all duration-500 overflow-hidden cursor-pointer shadow-2xl"
                        @click="open('{{ $vid['url'] }}')">

                        <div class="relative h-64 overflow-hidden rounded-[2.2rem]">
                            <img src="{{ $vid['img'] }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 grayscale-[0.5] group-hover:grayscale-0"
                                alt="{{ $vid['title'] }}">

                            <!-- Play Button Overlay -->
                            <div
                                class="absolute inset-0 bg-black/40 flex items-center justify-center group-hover:bg-black/20 transition-all duration-500">
                                <div
                                    class="w-16 h-16 rounded-full bg-gold-400 text-dark flex items-center justify-center scale-75 group-hover:scale-100 transition-all duration-500 shadow-[0_0_50px_rgba(245,197,24,0.3)]">
                                    <i class="fas fa-play text-xl ml-1"></i>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-8">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="w-2 h-2 rounded-full bg-gold-400"></span>
                                <span class="text-[10px] font-black uppercase tracking-widest text-dark-muted">Featured
                                    Video</span>
                            </div>
                            <h3 class="text-xl font-bold text-white group-hover:text-gold-400 transition-colors">
                                {{ $vid['title'] }}</h3>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

        <!-- Video Modal -->
        <div x-show="isOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 p-4 md:p-10" style="display: none;"
            @keydown.escape.window="close()">

            <button @click="close()" class="absolute top-10 right-10 text-white hover:text-gold-400 transition text-4xl">
                <i class="fas fa-times"></i>
            </button>

            <div class="w-full max-w-5xl aspect-video relative rounded-3xl overflow-hidden border border-white/10 shadow-[0_0_100px_rgba(245,197,24,0.1)]"
                @click.away="close()">
                <iframe class="w-full h-full" :src="videoUrl" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        </div>
    </section>

    <!-- YouTube CTA -->
    <section class="py-24 bg-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-dark rounded-[3rem] p-12 md:p-20 text-center border-gold-400/10 flex flex-col items-center">
                <div
                    class="w-20 h-20 rounded-full bg-red-600/10 border border-red-600/20 flex items-center justify-center mb-8">
                    <i class="fab fa-youtube text-3xl text-red-600"></i>
                </div>
                <h2 class="text-3xl md:text-5xl font-black text-white mb-6">See more on our <span
                        class="gradient-text">Channel</span></h2>
                <p class="text-gray-400 text-lg mb-10 max-w-2xl mx-auto">Subscribe to our official YouTube channel for deep
                    dives into our manufacturing processes, site deliveries, and corporate events.</p>
                <a href="https://youtube.com/@jabulanigroup" target="_blank" class="btn-gold px-12 group">
                    Visit Jabulani on YouTube <i class="fab fa-youtube ml-2 group-hover:scale-110 transition-transform"></i>
                </a>
            </div>
        </div>
    </section>

@endsection