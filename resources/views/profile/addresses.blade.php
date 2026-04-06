@extends('layouts.frontend')

@section('title', 'Manage Addresses - Jabulani Group')

@section('content')
    <!-- Page Header -->
    <div class="relative pt-32 pb-16 overflow-hidden bg-dark">
        <div class="absolute top-0 left-0 w-1/3 h-full bg-gold-400/5 blur-[150px] -z-10 rounded-full"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center lg:text-left">
            <h1 class="text-4xl lg:text-6xl font-black mb-4 tracking-tighter italic text-white uppercase">
                My <span class="gradient-text">Addresses</span>
            </h1>
            <nav
                class="flex justify-center lg:justify-start items-center gap-3 text-[9px] font-black uppercase tracking-[0.3em] text-dark-muted">
                <a href="{{ route('home') }}" class="hover:text-gold-400 transition">Home</a>
                <span class="w-1 h-1 rounded-full bg-gold-400/30"></span>
                <a href="{{ route('user.dashboard') }}" class="hover:text-gold-400 transition">Dashboard</a>
                <span class="w-1 h-1 rounded-full bg-gold-400/30"></span>
                <span class="text-white">Saved Addresses</span>
            </nav>
        </div>
    </div>

    <div class="bg-[#050505] min-h-screen py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

                <!-- Saved Addresses -->
                <div class="lg:col-span-2 space-y-8">
                    @forelse($addresses as $address)
                        <div
                            class="card-dark rounded-[3rem] p-8 border-white/5 bg-gradient-to-br from-white/[0.03] to-transparent hover:border-gold-400/20 transition-all duration-700 relative group overflow-hidden">
                            @if($address->is_default)
                                <div class="absolute top-0 right-0">
                                    <div
                                        class="bg-gold-400 text-dark text-[8px] font-black uppercase tracking-widest px-6 py-2 rounded-bl-3xl italic">
                                        Default Address
                                    </div>
                                </div>
                            @endif

                            <div class="flex flex-col sm:flex-row justify-between items-start gap-8 relative z-10">
                                <div class="flex-1">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-gold-400/10 flex items-center justify-center text-gold-400">
                                            <i class="fas fa-home"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-black text-white uppercase italic tracking-tight">
                                                {{ $address->address_name }}</h3>
                                            <p class="text-[10px] text-dark-muted uppercase font-bold tracking-widest">Saved
                                                Delivery Location</p>
                                        </div>
                                    </div>

                                    <div class="text-sm text-dark-muted space-y-1 mb-8">
                                        <p class="text-white font-bold">{{ $address->address_line_1 }}</p>
                                        @if($address->address_line_2)
                                        <p>{{ $address->address_line_2 }}</p>@endif
                                        <p>{{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                                    </div>

                                    <div class="flex items-center gap-6">
                                        @if(!$address->is_default)
                                            <form action="{{ route('profile.addresses.default', $address->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="text-[9px] font-black uppercase tracking-widest text-gold-400 hover:text-white transition">
                                                    Make Default
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('profile.addresses.delete', $address->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to remove this address profile?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-[9px] font-black uppercase tracking-widest text-red-400/50 hover:text-red-400 transition">
                                                Remove Profile
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div
                            class="flex flex-col items-center justify-center py-20 text-center card-dark rounded-[4rem] border-white/5 opacity-50">
                            <i class="fas fa-map-marked-alt text-4xl mb-6 text-dark-muted"></i>
                            <p class="text-sm text-dark-muted uppercase font-black tracking-widest">No Addresses Saved</p>
                        </div>
                    @endforelse
                </div>

                <!-- Add New Address Form -->
                <div class="lg:sticky lg:top-32">
                    <div
                        class="card-dark rounded-[3.5rem] p-12 border-white/5 bg-gradient-to-br from-white/[0.04] to-transparent shadow-2xl">
                        <h3 class="text-2xl font-black text-white italic mb-10 tracking-tight uppercase">Add <span
                                class="gradient-text">New Address</span></h3>

                        <form action="{{ route('profile.addresses.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <div>
                                <label
                                    class="block text-[9px] font-black uppercase tracking-widest text-dark-muted mb-2">Address Name</label>
                                <input type="text" name="address_name" placeholder="e.g. Home, Office" required
                                    class="w-full bg-black/40 border border-white/10 rounded-2xl py-4 px-6 text-white text-sm focus:border-gold-400/50 transition-all outline-none">
                            </div>

                            <div>
                                <label
                                    class="block text-[9px] font-black uppercase tracking-widest text-dark-muted mb-2">Street
                                    Address</label>
                                <input type="text" name="address_line_1" placeholder="Plot/Street" required
                                    class="w-full bg-black/40 border border-white/10 rounded-2xl py-4 px-6 text-white text-sm focus:border-gold-400/50 transition-all outline-none">
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label
                                        class="block text-[9px] font-black uppercase tracking-widest text-dark-muted mb-2">City</label>
                                    <input type="text" name="city" required
                                        class="w-full bg-black/40 border border-white/10 rounded-2xl py-4 px-6 text-white text-sm focus:border-gold-400/50 transition-all outline-none">
                                </div>
                                <div>
                                    <label
                                        class="block text-[9px] font-black uppercase tracking-widest text-dark-muted mb-2">Province</label>
                                    <input type="text" name="province" required
                                        class="w-full bg-black/40 border border-white/10 rounded-2xl py-4 px-6 text-white text-sm focus:border-gold-400/50 transition-all outline-none">
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-[9px] font-black uppercase tracking-widest text-dark-muted mb-2">Postal
                                    Code</label>
                                <input type="text" name="postal_code" required
                                    class="w-full bg-black/40 border border-white/10 rounded-2xl py-4 px-6 text-white text-sm focus:border-gold-400/50 transition-all outline-none">
                            </div>

                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="is_default" value="1" class="hidden peer">
                                <div
                                    class="w-5 h-5 rounded-md border border-white/10 flex items-center justify-center peer-checked:bg-gold-400 peer-checked:border-gold-400 transition-all">
                                    <i class="fas fa-check text-[8px] text-dark opacity-0 peer-checked:opacity-100"></i>
                                </div>
                                <span
                                    class="text-[9px] font-black uppercase tracking-widest text-dark-muted group-hover:text-white transition">Set
                                    as default shipping address</span>
                            </label>

                            <button type="submit"
                                class="btn-gold w-full py-5 text-[10px] font-black uppercase tracking-[0.3em] rounded-2xl group">
                                Save Address <i class="fas fa-plus ml-2 group-hover:rotate-90 transition-transform"></i>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection