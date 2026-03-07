@extends('layouts.frontend')

@section('title', 'Complete Your Profile - Jabulani Group')

@section('content')
    <div class="min-h-screen bg-dark pt-40 pb-20 relative overflow-hidden flex items-center">
        <!-- Background Accents -->
        <div class="absolute top-0 left-0 w-1/3 h-full bg-gold-400/5 blur-[150px] -z-10 rounded-full"></div>
        <div class="absolute bottom-0 right-0 w-1/3 h-full bg-gold-400/5 blur-[150px] -z-10 rounded-full"></div>

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="text-center mb-12">
                <span class="text-[10px] font-black uppercase tracking-[0.6em] text-gold-400 mb-4 block">First-Time
                    Setup</span>
                <h1 class="text-4xl lg:text-6xl font-black text-white italic uppercase tracking-tighter mb-4">
                    Complete <span class="gradient-text">Your Profile</span>
                </h1>
                <p class="text-dark-muted max-w-md mx-auto leading-relaxed text-sm">
                    Just a few more details to ensure a seamless experience and professional delivery service.
                </p>
            </div>

            <div
                class="card-dark rounded-[3rem] p-8 md:p-12 border-white/5 bg-gradient-to-br from-white/[0.04] to-transparent shadow-2xl">
                <form action="{{ route('profile.complete.store') }}" method="POST" class="space-y-8">
                    @csrf

                    <!-- Basic Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-dark-muted mb-3 italic">Mobile
                                Number</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                    <i
                                        class="fas fa-phone text-gold-400/50 group-focus-within:text-gold-400 transition-colors"></i>
                                </div>
                                <input type="text" name="phone" value="{{ old('phone') }}" required
                                    placeholder="e.g. +27 123 456 789"
                                    class="w-full bg-black/40 border border-white/10 rounded-2xl py-4 pl-12 pr-6 text-white placeholder-white/20 focus:outline-none focus:border-gold-400/50 focus:ring-1 focus:ring-gold-400/50 transition-all">
                            </div>
                            @error('phone') <p class="mt-2 text-[10px] text-red-400 uppercase font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-dark-muted mb-3 italic">Address
                                Label</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                    <i
                                        class="fas fa-tag text-gold-400/50 group-focus-within:text-gold-400 transition-colors"></i>
                                </div>
                                <input type="text" name="address_name" value="{{ old('address_name', 'Home') }}" required
                                    placeholder="e.g. Home, Office, Warehouse"
                                    class="w-full bg-black/40 border border-white/10 rounded-2xl py-4 pl-12 pr-6 text-white placeholder-white/20 focus:outline-none focus:border-gold-400/50 focus:ring-1 focus:ring-gold-400/50 transition-all">
                            </div>
                            @error('address_name') <p class="mt-2 text-[10px] text-red-400 uppercase font-bold">
                            {{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Address Lines -->
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-dark-muted mb-3 italic">Street
                            Address</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <i
                                    class="fas fa-map-marker-alt text-gold-400/50 group-focus-within:text-gold-400 transition-colors"></i>
                            </div>
                            <input type="text" name="address_line_1" value="{{ old('address_line_1') }}" required
                                placeholder="Plot number, Street name"
                                class="w-full bg-black/40 border border-white/10 rounded-2xl py-4 pl-12 pr-6 text-white placeholder-white/20 focus:outline-none focus:border-gold-400/50 focus:ring-1 focus:ring-gold-400/50 transition-all">
                        </div>
                        @error('address_line_1') <p class="mt-2 text-[10px] text-red-400 uppercase font-bold">{{ $message }}
                        </p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-dark-muted mb-3 italic">City</label>
                            <input type="text" name="city" value="{{ old('city') }}" required
                                class="w-full bg-black/40 border border-white/10 rounded-2xl py-4 px-6 text-white placeholder-white/20 focus:outline-none focus:border-gold-400/50 transition-all">
                            @error('city') <p class="mt-2 text-[10px] text-red-400 uppercase font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-dark-muted mb-3 italic">Province</label>
                            <input type="text" name="province" value="{{ old('province') }}" required
                                class="w-full bg-black/40 border border-white/10 rounded-2xl py-4 px-6 text-white placeholder-white/20 focus:outline-none focus:border-gold-400/50 transition-all">
                            @error('province') <p class="mt-2 text-[10px] text-red-400 uppercase font-bold">{{ $message }}
                            </p> @enderror
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-dark-muted mb-3 italic">Postal
                                Code</label>
                            <input type="text" name="postal_code" value="{{ old('postal_code') }}" required
                                class="w-full bg-black/40 border border-white/10 rounded-2xl py-4 px-6 text-white placeholder-white/20 focus:outline-none focus:border-gold-400/50 transition-all">
                            @error('postal_code') <p class="mt-2 text-[10px] text-red-400 uppercase font-bold">
                            {{ $message }}</p> @enderror
                        </div>
                    </div>

                    <button type="submit"
                        class="btn-gold w-full flex items-center justify-center gap-4 py-6 text-[12px] font-black uppercase tracking-[0.4em] rounded-[1.5rem] shadow-[0_25px_50px_rgba(255,229,7,0.2)] group hover:-translate-y-1 transition-all">
                        Secure Account <i class="fas fa-shield-alt group-hover:scale-110 transition-transform"></i>
                    </button>
                </form>
            </div>

            <div class="mt-8 text-center">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="text-[10px] font-black uppercase tracking-widest text-dark-muted hover:text-white transition">
                        Wait, log me out <i class="fas fa-sign-out-alt ml-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection