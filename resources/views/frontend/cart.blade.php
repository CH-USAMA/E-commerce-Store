@extends('layouts.frontend')

@section('title', 'Your Cart - Jabulani Group')

@section('content')
    <!-- Page Header with Thematic Background -->
    <div class="relative pt-32 pb-16 overflow-hidden bg-dark">
        <div class="absolute top-0 left-0 w-1/3 h-full bg-gold-400/5 blur-[150px] -z-10 rounded-full"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-8">
                <div class="text-center md:text-left">
                    <h1 class="text-4xl lg:text-6xl font-black mb-4 tracking-tighter italic text-white uppercase">
                        Your <span class="gradient-text">Shopping Cart</span>
                    </h1>
                    <nav
                        class="flex justify-center md:justify-start items-center gap-3 text-[9px] font-black uppercase tracking-[0.3em] text-dark-muted">
                        <a href="{{ route('home') }}" class="hover:text-gold-400 transition">Home</a>
                        <span class="w-1 h-1 rounded-full bg-gold-400/30"></span>
                        <span class="text-white">Shopping Items</span>
                    </nav>
                </div>
                <div
                    class="hidden md:flex items-center gap-4 bg-white/5 border border-white/10 rounded-2xl px-6 py-4 backdrop-blur-md">
                    <div class="w-12 h-12 rounded-xl bg-gold-400/10 flex items-center justify-center text-gold-400">
                        <i class="fas fa-shopping-basket text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-white uppercase tracking-widest">Inventory Selection</p>
                        <p class="text-[9px] text-dark-muted uppercase tracking-tighter">{{ $products->count() }} Distinct
                            Items</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-[#050505] min-h-screen py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($products->count() > 0)
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">

                            <!-- Cart Items -->
                            <div class="lg:col-span-2 space-y-8">
                                @foreach($products as $product)
                                    <div id="cart-row-{{ $product->id }}"
                                        class="card-dark rounded-[3rem] p-8 border-white/5 bg-gradient-to-br from-white/[0.03] to-transparent hover:border-gold-400/20 transition-all duration-700 shadow-2xl relative overflow-hidden group">

                                        <div class="flex flex-col sm:flex-row items-center gap-10 relative z-10">
                                            <!-- Image with Zoom Effect -->
                                            <a href="{{ route('product.detail', $product->slug) }}" class="flex-shrink-0">
                                                <div
                                                    class="w-32 h-32 sm:w-40 sm:h-40 rounded-[2rem] overflow-hidden border border-white/10 shadow-2xl relative group/img">
                                                    @php
                                                        $imgCartSrc = 'images/placeholder.webp';
                                                        if ($product->image && file_exists(public_path($product->image))) {
                                                            $segments = explode('/', $product->image);
                                                            $encodedSegments = array_map('rawurlencode', $segments);
                                                            $imgCartSrc = implode('/', $encodedSegments);
                                                        }
                                                    @endphp
                                                    <img src="{{ asset($imgCartSrc) }}" alt="{{ $product->name }}"
                                                        class="w-full h-full object-cover transition-transform duration-1000 group-hover/img:scale-110"
                                                        loading="lazy">
                                                    <div
                                                        class="absolute inset-0 bg-black/20 group-hover/img:bg-transparent transition-colors">
                                                    </div>
                                                </div>
                                            </a>

                                            <!-- Detailed Info -->
                                            <div class="flex-1 text-center sm:text-left min-w-0">
                                                <div class="flex items-center justify-center sm:justify-start gap-4 mb-3">
                                                    <span
                                                        class="text-[8px] font-bold uppercase tracking-[0.2em] text-gold-400/80 bg-white/5 px-4 py-1.5 rounded-full border border-white/10">
                                                        #JB-{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}
                                                    </span>
                                                </div>
                                                <a href="{{ route('product.detail', $product->slug) }}"
                                                    class="text-xl font-bold text-white hover:text-gold-400 transition leading-tight block mb-4 uppercase tracking-normal">{{ $product->name }}</a>

                                                <div class="flex items-baseline justify-center sm:justify-start gap-2">
                                                    <span
                                                        class="text-[9px] text-dark-muted font-bold uppercase tracking-widest">Rate:</span>
                                                    <p class="text-lg font-bold text-white tracking-tight">
                                                        R{{ number_format($product->price, 2) }}
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Status & Controls -->
                                            <div class="flex flex-col items-center sm:items-end gap-6">
                                                <div
                                                    class="flex items-center bg-black/40 border border-white/10 rounded-2xl p-1.5 shadow-inner backdrop-blur-sm">
                                                    <button onclick="changeQty({{ $product->id }}, {{ $product->price }}, -1)"
                                                        class="w-12 h-12 flex items-center justify-center text-dark-muted hover:text-gold-400 hover:bg-white/5 transition-all rounded-xl">
                                                        <i class="fas fa-minus text-xs"></i>
                                                    </button>
                                                    <span id="qty-{{ $product->id }}"
                                                        class="w-12 text-center text-lg font-black text-white">{{ $product->cart_quantity }}</span>
                                                    <button onclick="changeQty({{ $product->id }}, {{ $product->price }}, 1)"
                                                        class="w-12 h-12 flex items-center justify-center text-dark-muted hover:text-gold-400 hover:bg-white/5 transition-all rounded-xl">
                                                        <i class="fas fa-plus text-xs"></i>
                                                    </button>
                                                </div>

                                                <div class="text-center sm:text-right">
                                                    <p class="text-[8px] font-bold uppercase tracking-[0.3em] text-dark-muted mb-2">
                                                        Valuation</p>
                                                    <p id="sub-{{ $product->id }}"
                                                        class="text-2xl font-bold text-gold-400 tracking-tight mb-4">
                                                        R{{ number_format($product->cart_subtotal, 2) }}
                                                    </p>
                                                    <button onclick="removeItem({{ $product->id }})"
                                                        class="text-[9px] font-bold uppercase tracking-widest text-red-400/50 hover:text-red-400 transition-all flex items-center justify-center sm:justify-end gap-2 group/del">
                                                        <span class="w-4 h-px bg-red-400/20 group-hover/del:w-8 transition-all"></span>
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pt-10">
                                    <a href="{{ route('products') }}"
                                        class="btn-outline-gold group px-10 py-4 text-[10px] font-black uppercase tracking-widest rounded-full flex items-center gap-3">
                                        <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> Return to
                                        Catalog
                                    </a>
                                    <div class="flex items-center gap-4">
                                        <span class="text-[10px] font-black uppercase tracking-widest text-dark-muted">Prefer human
                                            contact?</span>
                                        <a href="https://wa.me/27660684585?text=Hi, I would like to place an order." target="_blank"
                                            class="text-[10px] font-black uppercase tracking-widest text-white hover:text-gold-400 transition flex items-center gap-2 px-6 py-4 bg-white/5 rounded-full border border-white/10">
                                            <i class="fab fa-whatsapp text-lg text-green-500"></i> WhatsApp Orders
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Sidebar Summary -->
                            <div class="lg:sticky lg:top-32">
                                <div
                                    class="card-dark rounded-[3.5rem] p-12 border-white/5 bg-gradient-to-br from-white/[0.04] to-transparent shadow-2xl relative overflow-hidden">
                                    <h3 class="text-2xl font-black text-white italic mb-12 tracking-tight uppercase">Shopping <span
                                            class="gradient-text">Summary</span></h3>

                                    <div id="total-display" class="space-y-8 text-sm mb-12 pb-12 border-b border-white/5">
                                        <div class="flex justify-between items-center">
                                            <p class="text-[9px] font-bold uppercase tracking-[0.4em] text-dark-muted">Summary Total</p>
                                            <span class="text-lg font-bold text-white tracking-tight"
                                                id="cart-total-display">R{{ number_format($total, 2) }}</span>
                                        </div>
                                    </div>

                                    <div class="mb-12">
                                        <p class="text-[10px] font-black uppercase tracking-[0.6em] text-dark-muted mb-4 opacity-50">
                                            Total Capital Outlay</p>
                                        <div class="flex items-center justify-between">
                                            <p class="text-4xl font-bold text-gold-400 tracking-tight" id="cart-total-big">
                                                R{{ number_format($total, 2) }}
                                            </p>
                                            <div
                                                class="w-16 h-16 rounded-[1.5rem] bg-gold-400/10 flex items-center justify-center text-gold-400 border border-gold-400/20 shadow-inner">
                                                <i class="fas fa-file-invoice text-2xl"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <a href="{{ route('checkout') }}"
                                        class="btn-gold w-full flex items-center justify-center gap-4 py-6 text-[12px] font-black uppercase tracking-[0.4em] rounded-[1.5rem] shadow-[0_25px_50px_rgba(255,229,7,0.2)] group hover:-translate-y-1 transition-all">
                                        <i class="fas fa-shield-alt group-hover:scale-110 transition-transform"></i> Finalize Order
                                    </a>
                                </div>
                            </div>

                            <!-- Geographic context -->
                            <div id="nearest-store-box" style="display:none"
                                class="mt-8 p-6 rounded-3xl border border-white/10 bg-white/[0.03]">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-8 h-8 rounded-full bg-gold-400 text-dark flex items-center justify-center text-xs">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-white">Nearest Dispatch
                                        Store</p>
                                </div>
                                <p class="text-xs font-bold text-gold-400 mb-1" id="nearest-store-name"></p>
                                <p class="text-[10px] text-dark-muted leading-relaxed" id="nearest-store-address"></p>
                            </div>
                        </div>

                        <!-- Trust badges small -->
                        <div class="grid grid-cols-2 gap-4 mt-8">
                            <div
                                class="flex items-center gap-3 grayscale opacity-40 hover:grayscale-0 hover:opacity-100 transition-all duration-500 cursor-default">
                                <i class="fas fa-shield-alt text-lg"></i>
                                <span class="text-[8px] font-black uppercase tracking-[0.2em] text-white">Secure Data</span>
                            </div>
                            <div
                                class="flex items-center gap-3 grayscale opacity-40 hover:grayscale-0 hover:opacity-100 transition-all duration-500 cursor-default px-2">
                                <i class="fas fa-truck text-lg"></i>
                                <span class="text-[8px] font-black uppercase tracking-[0.2em] text-white">Superlink Fleet</span>
                            </div>
                        </div>
                    </div>
                </div>

            @else
        <div class="flex flex-col items-center justify-center py-40 text-center card-dark rounded-[4rem] border-white/5">
            <div
                class="w-32 h-32 rounded-full bg-white/5 border border-white/5 flex items-center justify-center mb-10 shadow-2xl relative">
                <div class="absolute inset-0 bg-gold-400/5 blur-2xl rounded-full"></div>
                <i class="fas fa-cart-arrow-down text-5xl text-dark-muted relative z-10"></i>
            </div>
            <h2 class="text-4xl font-black text-white italic mb-4 tracking-tight">Cart Empty.</h2>
            <p class="text-dark-muted text-sm mb-12 max-w-sm mx-auto leading-relaxed">It looks like you haven't selected
                any materials for your project yet. Explore our premium catalog to get started.</p>
            <a href="{{ route('products') }}"
                class="btn-gold px-12 py-5 text-[11px] font-black uppercase tracking-widest rounded-full shadow-2xl">Start
                Sourcing Now</a>
        </div>
    @endif
    </div>
    </div>
@endsection

@push('js')
    <script>
        let productData = {
            @foreach($products as $p)
                {{ $p->id }}: { qty: {{ $p->cart_quantity }}, price: {{ $p->price }} },
            @endforeach
                            };

        function recalcTotal() {
            let total = 0;
            for (let id in productData) { total += productData[id].qty * productData[id].price; }
            let fmt = 'R' + total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            let d1 = document.getElementById('cart-total-display');
            let d2 = document.getElementById('cart-total-big');
            if (d1) d1.textContent = fmt;
            if (d2) d2.textContent = fmt;
        }

        function changeQty(id, price, delta) {
            let current = productData[id] ? productData[id].qty : 1;
            let newQty = Math.max(1, Math.min(999, current + delta));
            productData[id] = { qty: newQty, price: price };
            document.getElementById('qty-' + id).textContent = newQty;
            document.getElementById('sub-' + id).textContent = 'R' + (price * newQty).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            recalcTotal();
            fetch('{{ route("cart.update") }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ product_id: id, quantity: newQty }) }).then(r => r.json()).then(data => window.updateCartBadge(data.cart_count));
        }

        function removeItem(id) {
            fetch('{{ route("cart.remove") }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ product_id: id }) }).then(r => r.json()).then(data => {
                document.getElementById('cart-row-' + id).remove();
                delete productData[id];
                recalcTotal();
                window.updateCartBadge(data.cart_count);
                if (Object.keys(productData).length === 0) location.reload();
            });
        }

        // Geolocation nearest store
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(pos => {
                fetch('{{ route("cart.nearest-store") }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ lat: pos.coords.latitude, lng: pos.coords.longitude }) }).then(r => r.json()).then(data => {
                    if (data.success) {
                        document.getElementById('nearest-store-name').textContent = data.store.name;
                        document.getElementById('nearest-store-address').textContent = data.store.address;
                        document.getElementById('nearest-store-box').style.display = 'block';
                    }
                });
            });
        }
    </script>
@endpush