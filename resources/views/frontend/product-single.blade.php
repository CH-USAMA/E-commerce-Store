@extends('layouts.frontend')

@section('meta_title', $product->name . ' — Jabulani Group')
@section('meta_description', Str::limit(strip_tags($product->description), 160))
@section('og_type', 'product')
@if($product->image && file_exists(public_path($product->image)))
    @section('og_image', asset($product->image))
@endif

@section('content')

    <!-- Hero Section with Background Accent -->
    <div class="relative pt-10 pb-12 overflow-hidden bg-[#050505]">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-gold-400/5 blur-[150px] -z-10 rounded-full"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <nav class="flex items-center gap-2 text-[9px] font-black uppercase tracking-[0.3em] text-dark-muted mb-8">
                <a href="{{ route('home') }}" class="hover:text-gold-400 transition">Home</a>
                <span class="w-1 h-1 rounded-full bg-gold-400/30"></span>
                <a href="{{ route('products') }}" class="hover:text-gold-400 transition">Procurement</a>
                <span class="w-1 h-1 rounded-full bg-gold-400/30"></span>
                <span class="text-white">{{ $product->name }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 xl:gap-16 items-start" x-data="{ qty: 1, adding: false }">

                <!-- ===== PRODUCT VISUAL ===== -->
                <div class="lg:sticky lg:top-32 group">
                    <div
                        class="relative card-dark p-1.5 rounded-[2.5rem] border-white/5 bg-gradient-to-br from-white/[0.03] to-transparent shadow-2xl overflow-hidden">
                        <div class="relative aspect-square overflow-hidden rounded-[2.2rem] bg-[#0d0d0d]">
                            @php
                                $imgSingSrc = 'images/placeholder.webp';
                                if ($product->image && file_exists(public_path($product->image))) {
                                    $segments = explode('/', $product->image);
                                    $encodedSegments = array_map('rawurlencode', $segments);
                                    $imgSingSrc = implode('/', $encodedSegments);
                                }
                            @endphp
                            <img src="{{ asset($imgSingSrc) }}" alt="{{ $product->name }}"
                                class="w-full h-full object-cover transition-transform duration-[3s] group-hover:scale-110">

                            <div class="absolute top-6 left-6 flex flex-col gap-2">
                                <span
                                    class="bg-black/80 backdrop-blur-xl text-gold-400 text-[8px] font-black px-4 py-2 rounded-full border border-gold-400/20 uppercase tracking-[0.25em]">
                                    {{ $product->category->name }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== PRODUCT DETAILS ===== -->
                <div class="space-y-12 py-4">
                    <!-- Title & Identity -->
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-white mb-4 leading-tight uppercase">
                            {{ $product->name }}
                        </h1>
                        <div class="flex items-center gap-4">
                            <div class="flex gap-1">
                                @for($i = 0; $i < 5; $i++)
                                    <i class="fas fa-star text-gold-400 text-[8px]"></i>
                                @endfor
                            </div>
                            <span
                                class="text-[9px] font-black uppercase tracking-[0.2em] text-dark-muted border-l border-white/10 pl-4">
                                Premium Grade
                            </span>
                        </div>
                    </div>

                    <div
                        class="card-dark p-8 rounded-[2rem] border-white/5 bg-gradient-to-br from-white/[0.03] to-transparent shadow-2xl relative">
                        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-8">
                            <div>
                                <p class="text-[8px] font-bold uppercase tracking-[0.4em] text-gold-400/60 mb-2">Price</p>
                                <div class="flex items-baseline gap-1.5">
                                    <span class="text-xs font-bold text-gold-400/80">R</span>
                                    <span
                                        class="text-3xl font-bold text-white tracking-tight">{{ number_format($product->price, 2) }}</span>
                                    <span
                                        class="text-dark-muted font-bold text-[9px] uppercase tracking-widest border-l border-white/10 pl-2 ml-1">Incl.
                                        VAT</span>
                                </div>
                            </div>

                            <div
                                class="flex items-center gap-3 bg-black/40 border border-white/10 rounded-xl p-1 shadow-inner">
                                <button @click="qty = Math.max(1, qty-1)"
                                    class="w-10 h-10 flex items-center justify-center text-dark-muted hover:text-gold-400 transition-all rounded-lg hover:bg-white/5">
                                    <i class="fas fa-minus text-[10px]"></i>
                                </button>
                                <input type="text" x-model="qty" readonly
                                    class="w-8 bg-transparent text-center text-base font-black text-white focus:outline-none">
                                <button @click="qty = Math.min(999, qty+1)"
                                    class="w-10 h-10 flex items-center justify-center text-dark-muted hover:text-gold-400 transition-all rounded-lg hover:bg-white/5">
                                    <i class="fas fa-plus text-[10px]"></i>
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-8">
                            <button
                                @click="$data.adding = true; fetch('/cart/add', {method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({product_id:{{ $product->id }},quantity:qty})}).then(r=>r.json()).then(data=>{$data.adding=false;window.updateCartBadge(data.cart_count);window.showToast(data.message);})"
                                :disabled="adding"
                                class="btn-gold flex items-center justify-center gap-2 py-4 px-6 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all hover:-translate-y-1 active:scale-95">
                                <i class="fas" :class="adding ? 'fa-spinner fa-spin' : 'fa-shopping-cart'"></i>
                                <span x-text="adding ? 'Processing...' : 'Add to Cart'"></span>
                            </button>

                            <a href="https://wa.me/27660684585?text=Hi, I am interested in {{ urlencode($product->name) }} (R {{ number_format($product->price, 2) }})"
                                target="_blank"
                                class="flex items-center justify-center gap-2 py-4 px-6 rounded-xl text-[10px] font-black uppercase tracking-widest text-white border border-white/10 bg-white/5 hover:bg-white/10 transition-all hover:border-gold-400/30">
                                <i class="fab fa-whatsapp text-base text-green-500"></i> WhatsApp
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @if($product->description)
                            <div class="card-dark p-6 rounded-[2rem] border-white/5 bg-white/[0.01]">
                                <h3
                                    class="text-[9px] font-black uppercase tracking-widest text-gold-400 mb-4 flex items-center gap-2">
                                    <i class="fas fa-list-ul"></i> Detail Specs
                                </h3>
                                <div
                                    class="prose prose-invert prose-xs max-w-none text-gray-400 leading-relaxed line-clamp-[8]">
                                    {!! $product->description !!}
                                </div>
                            </div>
                        @endif

                        <div class="space-y-4">
                            <div
                                class="card-dark p-5 rounded-[1.5rem] border-white/5 bg-white/[0.01] flex items-center gap-4 group hover:bg-white/[0.03] transition-all">
                                <div
                                    class="w-10 h-10 rounded-xl bg-gold-400/10 flex items-center justify-center text-gold-400 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-truck-fast text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="text-[9px] font-black text-white uppercase tracking-widest">Rapid Delivery
                                    </h4>
                                    <p class="text-[8px] text-dark-muted uppercase tracking-tighter mt-0.5">Across Transkei
                                    </p>
                                </div>
                            </div>
                            <div
                                class="card-dark p-5 rounded-[1.5rem] border-white/5 bg-white/[0.01] flex items-center gap-4 group hover:bg-white/[0.03] transition-all">
                                <div
                                    class="w-10 h-10 rounded-xl bg-gold-400/10 flex items-center justify-center text-gold-400 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-check-double text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="text-[9px] font-black text-white uppercase tracking-widest">SABS Quality</h4>
                                    <p class="text-[8px] text-dark-muted uppercase tracking-tighter mt-0.5">Verified
                                        Standards</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection