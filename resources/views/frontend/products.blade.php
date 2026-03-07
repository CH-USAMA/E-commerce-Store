@extends('layouts.frontend')

@section('title', 'Hardware Catalog - Jabulani Group')

@section('content')

    {{-- Page Header --}}
    <div class="relative pt-10 pb-24 overflow-hidden bg-dark">
        <div class="absolute inset-0 opacity-10">
            <img src="{{ asset('images/quarry_truck.webp') }}" class="w-full h-full object-cover" alt="Products Hero">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-6xl lg:text-7xl font-black mb-6 tracking-tight uppercase">Our <span class="gradient-text">Catalog</span></h1>
            <p class="text-gold-400 font-bold uppercase tracking-[0.6em] text-[10px] mb-8">Premium Materials For Every Project</p>
            <nav class="flex justify-center items-center gap-2 text-[10px] font-black uppercase tracking-widest text-dark-muted">
                <a href="{{ route('home') }}" class="hover:text-gold-400 transition">Home</a>
                <span class="w-1 h-1 rounded-full bg-gold-400/50"></span>
                @if($activeCategory)
                    <a href="{{ route('products') }}" class="hover:text-gold-400 transition">Products</a>
                    <span class="w-1 h-1 rounded-full bg-gold-400/50"></span>
                    <span class="text-gray-400">{{ $activeCategory->name }}</span>
                @else
                    <span class="text-gray-400">Products</span>
                @endif
            </nav>
        </div>
    </div>

    <div class="bg-[#050505] min-h-screen py-16" x-data="{ search: '{{ request('search', '') }}', sidebarOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-12">

                {{-- ===== SIDEBAR ===== --}}
                <aside class="w-full lg:w-72 flex-shrink-0">
                    {{-- Mobile toggle --}}
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden w-full flex items-center justify-between bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-white mb-6">
                        <span><i class="fas fa-sliders-h mr-3 text-gold-400"></i>Refine Search</span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-300" :class="sidebarOpen ? 'rotate-180' : ''"></i>
                    </button>

                    <div :class="sidebarOpen ? 'block' : 'hidden'" class="lg:block space-y-8 sticky top-24">
                        {{-- Search Box --}}
                        <form action="{{ route('products') }}" method="GET" id="filterForm">
                            <div class="card-dark p-6 rounded-[2rem] border-white/5">
                                <h3 class="text-[10px] font-black uppercase tracking-widest text-dark-muted mb-6">Keyword Search</h3>
                                <div class="relative">
                                    <input type="text" name="search" x-model="search" placeholder="Search products, SKU..."
                                        value="{{ $search ?? '' }}"
                                        class="w-full bg-[#0a0a0a] border border-white/5 rounded-xl px-5 py-4 text-sm text-gray-200 placeholder-dark-muted focus:outline-none focus:border-gold-400/50 transition">
                                    <button type="submit" class="absolute right-4 top-4 text-dark-muted hover:text-gold-400">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Sort Options --}}
                            <div class="card-dark p-6 rounded-[2rem] border-white/5 mt-6">
                                <h3 class="text-[10px] font-black uppercase tracking-widest text-dark-muted mb-4">Sort By</h3>
                                <select name="sort" onchange="this.form.submit()" class="w-full bg-[#0a0a0a] border border-white/10 rounded-xl px-4 py-3 text-sm text-gray-300 focus:outline-none">
                                    <option value="latest" {{ ($sort ?? 'latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                                    <option value="price_asc" {{ ($sort ?? '') == 'price_asc' ? 'selected' : '' }}>Price: Low → High</option>
                                    <option value="price_desc" {{ ($sort ?? '') == 'price_desc' ? 'selected' : '' }}>Price: High → Low</option>
                                    <option value="name" {{ ($sort ?? '') == 'name' ? 'selected' : '' }}>Name A–Z</option>
                                </select>
                            </div>
                        </form>

                        {{-- Category Hierarchy Sidebar --}}
                        <div class="card-dark p-6 rounded-[2rem] border-white/5">
                            <h3 class="text-[10px] font-black uppercase tracking-widest text-dark-muted mb-6">Categories</h3>
                            <div class="space-y-1">
                                <a href="{{ route('products') }}"
                                    class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-bold border transition-all duration-300 {{ !$activeCategory ? 'bg-gold-400 text-dark border-gold-400' : 'text-gray-400 border-transparent hover:bg-white/5' }}">
                                    <span>All Products</span>
                                    <i class="fas fa-layer-group text-xs opacity-50"></i>
                                </a>
                                @foreach($topCategories as $topCat)
                                    {{-- Top-level category --}}
                                    <a href="{{ route('products', ['category' => $topCat->slug]) }}"
                                        class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-bold border transition-all duration-300 {{ ($activeCategory?->id == $topCat->id) ? 'bg-gold-400 text-dark border-gold-400' : 'text-gray-400 border-transparent hover:bg-white/5' }}">
                                        <span class="truncate pr-2">{{ $topCat->name }}</span>
                                        <i class="fas fa-chevron-right text-[10px] opacity-40"></i>
                                    </a>

                                    {{-- Sub-categories (shown when parent is active) --}}
                                    @if($activeCategory?->id == $topCat->id || $activeSubcategory?->parent_id == $topCat->id)
                                        @foreach($topCat->children as $child)
                                            <a href="{{ route('products', ['category' => $topCat->slug, 'subcategory' => $child->slug]) }}"
                                                class="flex items-center gap-2 ml-4 px-4 py-2 rounded-xl text-xs font-semibold border transition-all duration-300 {{ ($activeSubcategory?->id == $child->id) ? 'bg-gold-400/20 text-gold-400 border-gold-400/30' : 'text-gray-500 border-transparent hover:text-gray-300 hover:bg-white/5' }}">
                                                <i class="fas fa-angle-right text-[8px] opacity-50"></i>
                                                {{ $child->name }}
                                            </a>
                                        @endforeach
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </aside>

                {{-- ===== MAIN GRID ===== --}}
                <main class="flex-1 min-w-0">
                    {{-- Results header --}}
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <p class="text-dark-muted text-sm">
                                @if($search)
                                    Results for <span class="text-gold-400 font-bold">"{{ $search }}"</span> —
                                @endif
                                <span class="text-white font-bold">{{ $products->total() }}</span> products found
                                @if($activeSubcategory)
                                    in <span class="text-gold-400 font-bold">{{ $activeSubcategory->name }}</span>
                                @elseif($activeCategory)
                                    in <span class="text-gold-400 font-bold">{{ $activeCategory->name }}</span>
                                @endif
                            </p>
                        </div>
                        @if($activeCategory || $search)
                            <a href="{{ route('products') }}" class="text-xs text-dark-muted hover:text-gold-400 transition flex items-center gap-1">
                                <i class="fas fa-times-circle"></i> Clear filters
                            </a>
                        @endif
                    </div>

                    @if($products->count())
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach($products as $product)
                                @php
                                    $imgPath = $product->image;
                                    if ($imgPath && !Str::startsWith($imgPath, ['http', 'https', '/'])) {
                                        $imgPath = asset($imgPath);
                                    } elseif (!$imgPath) {
                                        $imgPath = asset('images/placeholder.webp');
                                    }
                                @endphp
                                <div class="product-card group relative bg-[#0d0d0d] rounded-[2rem] overflow-hidden border border-white/5 hover:border-gold-400/30 transition-all duration-500 hover:shadow-2xl hover:shadow-gold-400/5">
                                    {{-- Image --}}
                                    <div class="relative aspect-square overflow-hidden bg-[#111]">
                                        <img src="{{ $imgPath }}" alt="{{ $product->name }}"
                                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                                        @if($product->is_featured)
                                            <div class="absolute top-4 left-4">
                                                <span class="bg-gold-400 text-dark text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full">Featured</span>
                                            </div>
                                        @endif
                                        {{-- Quick Actions on Hover --}}
                                        <div class="absolute inset-0 bg-dark/70 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <button onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})"
                                                class="bg-gold-400 text-dark font-black px-8 py-3.5 rounded-2xl text-[11px] uppercase tracking-widest hover:bg-white transition transform translate-y-4 group-hover:translate-y-0 duration-500 shadow-xl flex items-center gap-2">
                                                <i class="fas fa-cart-plus text-sm"></i> Add to Cart
                                            </button>
                                        </div>
                                    </div>
                                    {{-- Info --}}
                                    <div class="p-6">
                                        <div class="text-[10px] font-bold uppercase tracking-[0.25em] text-dark-muted mb-2">
                                            {{ $product->subcategory?->name ?? $product->category?->name ?? '—' }}
                                        </div>
                                        <h3 class="font-bold text-white text-sm leading-snug mb-3 line-clamp-2 group-hover:text-gold-400 transition">
                                            <a href="{{ route('product.detail', $product->slug) }}">{{ $product->name }}</a>
                                        </h3>
                                        <div class="flex items-center justify-between">
                                            <span class="text-gold-400 font-black text-xl">R {{ number_format($product->price, 2) }}</span>
                                            <a href="{{ route('product.detail', $product->slug) }}" class="text-dark-muted hover:text-gold-400 transition text-xs font-bold">
                                                View Details <i class="fas fa-arrow-right ml-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        {{-- Pagination --}}
                        <div class="mt-12">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div class="text-center py-32">
                            <div class="text-6xl mb-6 opacity-20"><i class="fas fa-box-open"></i></div>
                            <h3 class="text-2xl font-bold mb-4">No products found</h3>
                            <p class="text-dark-muted mb-8">Try adjusting your search or browse all categories.</p>
                            <a href="{{ route('products') }}" class="btn-gold px-8 py-4 rounded-full text-sm font-black uppercase tracking-widest">
                                Browse All Products
                            </a>
                        </div>
                    @endif
                </main>
            </div>
        </div>
    </div>

    <script>
        function addToCart(productId, productName, price) {
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ product_id: productId, quantity: 1 })
            })
            .then(r => r.json())
            .then(data => {
                if (window.showToast) window.showToast(productName + ' added to cart!', 'success');
                if (data.cart_count !== undefined && window.updateCartBadge) window.updateCartBadge(data.cart_count);
            })
            .catch(() => {
                if (window.showToast) window.showToast('Could not add to cart. Please try again.', 'error');
            });
        }
    </script>
@endsection