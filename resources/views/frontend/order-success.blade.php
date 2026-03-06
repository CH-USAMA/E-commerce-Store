@extends('layouts.frontend')

@section('title', 'Order Confirmed - Jabulani Group')

@section('content')
    <div class="relative min-h-[85vh] flex items-center justify-center px-4 py-24 overflow-hidden bg-dark">
        <!-- Success Aura Background -->
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gold-400/5 blur-[150px] rounded-full pointer-events-none">
        </div>

        <div class="max-w-3xl w-full relative z-10">
            <div
                class="card-dark rounded-[3.5rem] p-12 md:p-20 border-gold-400/20 text-center relative overflow-hidden bg-gradient-to-b from-white/[0.03] to-transparent shadow-2xl">

                <!-- Animated Success Icon -->
                <div
                    class="inline-flex items-center justify-center w-28 h-28 rounded-full bg-gold-400 text-dark mb-12 shadow-[0_0_80px_rgba(245,197,24,0.3)] animate-pulse">
                    <i class="fas fa-check text-4xl"></i>
                </div>

                <h1 class="text-4xl md:text-6xl font-black text-white mb-6 leading-tight tracking-tight">Order <span
                        class="gradient-text">Confirmed!</span></h1>

                <div class="inline-block bg-white/5 border border-white/10 rounded-2xl px-8 py-4 mb-10">
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-dark-muted mb-1">Confirmation Number
                    </p>
                    <p class="text-3xl font-black text-gold-400 tracking-tighter">#{{ $orderNumber }}</p>
                </div>

                <div class="space-y-6 text-gray-400 text-lg leading-relaxed mb-12 max-w-xl mx-auto font-light">
                    <p>Thank you for choosing <span class="text-white font-bold">Jabulani Group</span>. We've received your
                        order and our processing team is already in motion.</p>
                    <p class="text-sm">A branch manager will contact you shortly to coordinate the delivery logistics or
                        collection timing.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="{{ route('home') }}"
                        class="btn-outline-gold py-5 rounded-full text-xs font-black uppercase tracking-widest border-white/10 hover:border-gold-400 transition-all flex items-center justify-center gap-3">
                        <i class="fas fa-home"></i> Return Home
                    </a>
                    <a href="{{ route('products') }}"
                        class="btn-gold py-5 rounded-full text-xs font-black uppercase tracking-widest flex items-center justify-center gap-3">
                        Continue Shopping <i class="fas fa-shopping-bag"></i>
                    </a>
                </div>

                <!-- Support Footer -->
                <div class="mt-16 pt-10 border-t border-white/5 flex flex-col items-center">
                    <p class="text-[10px] font-black uppercase tracking-widest text-dark-muted mb-4 italic">Need to make a
                        change?</p>
                    <div class="flex gap-6">
                        <a href="{{ route('contact') }}"
                            class="text-xs font-bold text-gray-400 hover:text-gold-400 transition underline underline-offset-8 decoration-gold-400/30">Contact
                            Support</a>
                        <span class="text-white/10">|</span>
                        <a href="{{ route('order.track') }}"
                            class="text-xs font-bold text-gray-400 hover:text-gold-400 transition underline underline-offset-8 decoration-gold-400/30">Track
                            Live Status</a>
                    </div>
                </div>

            </div>

            <!-- Floating Elements -->
            <div class="absolute -top-10 -left-10 w-24 h-24 bg-white/5 rounded-3xl rotate-12 blur-lg"></div>
            <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-white/5 rounded-full rotate-45 blur-xl"></div>
        </div>
    </div>
@endsection