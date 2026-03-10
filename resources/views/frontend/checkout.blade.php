@extends('layouts.frontend')

@section('title', 'Finalize Your Order - Jabulani Group')

@section('content')
    <!-- Page Header -->
    <div class="relative py-16 overflow-hidden bg-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-left">
            <h1 class="text-3xl lg:text-5xl font-black mb-2 tracking-tight italic text-white uppercase">Secure <span class="gradient-text">Procurement</span></h1>
            <nav class="flex justify-center sm:justify-start items-center gap-2 text-[10px] font-black uppercase tracking-widest text-dark-muted">
                <a href="{{ route('cart') }}" class="hover:text-gold-400 transition">Return to Cart</a>
                <span class="w-1 h-1 rounded-full bg-gold-400/50"></span>
                <span class="text-gray-400">Checkout Documentation</span>
            </nav>
        </div>
    </div>

    <div class="bg-[#050505] min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="/checkout" method="POST" id="checkoutForm">
                @csrf
                <input type="hidden" name="lat" id="checkout-lat">
                <input type="hidden" name="lng" id="checkout-lng">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">

                    <!-- Left: Procurement Details -->
                    <div class="lg:col-span-2 space-y-8">

                        <!-- Shipping Details -->
                        <div class="card-dark rounded-[2.5rem] p-8 border-white/5 bg-gradient-to-br from-white/[0.03] to-transparent shadow-2xl relative overflow-hidden group">
                            
                            <div class="flex items-center gap-4 mb-8 border-b border-white/5 pb-4">
                                <div class="w-10 h-10 rounded-xl bg-gold-400 text-dark flex items-center justify-center font-black text-base italic shadow-xl">01</div>
                                <div>
                                    <h3 class="text-lg font-black text-white italic uppercase">Order <span class="text-gold-400">Details</span></h3>
                                    <p class="text-[8px] font-black uppercase tracking-widest text-dark-muted">Contact & Delivery Information</p>
                                </div>
                            </div>

                            @if(auth()->check() && $addresses->count() > 0)
                                <div class="mb-8 p-6 bg-gold-400/5 border border-gold-400/10 rounded-2xl">
                                    <label class="block text-[9px] font-black uppercase tracking-widest text-gold-400 mb-4 italic">Select Saved Profile</label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach($addresses as $addr)
                                            <div class="address-profile-card cursor-pointer bg-black/40 border border-white/5 rounded-xl p-4 transition-all hover:border-gold-400/50 group {{ $addr->is_default ? 'border-gold-400/30 ring-1 ring-gold-400/30' : '' }}"
                                                onclick="selectAddressProfile(this)"
                                                data-name="{{ $user->name }}"
                                                data-phone="{{ $user->phone }}"
                                                data-address="{{ $addr->address_line_1 }} {{ $addr->address_line_2 }}"
                                                data-city="{{ $addr->city }}"
                                                data-postal="{{ $addr->postal_code }}">
                                                <div class="flex items-center justify-between mb-2">
                                                    <span class="text-[10px] font-black text-white uppercase italic">{{ $addr->address_name }}</span>
                                                    @if($addr->is_default)
                                                        <i class="fas fa-check-circle text-gold-400 text-[10px]"></i>
                                                    @endif
                                                </div>
                                                <p class="text-[8px] text-dark-muted truncate">{{ $addr->address_line_1 }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="space-y-1.5">
                                    <label class="text-[9px] font-black uppercase tracking-widest text-dark-muted ml-1">Full Name</label>
                                    <input type="text" name="customer_name" id="customer_name" required
                                        value="{{ old('customer_name', isset($user) ? $user->name : '') }}"
                                        class="w-full bg-black/40 border border-white/10 rounded-xl px-5 py-3.5 text-sm text-gray-200 focus:outline-none focus:border-gold-400/50 transition-all shadow-inner">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[9px] font-black uppercase tracking-widest text-dark-muted ml-1">Email</label>
                                    <input type="email" name="customer_email" required
                                        value="{{ old('customer_email', isset($user) ? $user->email : '') }}"
                                        class="w-full bg-black/40 border border-white/10 rounded-xl px-5 py-3.5 text-sm text-gray-200 focus:outline-none focus:border-gold-400/50 transition-all shadow-inner">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[9px] font-black uppercase tracking-widest text-dark-muted ml-1">Phone Number</label>
                                    <input type="text" name="customer_phone" id="customer_phone" required 
                                        value="{{ old('customer_phone', isset($user) ? $user->phone : '') }}"
                                        class="w-full bg-black/40 border border-white/10 rounded-xl px-5 py-3.5 text-sm text-gray-200 focus:outline-none focus:border-gold-400/50 transition-all shadow-inner">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[9px] font-black uppercase tracking-widest text-dark-muted ml-1">City</label>
                                    <input type="text" name="customer_city" id="customer_city" required
                                        value="{{ old('customer_city', isset($defaultShipping) ? $defaultShipping->city : '') }}"
                                        class="w-full bg-black/40 border border-white/10 rounded-xl px-5 py-3.5 text-sm text-gray-200 focus:outline-none focus:border-gold-400/50 transition-all shadow-inner">
                                </div>
                                <div class="sm:col-span-2 space-y-1.5">
                                    <label class="text-[9px] font-black uppercase tracking-widest text-dark-muted ml-1">Delivery Address</label>
                                    <textarea name="customer_address" id="customer_address" required rows="2"
                                        class="w-full bg-black/40 border border-white/10 rounded-xl px-5 py-3.5 text-sm text-gray-200 focus:outline-none focus:border-gold-400/50 transition-all shadow-inner resize-none font-medium leading-relaxed">{{ old('customer_address', isset($defaultShipping) ? $defaultShipping->address_line_1 . ' ' . $defaultShipping->address_line_2 : '') }}</textarea>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[9px] font-black uppercase tracking-widest text-dark-muted ml-1">Postal Code</label>
                                    <input type="text" name="customer_postal_code" id="customer_postal_code" required
                                        value="{{ old('customer_postal_code', isset($defaultShipping) ? $defaultShipping->postal_code : '') }}"
                                        class="w-full bg-black/40 border border-white/10 rounded-xl px-5 py-3.5 text-sm text-gray-200 focus:outline-none focus:border-gold-400/50 transition-all shadow-inner">
                                </div>
                            </div>
                        </div>

                        <!-- Payment Selection -->
                        <div class="card-dark rounded-[2.5rem] p-8 border-white/5 bg-gradient-to-br from-white/[0.03] to-transparent shadow-2xl relative overflow-hidden group">
                            <div class="flex items-center gap-4 mb-8 border-b border-white/5 pb-4">
                                <div class="w-10 h-10 rounded-xl bg-gold-400 text-dark flex items-center justify-center font-black text-base italic shadow-xl">02</div>
                                <div>
                                    <h3 class="text-lg font-black text-white italic uppercase">Payment <span class="text-gold-400">Method</span></h3>
                                    <p class="text-[8px] font-black uppercase tracking-widest text-dark-muted">Verified Payment Systems</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach([
                                    ['eft', 'fas fa-building-columns', 'Bank EFT', 'Direct Transfer'], 
                                    ['payfast', 'fas fa-credit-card', 'Online', 'Secure Gateway']
                                ] as [$val, $icon, $label, $desc])
                                    <label class="relative cursor-pointer group/opt">
                                        <input type="radio" name="payment_method" value="{{ $val }}" {{ $val === 'eft' ? 'checked' : '' }} class="peer hidden">
                                        <div class="h-full bg-black/40 rounded-2xl p-4 border border-white/5 peer-checked:border-gold-400 peer-checked:bg-gold-400/10 transition-all duration-300 flex flex-col items-center text-center">
                                            <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gold-400 mb-3 peer-checked:bg-gold-400 peer-checked:text-dark transition-all duration-300">
                                                <i class="{{ $icon }} text-sm"></i>
                                            </div>
                                            <h4 class="text-xs font-black text-white italic mb-1">{{ $label }}</h4>
                                            <p class="text-[8px] text-dark-muted font-bold uppercase tracking-widest leading-tight">{{ $desc }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Logistics & Notes -->
                        <div class="card-dark rounded-[2.5rem] p-8 border-white/5 bg-gradient-to-br from-white/[0.03] to-transparent shadow-2xl relative overflow-hidden group">
                            <div class="flex items-center gap-4 mb-8 border-b border-white/5 pb-4">
                                <div class="w-10 h-10 rounded-xl bg-gold-400 text-dark flex items-center justify-center font-black text-base italic shadow-xl">03</div>
                                <div>
                                    <h3 class="text-lg font-black text-white italic uppercase">Fulfillment <span class="text-gold-400">Hub</span></h3>
                                    <p class="text-[8px] font-black uppercase tracking-widest text-dark-muted">Logistics & Site Instructions</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="space-y-1.5">
                                    <label class="text-[9px] font-black uppercase tracking-widest text-dark-muted ml-1">Service Type</label>
                                    <div class="relative">
                                        <select name="order_type" id="order_type"
                                            class="w-full bg-black/40 border border-white/10 rounded-xl px-5 py-3.5 text-sm text-gray-200 focus:outline-none focus:border-gold-400/50 appearance-none transition shadow-inner">
                                            <option value="delivery">Delivery to Site</option>
                                            <option value="pickup">Warehouse Pickup</option>
                                        </select>
                                        <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gold-400">
                                            <i class="fas fa-chevron-down text-[10px]"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[9px] font-black uppercase tracking-widest text-dark-muted ml-1">Fulfillment Branch</label>
                                    <div class="relative">
                                        <select name="store_id" id="store_id"
                                            class="w-full bg-black/40 border border-white/10 rounded-xl px-5 py-3.5 text-sm text-gray-200 focus:outline-none focus:border-gold-400/50 appearance-none transition shadow-inner">
                                            @foreach($stores as $store)
                                                <option value="{{ $store->id }}" data-lat="{{ $store->lat }}"
                                                    data-lng="{{ $store->lng }}" {{ $loop->first ? 'selected' : '' }}>{{ $store->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gold-400">
                                            <i class="fas fa-warehouse text-[10px]"></i>
                                        </div>
                                    </div>
                                    <p id="store-distance" class="text-[8px] font-black text-gold-400 mt-1 italic hidden uppercase tracking-widest"></p>
                                </div>
                                <div class="sm:col-span-2 space-y-1.5">
                                    <label class="text-[9px] font-black uppercase tracking-widest text-dark-muted ml-1">Special Instructions <span class="opacity-40">(Optional)</span></label>
                                    <textarea name="notes" rows="2"
                                        class="w-full bg-black/40 border border-white/10 rounded-xl px-5 py-3.5 text-sm text-gray-200 focus:outline-none focus:border-gold-400/50 transition-all shadow-inner resize-none"
                                        placeholder="Any specific instructions for our team?"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Right: Inventory Summary -->
                    <div class="lg:sticky lg:top-32">
                        <div class="card-dark rounded-[2.5rem] p-8 border-gold-400/20 bg-gradient-to-br from-[#111] to-dark shadow-2xl relative overflow-hidden">
                            <h3 class="text-xl font-black text-white italic mb-8 tracking-tight uppercase">Order <span class="gradient-text">Summary</span></h3>

                            <!-- Mini Item List -->
                            <div class="space-y-4 mb-8 pb-8 border-b border-white/5 max-h-60 overflow-y-auto custom-scrollbar pr-2">
                                @foreach($products as $product)
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0 border border-white/10">
                                            <img src="{{ $product->image ? asset($product->image) : asset('images/placeholder.webp') }}" class="w-full h-full object-cover" alt="product" loading="lazy">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[11px] font-bold text-white truncate italic">{{ $product->name }}</p>
                                            <p class="text-[8px] font-black uppercase tracking-widest text-dark-muted mt-0.5">Qty: {{ $product->cart_quantity }}</p>
                                        </div>
                                        <p class="text-xs font-black text-white italic tracking-tighter">R{{ number_format($product->cart_subtotal, 2) }}</p>
                                    </div>
                                @endforeach
                            </div>

                            <div class="space-y-4 text-sm mb-8 pb-8 border-b border-white/5">
                                <div class="flex justify-between items-center text-dark-muted">
                                    <p class="text-[9px] font-black uppercase tracking-widest">Subtotal</p>
                                    <span class="text-sm font-bold tracking-tight">R{{ number_format($total, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p class="text-[9px] font-black uppercase tracking-widest text-dark-muted">Tax (VAT Incl)</p>
                                    <span class="text-[8px] font-black text-gold-400 uppercase tracking-widest bg-gold-400/5 px-2 py-0.5 rounded-full">Included</span>
                                </div>
                            </div>

                            <div class="mb-10">
                                <p class="text-[10px] font-black uppercase tracking-widest text-dark-muted mb-1 italic">Total Requisition</p>
                                <p class="text-4xl font-black text-gold-400 italic tracking-tighter">
                                    R{{ number_format($total, 2) }}
                                </p>
                            </div>

                            <button type="submit"
                                class="btn-gold w-full flex items-center justify-center gap-2 py-5 text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-2xl group relative overflow-hidden">
                                <i class="fas fa-lock text-sm relative z-10"></i> 
                                <span class="relative z-10">Place Order Now</span>
                            </button>
                            
                            <p class="text-[7px] text-center font-black uppercase tracking-widest text-dark-muted mt-4 italic opacity-40">Secure fulfillment terminal</p>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
                        
                        <!-- Mini Map context -->
                        <div class="mt-8 flex items-center justify-center gap-4 text-dark-muted opacity-50 grayscale">
                            <i class="fas fa-lock text-sm"></i>
                            <span class="text-[9px] font-black uppercase tracking-widest">End-to-End Encryption Terminal</span>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        function selectAddressProfile(element) {
            // Update fields
            document.getElementById('customer_name').value = element.dataset.name;
            document.getElementById('customer_phone').value = element.dataset.phone;
            document.getElementById('customer_address').value = element.dataset.address;
            document.getElementById('customer_city').value = element.dataset.city;
            document.getElementById('customer_postal_code').value = element.dataset.postal;

            // Update UI selection
            document.querySelectorAll('.address-profile-card').forEach(card => {
                card.classList.remove('border-gold-400/30', 'ring-1', 'ring-gold-400/30');
            });
            element.classList.add('border-gold-400/30', 'ring-1', 'ring-gold-400/30');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const storeSelect = document.getElementById('store_id');
            const distanceText = document.getElementById('store-distance');
            let userInteracted = false;
            const cartHasOnlyCrushStone = @json($cartHasOnlyCrushStone ?? false);

            storeSelect.addEventListener('change', () => userInteracted = true);

            function haversine(lat1, lon1, lat2, lon2) {
                const R = 6371, dLat = (lat2 - lat1) * Math.PI / 180, dLon = (lon2 - lon1) * Math.PI / 180;
                const a = Math.sin(dLat / 2) ** 2 + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dLon / 2) ** 2;
                return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            }

            function updateDistances(userLat, userLng) {
                let nearest = null, minDist = Infinity;
                Array.from(storeSelect.options).forEach(opt => {
                    const sLat = parseFloat(opt.getAttribute('data-lat')), sLng = parseFloat(opt.getAttribute('data-lng'));
                    if (sLat && sLng) {
                        const dist = haversine(userLat, userLng, sLat, sLng);
                        opt.text = opt.text.split('(')[0].trim() + ` (${dist.toFixed(1)} km)`;
                        if (dist < minDist) { minDist = dist; nearest = opt.value; }
                    }
                });
                
                if (nearest && !userInteracted) {
                    let overriddenDist = minDist;
                    const nearestOpt = storeSelect.querySelector(`option[value="${nearest}"]`);
                    
                    if (nearestOpt && nearestOpt.text.toLowerCase().includes('quarries') && !cartHasOnlyCrushStone) {
                        const tsoloHardwareOpt = Array.from(storeSelect.options).find(opt => opt.text.toLowerCase().includes('hardware tsolo'));
                        if (tsoloHardwareOpt) {
                            nearest = tsoloHardwareOpt.value;
                            const sLat = parseFloat(tsoloHardwareOpt.getAttribute('data-lat'));
                            const sLng = parseFloat(tsoloHardwareOpt.getAttribute('data-lng'));
                            overriddenDist = haversine(userLat, userLng, sLat, sLng);
                        }
                    }

                    storeSelect.value = nearest;
                    distanceText.textContent = `OPTIMAL DISPATCH HUB IDENTIFIED: ~${overriddenDist.toFixed(1)}KM`;
                    distanceText.classList.remove('hidden');
                } else if (minDist !== Infinity) {
                    distanceText.textContent = `NEAREST HUB: ~${minDist.toFixed(1)}KM AWAY`;
                    distanceText.classList.remove('hidden');
                }
            }

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(pos => {
                    if (document.getElementById('checkout-lat')) document.getElementById('checkout-lat').value = pos.coords.latitude;
                    if (document.getElementById('checkout-lng')) document.getElementById('checkout-lng').value = pos.coords.longitude;
                    updateDistances(pos.coords.latitude, pos.coords.longitude);
                }, err => {
                    console.log('Geolocation denied/failed');
                }, { timeout: 10000 });
            }
        });
    </script>
@endpush