@extends('layouts.frontend')

@section('title', 'Seasonal Specials - Jabulani Group')

@section('content')

    <!-- Page Header -->
    <div class="relative py-24 overflow-hidden bg-dark">
        <div class="absolute inset-0 opacity-10">
            <img src="{{ asset('images/qumbu_special_compressed.webp') }}" class="w-full h-full object-cover"
                alt="Specials Hero">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-5xl lg:text-7xl font-black mb-6 tracking-tight">Exclusive <span
                    class="gradient-text">Deals</span></h1>
            <p class="text-gold-400 font-bold uppercase tracking-[0.3em] text-sm mb-8">Unbeatable Prices on Quality Hardware
            </p>
            <nav class="flex justify-center items-center gap-2 text-xs font-bold uppercase tracking-widest text-dark-muted">
                <a href="{{ route('home') }}" class="hover:text-gold-400 transition">Home</a>
                <span class="w-1.5 h-1.5 rounded-full bg-gold-400"></span>
                <span class="text-gray-400">Specials</span>
            </nav>
        </div>
    </div>

    <!-- Specials Gallery Section -->
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

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">

                @php
                    $specials = [
                        ['title' => 'Mt Frere Specials', 'img' => asset('images/mtfrere_special.webp'), 'full' => asset('images/mtfrere_special.png')],
                        ['title' => 'Qumbu Specials', 'img' => asset('images/qumbu_special.webp'), 'full' => asset('images/qumbu_special.png')],
                        ['title' => 'Tsolo Specials', 'img' => asset('images/tsolo_special_compressed.webp'), 'full' => asset('images/tsolo_special.png')]
                    ];
                @endphp

                @foreach($specials as $special)
                    <div class="group relative card-dark p-2 rounded-[2.5rem] border-white/5 hover:border-gold-400/30 transition-all duration-500 overflow-hidden cursor-pointer shadow-2xl"
                        @click="open('{{ $special['full'] }}', '{{ $special['title'] }}')">

                        <div class="relative h-[32rem] overflow-hidden rounded-[2.2rem]">
                            <img src="{{ $special['img'] }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                alt="{{ $special['title'] }}">

                            <!-- Overlay -->
                            <div
                                class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center">
                                <div
                                    class="w-16 h-16 rounded-full bg-gold-400 text-dark flex items-center justify-center scale-75 group-hover:scale-100 transition-all duration-500 shadow-2xl">
                                    <i class="fas fa-search-plus text-xl"></i>
                                </div>
                                <span class="mt-4 text-xs font-black uppercase tracking-[0.3em] text-white">View Offer
                                    Details</span>
                            </div>

                            <!-- Price Tag Badge -->
                            <div class="absolute top-6 right-6">
                                <span
                                    class="bg-gold-400 text-dark text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-full shadow-xl rotate-3">Limited
                                    Time</span>
                            </div>
                        </div>

                        <div class="px-8 py-8">
                            <h3 class="text-xl font-bold text-white group-hover:text-gold-400 transition-colors">
                                {{ $special['title'] }}</h3>
                            <p class="text-dark-muted text-[10px] font-black uppercase tracking-widest mt-2">Available at Branch
                                Only</p>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

        <!-- Lightbox -->
        <div x-show="isOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 p-4 md:p-10" style="display: none;"
            @keydown.escape.window="close()">

            <button @click="close()"
                class="absolute top-10 right-10 text-white hover:text-gold-400 transition text-4xl p-4">
                <i class="fas fa-times"></i>
            </button>

            <div class="max-w-4xl w-full h-full flex flex-col items-center justify-center gap-6" @click.away="close()">
                <img :src="activeImg"
                    class="max-h-[85vh] w-auto border border-white/10 shadow-[0_0_100px_rgba(245,197,24,0.15)] rounded-2xl"
                    alt="Special Flyer">
                <p x-text="activeTitle"
                    class="text-gold-400 font-black uppercase tracking-[0.4em] text-sm md:text-lg text-center"></p>
            </div>
        </div>
    </section>

    <!-- Branch Locator CTA -->
    <section class="py-24 bg-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="card-dark rounded-[3rem] p-12 md:p-20 text-center border-gold-400/10 flex flex-col items-center relative overflow-hidden">
                <div class="absolute inset-0 opacity-5 -z-10">
                    <img src="{{ asset('images/asterisk-icon.svg') }}"
                        class="w-full h-full object-contain scale-150 rotate-12" alt="Pattern">
                </div>
                <h2 class="text-3xl md:text-5xl font-black text-white mb-6">Visit Our <span
                        class="gradient-text">Branches</span></h2>
                <p class="text-gray-400 text-lg mb-10 max-w-2xl mx-auto leading-relaxed">Most seasonal specials are
                    exclusive to our physical stores. Find the nearest Jabulani Group location and grab the best deals
                    before they're gone!</p>
                <div class="flex flex-col sm:flex-row gap-6">
                    <a href="{{ route('stores') }}" class="btn-gold px-12">Locate Store</a>
                    <a href="{{ route('contact') }}" class="btn-outline-gold px-12">Contact Sales Team</a>
                </div>
            </div>
        </div>
    </section>

@endsection