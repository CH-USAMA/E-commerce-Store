@extends('layouts.frontend')

@section('title', 'Testimonials - Jabulani Group')

@section('content')

    <!-- Page Header -->
    <div class="relative py-24 overflow-hidden bg-dark">
        <div class="absolute inset-0 opacity-10">
            <img src="{{ asset('images/JABULANI_Fleet.webp') }}" class="w-full h-full object-cover" alt="Testimonials Hero">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-5xl lg:text-7xl font-black mb-6 tracking-tight">Client <span class="gradient-text">Voices</span>
            </h1>
            <p class="text-gold-400 font-bold uppercase tracking-[0.3em] text-sm mb-8">What Our Partners Say About Us</p>
            <nav class="flex justify-center items-center gap-2 text-xs font-bold uppercase tracking-widest text-dark-muted">
                <a href="{{ route('home') }}" class="hover:text-gold-400 transition">Home</a>
                <span class="w-1.5 h-1.5 rounded-full bg-gold-400"></span>
                <span class="text-gray-400">Testimonials</span>
            </nav>
        </div>
    </div>

    <!-- Testimonials Section -->
    <section class="py-24 bg-[#0a0a0a]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="space-y-12">

                @php
                    $testimonials = [
                        [
                            'name' => 'Saleem Nazar',
                            'role' => 'CEO Cingimiso Construction',
                            'image' => asset('images/customer-3.JPG'),
                            'logo' => asset('images/HMART.JPG'),
                            'text' => 'Jabulani Group of Companies has been our go-to supplier for SABS-approved blocks and aluminium products. Their quality is unmatched, and their reliable deliveries keep our projects on schedule. Thanks to their competitive prices and exceptional service, we’ve saved costs without compromising on durability!'
                        ],
                        [
                            'name' => 'Aya Bulila',
                            'role' => 'Cashier Shoprite',
                            'image' => asset('images/customer-1.png'),
                            'logo' => null,
                            'text' => 'Jabulani Group of Companies is my first choice for all my building materials. Their products are high quality, their prices are fair, and their service is always reliable.'
                        ]
                    ];
                @endphp

                @foreach($testimonials as $index => $t)
                    <div
                        class="card-dark p-8 md:p-16 rounded-[3rem] border-white/5 relative overflow-hidden group hover:border-gold-400/30 transition-all duration-700">
                        <div
                            class="absolute top-0 right-0 p-12 opacity-5 scale-150 text-gold-400 group-hover:scale-[2] transition-transform duration-700">
                            <i class="fas fa-quote-right text-9xl"></i>
                        </div>

                        <div class="flex flex-col lg:flex-row items-center gap-12 relative z-10">
                            <div
                                class="w-48 h-48 md:w-64 md:h-64 flex-shrink-0 rounded-[2.5rem] overflow-hidden border-4 border-gold-400/20 shadow-2xl">
                                <img src="{{ $t['image'] }}" class="w-full h-full object-cover" alt="{{ $t['name'] }}">
                            </div>

                            <div class="flex-1 text-center lg:text-left">
                                <div class="flex justify-center lg:justify-start gap-1 mb-6">
                                    @for($i = 0; $i < 5; $i++)
                                        <i class="fas fa-star text-gold-400 text-sm"></i>
                                    @endfor
                                </div>

                                <blockquote class="text-xl md:text-2xl font-medium text-gray-300 leading-relaxed mb-10 italic">
                                    "{{ $t['text'] }}"
                                </blockquote>

                                <div
                                    class="flex flex-col md:flex-row items-center justify-between gap-6 pt-8 border-t border-white/5">
                                    <div>
                                        <h3 class="text-2xl font-black text-white mb-1">{{ $t['name'] }}</h3>
                                        <p class="text-gold-400 text-xs font-black uppercase tracking-widest">{{ $t['role'] }}
                                        </p>
                                    </div>
                                    @if($t['logo'])
                                        <div class="h-12 opacity-50 hover:opacity-100 transition grayscale hover:grayscale-0">
                                            <img src="{{ $t['logo'] }}" class="h-full object-contain" alt="Partner Logo">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>

    <!-- Review Invite -->
    <section class="py-24 bg-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-dark rounded-[3rem] p-12 md:p-20 text-center border-gold-400/10">
                <h2 class="text-4xl font-black text-white mb-6">Work <span class="gradient-text">With Us?</span></h2>
                <p class="text-gray-400 text-lg mb-10 max-w-2xl mx-auto">We value every partnership. If you've had a great
                    experience with Jabulani Group, we'd love to hear from you.</p>
                <div class="flex justify-center items-center gap-8">
                    <a href="{{ route('contact') }}" class="btn-gold px-12">Leave a Feedback</a>
                </div>
            </div>
        </div>
    </section>

@endsection