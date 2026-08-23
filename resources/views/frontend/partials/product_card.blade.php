<div
    class="group relative bg-[#0d0d0d] rounded-[2.5rem] border border-white/5 hover:border-gold-400/20 transition-all duration-500 overflow-hidden">
    {{-- Price Badge --}}
    @if(($settings['hide_pricing'] ?? '0') != '1')
        <div class="absolute top-6 left-6 z-20">
            <span class="bg-gold-400 text-dark font-black px-4 py-2 rounded-2xl text-xs">
                {{-- With sizes there is no single price to print; lead with the
                     cheapest and say so, rather than quoting one size as the price. --}}
                @if($product->hasPriceRange())From @endif R {{ number_format($product->display_price, 2) }}
            </span>
        </div>
    @endif

    {{-- Image --}}
    <div class="relative aspect-square overflow-hidden bg-dark-card">
        {{-- image_url() handles the per-segment encoding ('+' would otherwise be read as a
             space) and the placeholder fallback. --}}
        <img src="{{ image_url($product->image) }}"
            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
            alt="{{ $product->name }}">

        {{-- Quick Actions --}}
        <div
            class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            @include('frontend.partials.price_or_contact', ['product' => $product])
        </div>
    </div>

    {{-- Info --}}
    <div class="p-8">
        <span class="text-[10px] font-black uppercase tracking-widest text-gold-400/60 mb-2 block">
            {{ $product->subcategory?->name ?? $product->category?->name ?? 'Uncategorized' }}
        </span>
        <h3 class="text-white font-bold text-lg mb-4 line-clamp-1 group-hover:text-gold-400 transition">
            <a href="{{ route('product.detail', $product->slug) }}">{{ $product->name }}</a>
        </h3>
        <div class="flex items-center justify-between pt-4 border-t border-white/5">
            <span class="text-[10px] font-black text-white/30 uppercase tracking-widest">In Stock</span>
            <a href="{{ route('product.detail', $product->slug) }}"
                class="text-[10px] font-black uppercase tracking-widest text-white hover:text-gold-400 transition">
                View Details
            </a>
        </div>
    </div>
</div>