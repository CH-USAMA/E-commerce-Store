@extends('layouts.frontend')

@section('title', 'Finalize Your Order - Jabulani Group')

@section('content')
    <!-- Page Header -->
    <div class="relative py-12 overflow-hidden bg-dark border-b border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <h1 class="text-4xl lg:text-5xl font-black mb-4 tracking-tight italic text-white uppercase leading-none">Order <span class="gradient-text">Terminal</span></h1>
            <nav class="flex items-center gap-3 text-[10px] font-black uppercase tracking-widest text-dark-muted">
                <a href="{{ route('cart') }}" class="hover:text-gold-400 transition flex items-center gap-1"><i class="fas fa-cart-shopping"></i> Return to Cart</a>
                <span class="w-1 h-1 rounded-full bg-gold-400/50"></span>
                <span class="text-gray-400">Checkout Documentation</span>
            </nav>
        </div>
    </div>

    <div x-data="{
        step: 1,
        paymentMethod: 'eft',
        orderType: 'delivery',
        distance: 0,
        fileName: '',
        maxDeliveryKm: {{ \App\Models\Setting::where('key', 'max_delivery_km')->first()?->value ?? 300 }},
        selectedAddressId: {{ $defaultShipping->id ?? 0 }},
        setPayment(method) { this.paymentMethod = method; },
        selectAddress(addr, id) {
            this.selectedAddressId = id;
            document.getElementById('customer_address').value = addr.address;
            document.getElementById('customer_city').value = addr.city;
            document.getElementById('customer_postal_code').value = addr.postal;
            if (window.updateProximity) window.updateProximity();
        },
        useCurrentLocation() {
            if (window.triggerGeolocation) window.triggerGeolocation();
        },
        validateFile(event) {
            const file = event.target.files[0];
            if (file && file.size > 2 * 1024 * 1024) {
                if (window.showToast) {
                    window.showToast('Validation Failed: Artifact exceeds 2MB limit.', 'error');
                } else {
                    alert('Validation Failed: Artifact exceeds 2MB limit.');
                }
                event.target.value = '';
                this.fileName = '';
                return;
            }
            this.fileName = file ? file.name : '';
        }
    }" class="bg-[#050505] min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <form action="/checkout" method="POST" id="checkoutForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="lat" id="checkout-lat">
                <input type="hidden" name="lng" id="checkout-lng">

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

                    <!-- Left: Order Details (Steps) -->
                    <div class="lg:col-span-8 space-y-6">

                        <!-- Header Progress -->
                        <div class="flex items-center gap-8 mb-4 px-2">
                            @foreach(['Shipping', 'Payment', 'Review'] as $i => $label)
                                <div class="flex items-center gap-3 cursor-pointer" @click="if(step > {{ $i+1 }}) step = {{ $i+1 }}">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-black italic text-[10px] transition-all duration-500"
                                        :class="step > {{ $i+1 }} ? 'bg-gold-400 text-dark' : (step === {{ $i+1 }} ? 'bg-gold-400 text-dark scale-110 shadow-[0_0_20px_rgba(255,140,0,0.3)]' : 'bg-dark border border-white/10 text-dark-muted')">
                                        {{ $i + 1 }}
                                    </div>
                                    <span class="text-[9px] font-black uppercase tracking-[0.2em] transition-colors duration-500" :class="step >= {{ $i+1 }} ? 'text-gold-400' : 'text-dark-muted'">{{ $label }}</span>
                                    @if(!$loop->last)
                                        <div class="w-10 h-[1px] bg-white/5 ml-2"></div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Step 1: Address Selection -->
                        <div x-show="step === 1" x-transition.opacity.duration.500ms class="card-dark rounded-[2.5rem] p-8 border-white/5 bg-gradient-to-br from-white/[0.04] to-transparent shadow-2xl relative overflow-hidden">
                            <div class="flex items-center justify-between mb-10 border-b border-white/5 pb-6">
                                <div>
                                    <h3 class="text-2xl font-black text-white italic uppercase tracking-tight">Destination <span class="text-gold-400">Selection</span></h3>
                                    <p class="text-[9px] font-black uppercase tracking-[0.3em] text-dark-muted mt-1">Operational Delivery Radius: <span x-text="maxDeliveryKm"></span> KM</p>
                                </div>
                                <div class="w-14 h-14 rounded-2xl bg-gold-400/10 border border-gold-400/20 flex items-center justify-center text-gold-400 text-xl">
                                    <i class="fas fa-map-location-dot"></i>
                                </div>
                            </div>

                            @if(auth()->check() && $addresses->count() > 0)
                                <div class="mb-10">
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-gold-400 mb-6 italic flex items-center gap-2">
                                        <i class="fas fa-bookmark text-[8px]"></i> Saved Order Profiles
                                    </label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($addresses as $addr)
                                            <div class="profile-card cursor-pointer bg-black/40 border border-white/5 rounded-2xl p-6 transition-all hover:border-gold-400/40 group relative overflow-hidden"
                                                @click="selectAddress({
                                                    address: '{{ $addr->address_line_1 }} {{ $addr->address_line_2 }}',
                                                    city: '{{ $addr->city }}',
                                                    postal: '{{ $addr->postal_code }}'
                                                }, {{ $addr->id }})"
                                                :class="selectedAddressId === {{ $addr->id }} ? 'border-gold-400 ring-1 ring-gold-400/20 bg-gold-400/[0.05]' : ''">
                                                <div class="flex items-start justify-between">
                                                    <div>
                                                        <h4 class="text-sm font-black text-white uppercase italic mb-1">{{ $addr->address_name }}</h4>
                                                        <p class="text-[10px] text-dark-muted font-medium">{{ $addr->address_line_1 }}, {{ $addr->city }}</p>
                                                    </div>
                                                    <div class="w-6 h-6 rounded-full bg-white/5 flex items-center justify-center text-[10px] text-gold-400 group-hover:bg-gold-400 group-hover:text-dark transition-all">
                                                        <i class="fas fa-arrow-right"></i>
                                                    </div>
                                                </div>
                                                @if($addr->is_default)
                                                    <span class="absolute top-0 right-0 bg-gold-400 text-dark font-black px-3 py-1 text-[7px] uppercase tracking-tighter rounded-bl-xl italic">Default</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-6 flex flex-col md:flex-row items-center gap-4 text-dark-muted uppercase font-black text-[9px] tracking-widest">
                                        <div class="flex-1 h-[1px] bg-white/5"></div>
                                        <button type="button" @click="useCurrentLocation()" class="btn-gold px-6 py-2 rounded-full text-[8px] flex items-center gap-2 hover:scale-105 transition-all">
                                            <i class="fas fa-location-crosshairs"></i> Use My Current Location
                                        </button>
                                        <span>Or Manual entry below</span>
                                        <div class="flex-1 h-[1px] bg-white/5"></div>
                                    </div>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="md:col-span-2 space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-dark-muted ml-1">Official Delivery Destination</label>
                                    <input type="text" name="customer_address" id="customer_address" required
                                        value="{{ old('customer_address', $defaultShipping->address_line_1 ?? '') }}"
                                        class="w-full bg-black/40 border border-white/10 rounded-2xl px-6 py-4 text-base text-gray-200 focus:outline-none focus:border-gold-400/50 transition-all shadow-inner placeholder:text-gray-700"
                                        placeholder="Enter full site address...">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-dark-muted ml-1">City / Region</label>
                                    <input type="text" name="customer_city" id="customer_city" required
                                        value="{{ old('customer_city', $defaultShipping->city ?? '') }}"
                                        class="w-full bg-black/40 border border-white/10 rounded-2xl px-6 py-4 text-base text-gray-200 focus:outline-none focus:border-gold-400/50 transition-all">
                                </div>
                                <div class="space-y-2" x-data="{ postal: '{{ old('customer_postal_code', $defaultShipping->postal_code ?? '') }}' }">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-dark-muted ml-1 flex justify-between">
                                        Postal Code <span class="opacity-40 font-bold italic tracking-normal">(OPTIONAL)</span>
                                        <span x-show="postal && !/^\d+$/.test(postal)" class="text-red-400 text-[8px] animate-pulse">Numeric Only Required</span>
                                    </label>
                                    <input type="text" name="customer_postal_code" id="customer_postal_code"
                                        inputmode="numeric"
                                        x-model="postal"
                                        :class="postal && !/^\d+$/.test(postal) ? 'border-red-400/50 bg-red-400/5' : 'border-white/10 bg-black/40'"
                                        class="w-full rounded-2xl px-6 py-4 text-base text-gray-200 focus:outline-none focus:border-gold-400/50 transition-all border"
                                        placeholder="0000">
                                </div>
                            </div>

                            <!-- Hidden Profile Fields (Admin logic will handle saving if profile is empty) -->
                            <input type="hidden" name="customer_name" value="{{ $user->name }}">
                            <input type="hidden" name="customer_email" value="{{ $user->email }}">
                            <input type="hidden" name="customer_phone" value="{{ $user->phone }}">

                            <div class="mt-12 flex justify-end">
                                <button type="button" @click="step = 2" class="btn-gold px-16 py-5 text-xs font-black uppercase tracking-[0.2em] rounded-2xl group shadow-[0_20px_40px_rgba(0,0,0,0.3)]">
                                    Continue to Payment <i class="fas fa-arrow-right ml-3 group-hover:translate-x-2 transition-transform"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Payment & POP -->
                        <div x-show="step === 2" x-transition.opacity.duration.500ms class="space-y-6">
                            <div class="card-dark rounded-[2.5rem] p-8 border-white/5 bg-gradient-to-br from-white/[0.04] to-transparent shadow-2xl relative overflow-hidden">
                                <div class="flex items-center justify-between mb-10 border-b border-white/5 pb-6">
                                    <div>
                                        <h3 class="text-2xl font-black text-white italic uppercase tracking-tight">Payment <span class="text-gold-400">Authorization</span></h3>
                                        <p class="text-[9px] font-black uppercase tracking-[0.3em] text-dark-muted mt-1">Encrypted Transaction Interface</p>
                                    </div>
                                    <div class="w-14 h-14 rounded-2xl bg-gold-400/10 border border-gold-400/20 flex items-center justify-center text-gold-400 text-xl">
                                        <i class="fas fa-shield-halved"></i>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="payment_method" value="eft" x-model="paymentMethod" class="peer hidden">
                                        <div class="h-full bg-black/40 rounded-[2rem] p-8 border border-white/5 peer-checked:border-gold-400 peer-checked:bg-gold-400/[0.04] transition-all duration-500 flex items-center gap-6">
                                            <div class="w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center text-gold-400 peer-checked:bg-gold-400 peer-checked:text-dark transition-all shadow-xl">
                                                <i class="fas fa-university text-2xl"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-lg font-black text-white italic uppercase">Bank EFT</h4>
                                                <p class="text-[10px] text-dark-muted font-black uppercase tracking-widest opacity-60">Account Transfer</p>
                                            </div>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="payment_method" value="payfast" x-model="paymentMethod" class="peer hidden">
                                        <div class="h-full bg-black/40 rounded-[2rem] p-8 border border-white/5 peer-checked:border-gold-400 peer-checked:bg-gold-400/[0.04] transition-all duration-500 flex items-center gap-6">
                                            <div class="w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center text-gold-400 peer-checked:bg-gold-400 peer-checked:text-dark transition-all shadow-xl">
                                                <i class="fab fa-stripe text-2xl"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-lg font-black text-white italic uppercase">Stripe Online</h4>
                                                <p class="text-[10px] text-dark-muted font-black uppercase tracking-widest opacity-60">Visa / Mastercard</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <!-- Bank Accounts Styled -->
                                <div x-show="paymentMethod === 'eft'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-8">
                                    <div class="flex items-center justify-between mb-4 mt-8">
                                        <h5 class="text-[11px] font-black uppercase tracking-[0.4em] text-gold-400 italic">Official Settlement Channels</h5>
                                        <span class="text-[8px] font-black text-dark-muted uppercase tracking-widest bg-white/5 px-3 py-1 rounded-full border border-white/5">Tap to Copy Account</span>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        @foreach([
                                            ['FNB', 'Moin Hardware', '62866895166', '628'],
                                            ['FNB', 'JB Builder Choice', '63070014740', '630'],
                                            ['Standard Bank', 'Moin Hardware', '272322091', '051']
                                        ] as [$bank, $name, $acc, $code])
                                            <div class="relative p-6 bg-gradient-to-br from-white/[0.03] to-transparent border border-white/10 rounded-[2rem] hover:border-gold-400/40 transition-all group cursor-pointer overflow-hidden shadow-2xl"
                                                @click="copyToClipboard('{{ $acc }}', '{{ $bank }} Account')">

                                                <!-- Bank Aura -->
                                                <div class="absolute -top-12 -right-12 w-24 h-24 bg-white/5 blur-2xl rounded-full group-hover:bg-gold-400/10 transition-colors"></div>

                                                <div class="relative z-10">
                                                    <div class="flex items-center justify-between mb-6">
                                                        <div class="px-3 py-1 bg-white/5 rounded-lg border border-white/5 group-hover:border-gold-400/30 transition-all">
                                                            <span class="text-[9px] font-black text-gold-400 uppercase tracking-widest">{{ $bank }}</span>
                                                        </div>
                                                        <i class="fas fa-copy text-white/10 group-hover:text-gold-400 transition-colors text-sm"></i>
                                                    </div>

                                                    <p class="text-[10px] font-black text-dark-muted uppercase tracking-tighter mb-1 opacity-60">Beneficiary</p>
                                                    <p class="text-xs font-black text-white uppercase italic mb-3 leading-tight">{{ $name }}</p>

                                                    <p class="text-[10px] font-black text-dark-muted uppercase tracking-tighter mb-1 opacity-60">Account Number</p>
                                                    <p class="text-xl font-black text-white tracking-[0.1em] font-mono group-hover:text-gold-400 transition-colors">{{ $acc }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Instructional Message -->
                                    <div class="mt-8 p-8 bg-gold-400/[0.03] border border-gold-400/20 rounded-[2.5rem] flex items-start gap-6 shadow-2xl relative overflow-hidden">
                                        <div class="absolute top-0 right-0 p-4 opacity-5">
                                            <i class="fas fa-receipt text-6xl text-gold-400"></i>
                                        </div>
                                        <div class="w-14 h-14 rounded-2xl bg-gold-400/10 flex items-center justify-center text-gold-400 flex-shrink-0 border border-gold-400/20 shadow-inner">
                                            <i class="fas fa-circle-info text-2xl"></i>
                                        </div>
                                        <div class="space-y-2">
                                            <h4 class="text-xs font-black uppercase tracking-widest text-white italic">Settlement Instructions</h4>
                                            <p class="text-[10px] font-medium tracking-wide leading-relaxed text-gray-400">
                                                Please utilize the account details above to finalize your transaction. Use your <strong>Order Number</strong> as the primary reference. <span class="text-gold-400 font-black">Once funds are cleared, your confirmation documentation will be dispatched.</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- POP Upload Card - Decoupled & Premium -->
                            <div x-show="paymentMethod === 'eft'"
                                x-transition:enter="transition ease-out duration-500 delay-100"
                                x-transition:enter-start="opacity-0 translate-y-8"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="card-dark rounded-[3rem] p-10 border-white/5 bg-gradient-to-br from-white/[0.02] to-transparent shadow-3xl relative overflow-hidden group">

                                <div class="relative z-10">
                                    <div class="flex flex-col lg:flex-row items-center gap-12">
                                        <div class="flex-1 text-center lg:text-left">
                                            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-gold-400/10 border border-gold-400/20 rounded-full mb-6">
                                                <i class="fas fa-shield-check text-[10px] text-gold-400"></i>
                                                <span class="text-[9px] font-black uppercase tracking-widest text-gold-400 italic">Financial Verification Node</span>
                                            </div>
                                            <h4 class="text-2xl font-black text-white italic uppercase mb-3">Settlement <span class="gradient-text">Documentation</span></h4>
                                            <p class="text-xs text-dark-muted font-medium mb-8 max-w-sm mx-auto lg:mx-0 leading-relaxed italic">
                                                Upload your Proof of Payment (JPG, PNG, PDF) to bypass manual audit delays. Visual confirmation accelerates your dispatch queue priority.
                                            </p>

                                            <!-- Interactive File Tray -->
                                            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4">
                                                <div x-show="fileName" class="px-6 py-4 bg-gold-400/10 border border-gold-400/30 rounded-2xl flex items-center gap-4 animate-bounce-slow">
                                                    <div class="w-8 h-8 rounded-full bg-gold-400 flex items-center justify-center text-dark text-xs">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-[8px] font-black uppercase tracking-widest text-dark-muted mb-0.5">Linked Artifact</p>
                                                        <p class="text-[10px] font-black text-white truncate max-w-[150px]" x-text="fileName"></p>
                                                    </div>
                                                </div>
                                                <div x-show="!fileName" class="px-6 py-4 bg-white/[0.03] border border-white/10 rounded-2xl flex items-center gap-4 opacity-40">
                                                    <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-dark-muted text-xs">
                                                        <i class="fas fa-upload"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-[8px] font-black uppercase tracking-widest text-dark-muted mb-0.5">Idle Pipeline</p>
                                                        <p class="text-[10px] font-black text-dark-muted">No POP Documentation</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="w-full lg:w-1/3">
                                            <label class="relative block group cursor-pointer">
                                                <input type="file" name="payment_screenshot" class="hidden" @change="validateFile($event)">
                                                <div class="aspect-square rounded-[2.5rem] bg-black/40 border-2 border-dashed border-white/10 group-hover:border-gold-400/50 transition-all flex flex-col items-center justify-center p-8 text-center bg-gradient-to-t from-white/[0.02] to-transparent shadow-inner">
                                                    <div class="w-20 h-20 rounded-full bg-gold-400/10 border border-gold-400/20 flex items-center justify-center text-gold-400 text-3xl mb-4 group-hover:scale-110 transition-transform group-hover:bg-gold-400 group-hover:text-dark shadow-[0_0_30px_rgba(234,179,8,0.1)] group-hover:shadow-[0_0_40px_rgba(234,179,8,0.3)]">
                                                        <i class="fas fa-cloud-arrow-up"></i>
                                                    </div>
                                                    <h5 class="text-xs font-black text-white uppercase italic mb-1">Select File</h5>
                                                    <p class="text-[8px] font-black text-dark-muted uppercase tracking-widest">DRAG & DROP READY</p>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Background Accents -->
                                <div class="absolute bottom-0 right-0 w-64 h-64 bg-gold-400/5 blur-[100px] rounded-full -mb-32 -mr-32"></div>
                            </div>

                            <div class="flex justify-between items-center pt-6">
                                <button type="button" @click="step = 1" class="text-dark-muted hover:text-white transition text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                                    <i class="fas fa-arrow-left"></i> Address Revision
                                </button>
                                <button type="button" @click="step = 3" class="btn-gold px-16 py-5 text-xs font-black uppercase tracking-[0.2em] rounded-2xl group shadow-2xl">
                                    Fulfillment Review <i class="fas fa-arrow-right ml-3 group-hover:translate-x-2 transition-transform"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Fulfillment & Logistics -->
                        <div x-show="step === 3" x-transition.opacity.duration.500ms class="card-dark rounded-[2.5rem] p-8 border-white/5 bg-gradient-to-br from-white/[0.04] to-transparent shadow-2xl">
                            <div class="flex items-center justify-between mb-10 border-b border-white/5 pb-6">
                                <div>
                                    <h3 class="text-2xl font-black text-white italic uppercase tracking-tight">Fulfillment <span class="text-gold-400">Logistics</span></h3>
                                    <p class="text-[9px] font-black uppercase tracking-[0.3em] text-dark-muted mt-1">Operational Dispatch Settings</p>
                                </div>
                                <div class="w-14 h-14 rounded-2xl bg-gold-400/10 border border-gold-400/20 flex items-center justify-center text-gold-400 text-xl">
                                    <i class="fas fa-truck-ramp-box"></i>
                                </div>
                            </div>

                            <!-- Dispatch Selection -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-dark-muted ml-1">Dispatch Hub</label>
                                    <div class="relative group">
                                        <select name="store_id" id="store_id" required
                                            class="w-full bg-black/40 border border-white/10 rounded-2xl px-6 py-4 text-sm text-gray-200 focus:outline-none focus:border-gold-400/50 appearance-none transition-all shadow-inner font-bold italic uppercase tracking-wider">
                                            @foreach($stores as $store)
                                                <option value="{{ $store->id }}" data-lat="{{ $store->lat }}" data-lng="{{ $store->lng }}" {{ $loop->first ? 'selected' : '' }}>
                                                    {{ $store->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-gold-400 opacity-40 group-hover:opacity-100 transition-opacity">
                                            <i class="fas fa-warehouse text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 mt-2 ml-1">
                                        <div class="w-1.5 h-1.5 rounded-full bg-gold-400 animate-pulse"></div>
                                        <p id="store-distance" class="text-[9px] font-black text-gold-400 uppercase tracking-widest italic opacity-80">Calculating proximity...</p>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-dark-muted ml-1">Fulfillment Strategy</label>
                                    <div class="relative group">
                                        <select name="order_type" id="order_type" x-model="orderType"
                                            class="w-full bg-black/40 border border-white/10 rounded-2xl px-6 py-4 text-sm text-gray-200 focus:outline-none focus:border-gold-400/50 appearance-none transition-all shadow-inner font-bold italic uppercase tracking-wider">
                                            <option value="delivery" x-show="canDeliver">Site Delivery</option>
                                            <option value="pickup">Warehouse Pickup</option>
                                        </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Distance Restriction Alert -->
                                    <div x-show="!canDeliver" x-transition.opacity class="p-6 bg-red-400/5 border border-red-400/10 rounded-[2rem] flex items-start gap-5 mt-8 shadow-2xl border-dashed">
                                        <div class="w-12 h-12 rounded-2xl bg-red-400/10 flex items-center justify-center text-red-400 flex-shrink-0 border border-red-400/20 shadow-inner">
                                            <i class="fas fa-truck-slash text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-[11px] font-black uppercase tracking-widest text-red-400 mb-1">Logistics Boundary Warning</h4>
                                            <p class="text-[10px] font-black uppercase tracking-[0.1em] leading-relaxed text-gray-400 italic">
                                                We currently deliver orders within <span x-text="maxDeliveryKm" class="text-white"></span>KM of our regional collection points. 
                                                <span class="text-red-400/80 underline decoration-red-400/30 underline-offset-4 font-bold">Only 'Warehouse Pickup' is permitted for this requisition.</span>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="md:col-span-2 space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-dark-muted ml-1">Operations Notes <span class="opacity-30 tracking-normal ml-2">(OPTIONAL)</span></label>
                                    <textarea name="notes" rows="3"
                                        class="w-full bg-black/40 border border-white/10 rounded-3xl px-6 py-5 text-sm text-gray-200 focus:outline-none focus:border-gold-400/50 transition-all shadow-inner resize-none font-medium"
                                        placeholder="Specific site access details or logistics notes?"></textarea>
                                </div>

                            <div class="flex items-center justify-between py-6 mb-10 border-b border-white/5 text-[10px] font-black uppercase tracking-widest">
                                <button type="button" @click="step = 2" class="text-dark-muted hover:text-white transition">
                                    <i class="fas fa-chevron-left mr-2"></i> Payment Re-selection
                                </button>
                                <span class="text-dark-muted italic opacity-40">System Release v2.4</span>
                            </div>

                            <div class="bg-gold-400/5 border border-gold-400/10 rounded-[2rem] p-8 flex flex-col md:flex-row items-center justify-between gap-8">
                                <div class="flex items-center gap-6">
                                    <div class="w-20 h-20 rounded-full bg-white/[0.03] border border-white/5 flex items-center justify-center text-gold-400 text-3xl shadow-xl">
                                        <i class="fas fa-file-invoice-dollar"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-dark-muted uppercase tracking-[0.3em] mb-1">Final Order Value</p>
                                        <h4 class="text-4xl font-black text-white italic tracking-tighter">R{{ number_format($total, 2) }} <span class="text-xs text-dark-muted tracking-normal font-bold">ZAR</span></h4>
                                        <p class="text-[8px] font-black text-gold-400 uppercase tracking-widest mt-1 opacity-60 italic"><i class="fas fa-shield-check mr-1"></i> All-inclusive order pricing</p>
                                    </div>
                                </div>
                                <button type="submit"
                                    class="w-full md:w-auto btn-gold px-20 py-6 text-sm font-black uppercase tracking-[0.3em] rounded-3xl shadow-[0_25px_50px_rgba(0,0,0,0.5)] relative overflow-hidden group">
                                    <span class="relative z-10 flex items-center gap-4">
                                        Finalize Order <i class="fas fa-bolt text-xl group-hover:rotate-12 transition-transform"></i>
                                    </span>
                                </button>
                            </div>
                        </div>

                    </div>

                    <!-- Right: Quick Manifest (Enhanced) -->
                    <div class="lg:col-span-4 lg:sticky lg:top-24 space-y-8">
                        <div class="card-dark rounded-[3rem] p-8 md:p-12 border-gold-400/20 bg-gradient-to-br from-[#0c0c0c] to-black shadow-3xl relative overflow-hidden group">
                            
                            <!-- Animated Background Decor -->
                            <div class="absolute -top-24 -right-24 w-48 h-48 bg-gold-400/5 blur-[80px] rounded-full group-hover:bg-gold-400/10 transition-colors"></div>

                            <div class="flex items-center justify-between mb-10">
                                <h3 class="text-2xl font-black text-white italic tracking-tight uppercase leading-none">Quick <br><span class="gradient-text">Manifest</span></h3>
                                <span class="px-3 py-1 bg-white/5 border border-white/10 rounded-full text-[9px] font-black text-dark-muted uppercase tracking-widest">{{ count($products) }} Modules</span>
                            </div>

                            <!-- Expanded Item List -->
                            <div class="space-y-6 mb-10 pb-10 border-b border-white/5 max-h-[600px] overflow-y-auto custom-scrollbar pr-4">
                                @foreach($products as $product)
                                    <div class="flex items-center gap-5 group/item">
                                        <div class="w-16 h-16 rounded-2xl overflow-hidden flex-shrink-0 border border-white/10 group-hover/item:border-gold-400/40 transition-all shadow-xl">
                                            @php
                                                $imgCheckSrc = 'images/placeholder.webp';
                                                if ($product->image && file_exists(public_path($product->image))) {
                                                    $imgCheckSrc = implode('/', array_map('rawurlencode', explode('/', $product->image)));
                                                }
                                            @endphp
                                            <img src="{{ asset($imgCheckSrc) }}" class="w-full h-full object-cover group-hover/item:scale-110 transition-transform duration-500" alt="product" loading="lazy">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-black text-white italic uppercase tracking-tight group-hover/item:text-gold-400 transition-colors" title="{{ $product->name }}">{{ $product->name }}</p>
                                            <div class="flex items-center gap-3 mt-1.5">
                                                <span class="text-[9px] font-black uppercase tracking-widest text-dark-muted">Qty: {{ $product->cart_quantity }}</span>
                                                <span class="w-1 h-1 rounded-full bg-white/10"></span>
                                                <span class="text-[10px] font-black text-white italic">R{{ number_format($product->price, 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="text-right flex-shrink-0">
                                            <p class="text-[8px] font-black uppercase tracking-widest text-dark-muted mb-1 opacity-50">Line Total</p>
                                            <p class="text-sm font-black text-white italic tracking-tighter">R{{ number_format($product->cart_subtotal, 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="space-y-6 pt-10 border-t border-white/5">
                                <p class="text-[11px] font-black uppercase tracking-[0.3em] text-dark-muted italic mb-2 opacity-50">Total Order Value</p>
                                <div class="flex flex-col">
                                    <p class="text-5xl font-black text-gold-400 italic tracking-tighter leading-none mb-6">
                                        R{{ number_format($total, 2) }}
                                    </p>
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-[1px] bg-gold-400/20"></div>
                                        <span class="text-[8px] font-black text-white/40 uppercase tracking-[0.2em] whitespace-nowrap italic">All-Inclusive Order Pricing</span>
                                        <div class="flex-1 h-[1px] bg-white/5"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Terminal Security Footer -->
                        <div class="px-8 flex flex-col items-center gap-4 text-dark-muted">
                            <i class="fas fa-fingerprint text-3xl opacity-20"></i>
                            <div class="text-center">
                                <p class="text-[9px] font-black uppercase tracking-[0.3em]">End-to-End Encryption Terminal</p>
                                <p class="text-[7px] font-bold opacity-40 uppercase tracking-widest mt-1">PCI Level 1 Certified Node | Jabulani Secure-Core</p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        function copyToClipboard(text, label) {
            navigator.clipboard.writeText(text).then(() => {
                window.showToast(label + ' Copied to Clipboard', 'info');
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const storeSelect = document.getElementById('store_id');
            const distanceText = document.getElementById('store-distance');
            const checkoutLat = document.getElementById('checkout-lat');
            const checkoutLng = document.getElementById('checkout-lng');
            const orderTypeSelect = document.getElementById('order_type');
            
            let userLat = null, userLng = null;
            const maxDeliveryKm = {{ \App\Models\Setting::where('key', 'max_delivery_km')->first()?->value ?? 300 }};

            function haversine(lat1, lon1, lat2, lon2) {
                const R = 6371;
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLon = (lon2 - lon1) * Math.PI / 180;
                const a = Math.sin(dLat / 2) ** 2 + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dLon / 2) ** 2;
                return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            }

            function updateProximity() {
                // If coordinates are missing, we can't do distance check
                if (!userLat || !userLng) {
                    const alpineData = document.querySelector('[x-data]').__x.$data;
                    alpineData.canDeliver = true; // Default to true if unknown, backend will re-verify
                    distanceText.textContent = 'ADDRESS LOGISTICS PENDING COORDINATES';
                    return;
                }
                
                const selectedOpt = storeSelect.options[storeSelect.selectedIndex];
                const sLat = parseFloat(selectedOpt.getAttribute('data-lat'));
                const sLng = parseFloat(selectedOpt.getAttribute('data-lng'));

                if (sLat && sLng) {
                    const dist = haversine(userLat, userLng, sLat, sLng);
                    distanceText.textContent = `OPTIMAL DISTANCE DETECTED: ~${dist.toFixed(1)}KM`;
                    
                    const alpineData = document.querySelector('[x-data]').__x.$data;
                    alpineData.distance = dist;
                    alpineData.canDeliver = dist <= maxDeliveryKm;

                    if (dist > maxDeliveryKm) {
                        alpineData.orderType = 'pickup';
                    }
                }
            }

            window.updateProximity = updateProximity;

            function triggerGeolocation(manual = false) {
                if (navigator.geolocation) {
                    distanceText.textContent = 'RECIPIENT COORDINATES ACQUIRING...';
                    navigator.geolocation.getCurrentPosition(pos => {
                        userLat = pos.coords.latitude;
                        userLng = pos.coords.longitude;
                        checkoutLat.value = userLat;
                        checkoutLng.value = userLng;
                        
                        // Auto-select nearest store
                        let nearest = null, minDist = Infinity;
                        Array.from(storeSelect.options).forEach(opt => {
                            const sLat = parseFloat(opt.getAttribute('data-lat')), sLng = parseFloat(opt.getAttribute('data-lng'));
                            if (sLat && sLng) {
                                const d = haversine(userLat, userLng, sLat, sLng);
                                if (d < minDist) { minDist = d; nearest = opt.value; }
                            }
                        });

                        if (nearest) storeSelect.value = nearest;
                        updateProximity();
                        
                        if (manual) {
                            window.showToast('Location Synchronized Successfully', 'success');
                            // If we had a geocoding service, we'd fill the address here
                            document.getElementById('customer_address').value = "Detected Location (" + userLat.toFixed(4) + ", " + userLng.toFixed(4) + ")";
                        }
                    }, err => {
                        console.log('Location access denied');
                        distanceText.textContent = 'LOCATION ACCESS REQUIRED FOR SMART LOGISTICS';
                        if (manual) window.showToast('Location Access Denied', 'error');
                        
                        // Random store fallback if proximity fails but they want to finalize
                        if (!storeSelect.value) {
                            storeSelect.selectedIndex = Math.floor(Math.random() * storeSelect.length);
                        }
                    });
                }
            }

            window.triggerGeolocation = triggerGeolocation;
            storeSelect.addEventListener('change', updateProximity);

            // Initial trigger
            triggerGeolocation(false);
        });
    </script>
@endpush