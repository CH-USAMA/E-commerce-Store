@extends('layouts.frontend')

@section('title', 'Your Cart - Jabulani Group')

@section('content')
    <!-- Page Header with Thematic Background -->
    <div class="relative pt-10 pb-12 overflow-hidden bg-dark">
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

    <div class="bg-[#050505] min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($products->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
                    <!-- Compact Cart Items -->
                    <div class="lg:col-span-3">
                        <div class="card-dark border-white/5 bg-white/[0.02] rounded-3xl overflow-hidden shadow-2xl">
                            <!-- Table Header (Desktop Only) -->
                            <div class="hidden md:grid grid-cols-12 gap-4 px-8 py-4 bg-white/5 border-b border-white/10 text-[9px] font-black uppercase tracking-widest text-dark-muted">
                                <div class="col-span-6">Material Description</div>
                                <div class="col-span-2 text-center">Unit Rate</div>
                                <div class="col-span-2 text-center">Quantity</div>
                                <div class="col-span-2 text-right">Subtotal</div>
                            </div>

                            <div class="divide-y divide-white/5">
                                @foreach($products as $product)
                                    <div id="cart-row-{{ $product->id }}" class="group transition-all duration-300 hover:bg-white/[0.03]">
                                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center px-6 py-6 sm:px-8">
                                            <!-- Product Info -->
                                            <div class="md:col-span-6 flex items-center gap-4 sm:gap-6">
                                                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl overflow-hidden border border-white/10 flex-shrink-0 bg-dark shadow-lg">
                                                    @php
                                                        $imgSrc = 'images/placeholder.webp';
                                                        if ($product->image && file_exists(public_path($product->image))) {
                                                            $imgSrc = $product->image;
                                                        }
                                                    @endphp
                                                    <img src="{{ asset($imgSrc) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-[8px] font-black text-gold-400 uppercase tracking-widest mb-1 opacity-60">#{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</p>
                                                    <a href="{{ route('product.detail', $product->slug) }}" class="text-sm sm:text-base font-bold text-white hover:text-gold-400 transition truncate block uppercase tracking-tight">{{ $product->name }}</a>
                                                    <button onclick="removeItem({{ $product->id }})" class="mt-2 text-[9px] font-black text-red-400/40 hover:text-red-400 transition uppercase tracking-widest flex items-center gap-2">
                                                        <i class="fas fa-trash-alt"></i> Remove Item
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Price (Mobile Friendly) -->
                                            <div class="md:col-span-2 flex md:flex-col items-baseline md:items-center justify-between gap-1">
                                                <span class="md:hidden text-[9px] font-black uppercase text-dark-muted">Unit Rate:</span>
                                                <p class="text-sm font-bold text-gray-300">R{{ number_format($product->price, 2) }}</p>
                                            </div>

                                            <!-- Qty Controls -->
                                            <div class="md:col-span-2 flex md:flex-col items-center justify-between md:justify-center gap-4">
                                                <span class="md:hidden text-[9px] font-black uppercase text-dark-muted">Quantity:</span>
                                                <div class="flex items-center bg-black/40 border border-white/10 rounded-xl p-1 shadow-inner">
                                                    <button onclick="changeQty({{ $product->id }}, {{ $product->price }}, -1)" class="w-8 h-8 flex items-center justify-center text-dark-muted hover:text-gold-400 transition">
                                                        <i class="fas fa-minus text-[10px]"></i>
                                                    </button>
                                                    <span id="qty-{{ $product->id }}" class="w-8 text-center text-xs font-black text-white px-1">{{ $product->cart_quantity }}</span>
                                                    <button onclick="changeQty({{ $product->id }}, {{ $product->price }}, 1)" class="w-8 h-8 flex items-center justify-center text-dark-muted hover:text-gold-400 transition">
                                                        <i class="fas fa-plus text-[10px]"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Subtotal -->
                                            <div class="md:col-span-2 flex md:flex-col items-baseline md:items-end justify-between gap-1">
                                                <span class="md:hidden text-[9px] font-black uppercase text-gold-400">Subtotal:</span>
                                                <p id="sub-{{ $product->id }}" class="text-base sm:text-lg font-black text-white tracking-tighter">
                                                    R{{ number_format($product->cart_subtotal, 2) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-10 flex flex-col sm:flex-row items-center justify-between gap-6">
                            <a href="{{ route('products') }}" class="text-[10px] font-black uppercase tracking-[0.3em] text-dark-muted hover:text-gold-400 transition flex items-center gap-4 py-4 px-10 border border-white/10 rounded-2xl bg-white/5">
                                <i class="fas fa-arrow-left"></i> Return to Catalog
                            </a>
                            <a href="{{ route('cart.clear') }}" onclick="return confirm('Secure clearance? This will purge all items from current staging.')" class="text-[10px] font-black uppercase tracking-[0.3em] text-red-400/50 hover:text-red-400 transition flex items-center gap-4 py-4 px-10 border border-white/10 rounded-2xl bg-white/5">
                                <i class="fas fa-trash-alt"></i> Empty Cart
                            </a>
                            <div class="flex items-center gap-6">
                                <a href="https://wa.me/27660684585" target="_blank" class="flex items-center gap-3 text-green-500 hover:text-green-400 transition-all font-black uppercase text-[10px] tracking-widest">
                                    <i class="fab fa-whatsapp text-lg"></i> Fast Track Checkout
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Compact Sidebar -->
                    <div class="lg:sticky lg:top-36">
                        <div class="card-dark p-8 rounded-[2.5rem] bg-gradient-to-br from-white/[0.04] to-transparent shadow-2xl space-y-8">
                            <div>
                                <h4 class="text-xs font-black uppercase tracking-[0.4em] text-gold-400 mb-6 border-b border-gold-400/20 pb-2 italic">Order Summary</h4>
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center text-[10px] font-bold uppercase tracking-widest text-dark-muted">
                                        <span>Items Count:</span>
                                        <span class="text-white">{{ array_sum(session()->get('cart', [])) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-[10px] font-bold uppercase tracking-widest text-dark-muted">
                                        <span>Total Valuation:</span>
                                        <span id="cart-total-display" class="text-white">R{{ number_format($total, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-6 border-t border-white/5 text-center">
                                <p class="text-[9px] font-black uppercase tracking-[0.5em] text-dark-muted mb-2 opacity-50">Settlement Amount</p>
                                <p id="cart-total-big" class="text-3xl font-black text-white italic tracking-tighter mb-8">
                                    R{{ number_format($total, 2) }}
                                </p>
                                <a href="{{ route('checkout') }}" class="btn-gold w-full flex items-center justify-center gap-4 py-5 text-[11px] font-black uppercase tracking-[0.3em] rounded-2xl shadow-2xl hover:-translate-y-1 transition active:scale-95">
                                    Finalize Checkout <i class="fas fa-shield-alt"></i>
                                </a>
                            </div>
                            
                            <!-- Nearest Store (Mini) -->
                            <div id="nearest-store-box" style="display:none" class="pt-6 border-t border-white/5">
                                <div class="flex items-center gap-3 mb-3">
                                    <i class="fas fa-map-marker-alt text-gold-400 text-xs"></i>
                                    <p class="text-[9px] font-black uppercase tracking-widest text-white">Nearest Dispatch</p>
                                </div>
                                <p class="text-[10px] font-bold text-gold-400 mb-1" id="nearest-store-name"></p>
                                <p class="text-[9px] text-dark-muted leading-tight" id="nearest-store-address"></p>
                            </div>
                        </div>

                        <!-- Trust Badge -->
                        <div class="mt-6 flex items-center gap-4 justify-center grayscale opacity-30">
                            <i class="fas fa-lock text-xs"></i>
                            <span class="text-[8px] font-black uppercase tracking-widest">End-to-End Secure Transaction</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="py-32 text-center card-dark rounded-[3rem] border-white/5 shadow-2xl max-w-2xl mx-auto">
                    <div class="w-24 h-24 rounded-full bg-white/5 border border-white/5 flex items-center justify-center mb-8 mx-auto relative">
                        <i class="fas fa-cart-arrow-down text-3xl text-dark-muted relative z-10"></i>
                    </div>
                    <h2 class="text-3xl font-black text-white italic mb-4 tracking-tight uppercase">Cart Status: Inactive</h2>
                    <p class="text-dark-muted text-xs mb-10 max-w-sm mx-auto leading-relaxed font-bold uppercase tracking-widest px-8">No inventory items detected in current session. Initialize order to continue.</p>
                    <a href="{{ route('products') }}" class="btn-gold px-12 py-5 text-[10px] font-black uppercase tracking-widest rounded-full shadow-2xl">Return to Catalog</a>
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
            fetch('/cart/update', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ product_id: id, quantity: newQty }) }).then(r => r.json()).then(data => window.updateCartBadge(data.cart_count));
        }

        function removeItem(id) {
            fetch('/cart/remove', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ product_id: id }) }).then(r => r.json()).then(data => {
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
                fetch('/cart/nearest-store', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ lat: pos.coords.latitude, lng: pos.coords.longitude }) }).then(r => r.json()).then(data => {
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