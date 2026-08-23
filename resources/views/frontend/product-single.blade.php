@extends('layouts.frontend')

@section('meta_title', $product->name . ' — Jabulani Group | Hardware & Building Materials SA')
@section('meta_description', Str::limit(strip_tags($product->description ?? 'Buy ' . $product->name . ' from Jabulani Group. Quality hardware and building materials in South Africa.'), 160))
@section('meta_keywords', $product->name . ', building materials, hardware, South Africa, ' . optional($product->category)->name . ', ' . optional($product->brand)->name . ', buy online')
@section('og_type', 'product')
@if($product->image && image_path($product->image, null))
    @section('og_image', image_url($product->image))
@endif

@push('seo')
<script type="application/ld+json">
{
    "@@context": "https://schema.org/",
    "@@type": "Product",
    "name": "{{ $product->name }}",
    "description": "{{ Str::limit(strip_tags($product->description ?? ''), 500) }}",
    @if($product->sku)
    "sku": "{{ $product->sku }}",
    @endif
    @if($product->image && image_path($product->image, null))
    "image": "{{ image_url($product->image) }}",
    @endif
    "brand": {
        "@@type": "Brand",
        "name": "{{ optional($product->brand)->name ?? 'Jabulani Group' }}"
    },
    "category": "{{ optional($product->category)->name ?? 'Hardware' }}",
    "offers": {
        "@@type": "Offer",
        "url": "{{ route('product.detail', $product->slug) }}",
        "priceCurrency": "ZAR",
        "price": "{{ number_format($product->display_price, 2, '.', '') }}",
        "priceValidUntil": "{{ now()->addYear()->format('Y-m-d') }}",
        "availability": "https://schema.org/InStock",
        "itemCondition": "https://schema.org/NewCondition",
        "seller": {
            "@@type": "Organization",
            "name": "Jabulani Group of Companies",
            "url": "{{ config('app.url') }}"
        }
    }
}
</script>
@endpush


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

            {{-- Hoisted above the Alpine root on purpose: the x-data getter below builds
                 the WhatsApp enquiry, so it needs $waBase and $hidePricing in scope. --}}
            @php
                $hidePricing = ($settings['hide_pricing'] ?? '0') == '1';
                $waPhone = preg_replace('/[^0-9]/', '', $settings['invoice_company_phone'] ?? '27660684585');
                $waMessage = $hidePricing
                    ? "Hi, I'm interested in {$product->name}"
                    : "Hi, I am interested in {$product->name} (R " . number_format($product->display_price, 2) . ")";
                $waLink = "https://wa.me/{$waPhone}?text=" . urlencode($waMessage);
                $waBase = "https://wa.me/{$waPhone}?text=";
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 xl:gap-16 items-start" x-data="{
                qty: 1,
                adding: false,
                sizes: @js($product->offersVariants() ? $product->activeVariants->map(fn ($v) => ['id' => $v->id, 'label' => $v->label, 'price' => (float) $v->price])->values() : []),
                variantId: @js($product->offersVariants() ? $product->activeVariants->first()->id : null),
                get size() { return this.sizes.find(s => s.id === this.variantId) ?? null },
                get unitPrice() { return this.size ? this.size.price : {{ (float) $product->price }} },
                money(v) { return v.toLocaleString('en-ZA', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) },
                get waUrl() {
                    let msg = @js("Hi, I'm interested in {$product->name}");
                    if (this.size) { msg += ' - size ' + this.size.label; }
                    @if(!$hidePricing)
                        msg += ' (R ' + this.money(this.unitPrice) + ')';
                    @endif
                    return @js($waBase) + encodeURIComponent(msg);
                },
             }">

                <!-- ===== PRODUCT VISUAL ===== -->
                <div class="lg:sticky lg:top-32 group">
                    <div
                        class="relative card-dark p-1.5 rounded-[2.5rem] border-white/5 bg-gradient-to-br from-white/[0.03] to-transparent shadow-2xl overflow-hidden">
                        <div class="relative aspect-square overflow-hidden rounded-[2.2rem] bg-[#0d0d0d]">
                            <img src="{{ image_url($product->image) }}" alt="{{ $product->name }}"
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
                        @if($product->offersVariants())
                            <div class="mb-8">
                                <p class="text-[8px] font-bold uppercase tracking-[0.4em] text-gold-400/60 mb-3">
                                    Select Size
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="s in sizes" :key="s.id">
                                        <button type="button" @click="variantId = s.id"
                                            class="px-5 py-3 rounded-xl text-xs font-black uppercase tracking-widest border transition-all duration-300"
                                            :class="variantId === s.id
                                                ? 'bg-gold-400 text-dark border-gold-400 shadow-lg'
                                                : 'text-gray-300 border-white/10 bg-white/5 hover:border-gold-400/40 hover:text-white'">
                                            <span x-text="s.label"></span>
                                            @if(!$hidePricing)
                                                <span class="block text-[9px] font-bold normal-case tracking-normal opacity-70 mt-0.5"
                                                    x-text="'R ' + money(s.price)"></span>
                                            @endif
                                        </button>
                                    </template>
                                </div>
                            </div>
                        @endif

                        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-8">
                            @if(!$hidePricing)
                                <div>
                                    <p class="text-[8px] font-bold uppercase tracking-[0.4em] text-gold-400/60 mb-2">Price</p>
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="text-xs font-bold text-gold-400/80">R</span>
                                        <span class="text-3xl font-bold text-white tracking-tight"
                                            x-text="money(unitPrice)">{{ number_format($product->display_price, 2) }}</span>
                                        <span
                                            class="text-dark-muted font-bold text-[9px] uppercase tracking-widest border-l border-white/10 pl-2 ml-1">Incl.
                                            VAT excluded</span>
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
                            @else
                                <div>
                                    <p class="text-[8px] font-bold uppercase tracking-[0.4em] text-gold-400/60 mb-2">Pricing</p>
                                    <p class="text-xl font-bold text-white tracking-tight">Contact us for a quote</p>
                                </div>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 {{ $hidePricing ? '' : 'sm:grid-cols-2' }} gap-3 mt-8">
                            @if(!$hidePricing)
                                <button
                                    @click="$data.adding = true; fetch('/cart/add', {method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({product_id:{{ $product->id }},variant_id:variantId,quantity:qty})}).then(r=>r.json()).then(data=>{$data.adding=false;window.updateCartBadge(data.cart_count);window.showToast(data.message);})"
                                    :disabled="adding"
                                    class="btn-gold flex items-center justify-center gap-2 py-4 px-6 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all hover:-translate-y-1 active:scale-95">
                                    <i class="fas" :class="adding ? 'fa-spinner fa-spin' : 'fa-shopping-cart'"></i>
                                    <span x-text="adding ? 'Processing...' : 'Add to Cart'"></span>
                                </button>
                            @endif

                            <a href="{{ $waLink }}"
                                @if($product->offersVariants())
                                    :href="waUrl"
                                @endif
                                target="_blank"
                                class="flex items-center justify-center gap-2 py-4 px-6 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all hover:border-gold-400/30 {{ $hidePricing ? 'btn-gold' : 'text-white border border-white/10 bg-white/5 hover:bg-white/10' }}">
                                <i class="fab fa-whatsapp text-base {{ $hidePricing ? '' : 'text-green-500' }}"></i> {{ $hidePricing ? 'Contact Us' : 'WhatsApp' }}
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

    {{-- Recently viewed — excludes the product being viewed right now. --}}
    @include('frontend.partials.recently_viewed', ['excludeId' => $product->id])

@endsection