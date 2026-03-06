<div
    class="group relative bg-[#0d0d0d] rounded-[2.5rem] border border-white/5 hover:border-gold-400/20 transition-all duration-500 overflow-hidden">
    {{-- Price Badge --}}
    <div class="absolute top-6 left-6 z-20">
        <span class="bg-gold-400 text-dark font-black px-4 py-2 rounded-2xl text-xs">
            R {{ number_format($product->price, 2) }}
        </span>
    </div>

    {{-- Image --}}
    <div class="relative aspect-square overflow-hidden">
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
            <div
                class="flex items-center gap-3 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                <button
                    onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})"
                    class="w-12 h-12 bg-gold-400 rounded-2xl flex items-center justify-center text-dark hover:bg-white transition">
                    <i class="fas fa-cart-plus"></i>
                </button>
                <a href="{{ route('product.detail', $product->slug) }}"
                    class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-white hover:bg-gold-400 hover:text-dark transition">
                    <i class="fas fa-eye"></i>
                </a>
            </div>
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