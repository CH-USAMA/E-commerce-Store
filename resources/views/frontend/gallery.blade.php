@extends('layouts.frontend')

@section('title', 'Jabulani Gallery - Jabulani Group')

@section('content')

    <!-- Page Header -->
    <div class="relative py-24 overflow-hidden bg-dark">
        <div class="absolute inset-0 opacity-10">
            <img src="{{ asset('images/meeting.webp') }}" class="w-full h-full object-cover" alt="Gallery Hero">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-5xl lg:text-7xl font-black mb-6 tracking-tight">Visual <span
                    class="gradient-text">Journal</span></h1>
            <p class="text-gold-400 font-bold uppercase tracking-[0.3em] text-sm mb-8">Our Journey in Pictures</p>
            <nav class="flex justify-center items-center gap-2 text-xs font-bold uppercase tracking-widest text-dark-muted">
                <a href="{{ route('home') }}" class="hover:text-gold-400 transition">Home</a>
                <span class="w-1.5 h-1.5 rounded-full bg-gold-400"></span>
                <span class="text-gray-400">Gallery</span>
            </nav>
        </div>
    </div>

    <!-- Gallery Grid with Alpine Lightbox -->
    <section class="py-24 bg-[#0a0a0a]" x-data="{ 
            isOpen: false, 
            activeImg: '', 
            activeTitle: '',
            open(img, title) {
                this.activeImg = img;
                this.activeTitle = title;
                this.isOpen = true;
                document.body.style.overflow = 'hidden';
            },
            close() {
                this.isOpen = false;
                document.body.style.overflow = 'auto';
            }
        }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($galleryItems->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($galleryItems as $item)
                        @php
                            $imageSrc = $item->image;
                            if ($imageSrc && !Str::startsWith($imageSrc, ['http', 'https'])) {
                                $imageSrc = Str::contains($imageSrc, 'images/') ? asset($imageSrc) : asset('' . $imageSrc);
                            }
                        @endphp
                        <div class="group relative card-dark p-2 rounded-3xl border-white/5 hover:border-gold-400/30 transition-all duration-500 overflow-hidden cursor-pointer shadow-2xl"
                            @click="open('{{ $imageSrc }}', '{{ $item->title ?? 'Jabulani Group' }}')">

                            <div class="relative h-80 overflow-hidden rounded-[1.6rem]">
                                <img src="{{ $imageSrc }}"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                    alt="{{ $item->title ?? 'Gallery Image' }}">

                                <div
                                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <div
                                        class="w-14 h-14 rounded-full bg-gold-400 text-dark flex items-center justify-center scale-75 group-hover:scale-100 transition-transform duration-500">
                                        <i class="fas fa-expand-alt text-xl"></i>
                                    </div>
                                </div>
                            </div>

                            @if($item->title)
                                <div class="px-5 py-6">
                                    <h3
                                        class="text-sm font-black text-gray-200 group-hover:text-gold-400 transition-colors uppercase tracking-widest truncate">
                                        {{ $item->title }}</h3>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-24 card-dark rounded-[3rem] border-white/5">
                    <div class="w-20 h-20 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-images text-gray-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-400">Our gallery is currently being updated.</h3>
                    <p class="text-dark-muted text-sm mt-2">Check back soon for more project highlights!</p>
                </div>
            @endif

        </div>

        <!-- Lightbox -->
        <div x-show="isOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 p-4 md:p-10" style="display: none;"
            @keydown.escape.window="close()">

            <button @click="close()" class="absolute top-10 right-10 text-white hover:text-gold-400 transition text-4xl">
                <i class="fas fa-times"></i>
            </button>

            <div class="max-w-5xl w-full h-full flex flex-col items-center justify-center gap-8" @click.away="close()">
                <img :src="activeImg"
                    class="max-h-[80vh] w-auto border border-white/10 shadow-[0_0_100px_rgba(245,197,24,0.1)] rounded-2xl"
                    alt="Lightbox Image">
                <p x-text="activeTitle"
                    class="text-gold-400 font-black uppercase tracking-[0.4em] text-sm md:text-lg text-center"></p>
            </div>
        </div>
    </section>

@endsection