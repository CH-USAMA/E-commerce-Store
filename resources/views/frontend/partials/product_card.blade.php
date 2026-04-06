<div
    class="group relative bg-[#0d0d0d] rounded-[2.5rem] border border-white/5 hover:border-gold-400/20 transition-all duration-500 overflow-hidden">
    {{-- Price Badge --}}
    <div class="absolute top-6 left-6 z-20">
        <span class="bg-gold-400 text-dark font-black px-4 py-2 rounded-2xl text-xs">
            R {{ number_format($product->price, 2) }}
        </span>
    </div>

    {{-- Image --}}
    <div class="relative aspect-square overflow-hidden bg-dark-card">
        @php
            // The file name might contain special characters like '+'. Browser interprets '+' as space.
            // We need to encode the path segments correctly for the src attribute.
            $imageSrc = 'images/placeholder.webp';
            if ($product->image && file_exists(public_path($product->image))) {
                // Split by '/' and encode each segment, then join back
                $segments = explode('/', $product->image);
                $encodedSegments = array_map('rawurlencode', $segments);
                $imagePath = implode('/', $encodedSegments);
                $imageSrc = $imagePath;
            }
        @endphp
        <img src="{{ asset($imageSrc) }}"
            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
            alt="{{ $product->name }}">

        {{-- Quick Actions --}}
        <div
            class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <button onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})"
                class="bg-gold-400 text-dark font-black px-8 py-3.5 rounded-2xl text-[11px] uppercase tracking-widest hover:bg-white transition transform translate-y-4 group-hover:translate-y-0 duration-500 shadow-xl flex items-center gap-2">
                <i class="fas fa-cart-plus text-sm"></i> Add to Cart
            </button>
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