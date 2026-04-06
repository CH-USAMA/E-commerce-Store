@extends('layouts.user')

@section('title', 'Order #' . $order->order_number)

@section('content')

    @php
        $statusMap = [
            'pending' => 1,
            'processing' => 2,
            'shipped' => 3,
            'out_for_delivery' => 3,
            'ready_for_pickup' => 3,
            'delivered' => 4,
            'completed' => 4,
            'cancelled' => 4,
        ];
        $step = $statusMap[$order->status] ?? 1;
        $cancelled = $order->status === 'cancelled';
        $color = $cancelled ? 'rgb(239,68,68)' : '#f5c518';
        $statusLabels = ['Order Placed','Processing',$order->order_type == "pickup" ? "Ready for Pickup" : "In Transit",$cancelled ? "Cancelled" : "Delivered"];
    @endphp

    <!-- Order Detail Header -->
    <div class="row g-6 mb-12">
        <div class="col-md-8">
            <header class="flex flex-col gap-2">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-1 bg-gold-400 rounded-full"></span>
                    <span class="text-[10px] font-black uppercase tracking-[.4em] text-gold-400">Order Information</span>
                </div>
                <h2 class="text-4xl lg:text-5xl font-black text-white italic tracking-tighter uppercase mb-2">Order <span class="gradient-text">#{{ $order->order_number }}</span></h2>
                <div class="flex items-center gap-4 text-[10px] font-black uppercase tracking-widest text-dark-muted italic">
                    <span>Placed on {{ $order->created_at->format('M d, Y') }} at {{ $order->created_at->format('H:i') }}</span>
                    <span class="w-1 h-1 rounded-full bg-white/10"></span>
                    <span class="text-white">{{ $order->order_type ?: 'Store Order' }}</span>
                </div>
            </header>
        </div>
        <div class="col-md-4 flex items-center justify-end gap-4">
            <a href="{{ route('order.track', ['order_number' => $order->order_number]) }}" class="px-8 py-4 bg-white/5 border border-white/10 rounded-full text-[10px] font-black uppercase tracking-widest text-gold-400 hover:border-gold-400/30 transition-all flex items-center gap-3">
                <i class="fas fa-location-arrow"></i> Real-time Tracking
            </a>
        </div>
    </div>

    <!-- Logistics Pipeline -->
    <div class="premium-glass rounded-[3rem] p-12 border-white/5 mb-12 relative overflow-hidden">
        <div class="absolute inset-x-0 bottom-0 h-[300px] bg-gradient-to-t from-gold-400/[0.02] to-transparent opacity-50"></div>
        
        <div class="relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-center gap-10 mb-16">
                <div>
                    <h3 class="text-xs font-black uppercase tracking-[0.4em] text-white italic mb-2">Track Your Order</h3>
                    <p class="text-[9px] font-black uppercase tracking-widest text-dark-muted">Current status of your order</p>
                </div>
                <div class="flex flex-col items-end">
                    <span class="px-8 py-3 rounded-full text-[11px] font-black uppercase tracking-widest italic border {{ $order->status === 'completed' ? 'bg-green-500/10 text-green-400 border-green-500/20 shadow-[0_0_30px_rgba(34,197,94,0.15)]' : ($cancelled ? 'bg-red-500/10 text-red-400 border-red-500/20 shadow-[0_0_30px_rgba(239,68,68,0.15)]' : 'bg-gold-400/10 text-gold-400 border-gold-400/20 shadow-[0_0_30px_rgba(245,197,24,0.15)]') }}">
                        <i class="fas fa-circle text-[7px] me-3 {{ $cancelled ? '' : 'animate-pulse' }}"></i>{{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>
            </div>

            <!-- Enhanced Visual Pipeline -->
            <div class="relative py-12 px-6 max-w-5xl mx-auto">
                <div class="absolute top-1/2 -translate-y-1/2 left-0 right-0 h-[4px] bg-white/[0.03] rounded-full overflow-hidden">
                    <div class="h-full transition-all duration-1000 shadow-[0_0_15px_rgba(245,197,24,0.3)]" style="background:{{ $color }};width:{{ min(100, ($step-1)*33.33) }}%"></div>
                </div>
                
                <div class="relative flex justify-between">
                    @foreach($statusLabels as $i => $label)
                        <div class="flex flex-col items-center group/step w-1/4">
                            <div class="w-16 h-16 rounded-3xl flex items-center justify-center text-sm font-black z-10 border-2 transition-all duration-700 {{ ($i+1) <= $step ? 'border-transparent shadow-[0_15px_40px_rgba(0,0,0,0.4)]' : 'border-white/5 bg-[#0a0a0a]' }} hover:scale-110"
                                 style="{{ ($i+1) <= $step ? 'background:'.$color.';color:'.($cancelled&&($i+1)==$step?'white':'#0a0a0a') : 'color:#444' }}">
                                @if(($i+1) < $step)<i class="fas fa-check text-base"></i>
                                @elseif(($i+1) == $step)<i class="fas fa-{{ $cancelled ? 'times' : ($i == 0 ? 'receipt' : ($i == 1 ? 'cog' : ($i == 2 ? 'truck-fast' : 'circle-check'))) }} {{ $cancelled ? '' : 'animate-pulse' }}"></i>
                                @else<span class="opacity-30">{{ $i+1 }}</span>@endif
                            </div>
                            <p class="text-[9px] font-black uppercase tracking-[0.2em] text-center mt-8 transition-colors duration-700 {{ ($i+1) <= $step ? 'text-white italic' : 'text-dark-muted opacity-30' }}">{{ $label }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="row g-8 mb-12">
        <div class="col-lg-6">
            <div class="premium-glass rounded-[2.5rem] p-10 border-white/5 h-full relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-2 h-full bg-gold-400/20"></div>
                <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-dark-muted italic mb-10 pl-2">Customer Details</h4>
                
                <div class="space-y-10">
                    <div class="flex items-start gap-6">
                        <div class="w-14 h-14 rounded-2xl bg-white/5 border border-white/5 flex items-center justify-center text-gold-400/50 flex-shrink-0 group-hover:bg-gold-400/5 group-hover:text-gold-400 transition-all shadow-xl">
                            <i class="fas fa-id-card text-xl"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-widest text-dark-muted mb-1 opacity-50">Customer</p>
                            <h5 class="text-xl font-black text-white italic tracking-tighter">{{ $order->customer_name }}</h5>
                            <div class="mt-2 flex items-center gap-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                                <span>{{ $order->customer_email }}</span>
                                <span class="w-1 h-1 rounded-full bg-white/10"></span>
                                <span>{{ $order->customer_phone }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-6">
                        <div class="w-14 h-14 rounded-2xl bg-white/5 border border-white/5 flex items-center justify-center text-gold-400/50 flex-shrink-0 group-hover:bg-gold-400/5 group-hover:text-gold-400 transition-all shadow-xl">
                            <i class="fas fa-map-location-dot text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-[9px] font-black uppercase tracking-widest text-dark-muted mb-3 opacity-50">Shipping Address</p>
                            <div class="p-6 rounded-[1.5rem] bg-black/40 border border-white/5 shadow-inner">
                                <p class="text-sm text-gray-300 leading-relaxed font-medium">
                                    {{ $order->customer_address }}<br>
                                    <span class="text-white font-black italic">{{ $order->customer_city }}, {{ $order->customer_postal_code }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="premium-glass rounded-[2.5rem] p-10 border-white/5 h-full relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-2 h-full bg-gold-400/20"></div>
                <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-dark-muted italic mb-10 pl-2">Order Summary</h4>
                
                <div class="space-y-10">
                    <div class="grid grid-cols-2 gap-10">
                        <div class="flex items-start gap-5">
                            <div class="w-12 h-12 rounded-xl bg-white/5 border border-white/5 flex items-center justify-center text-gold-400/40">
                                <i class="fas fa-truck-ramp-box text-lg"></i>
                            </div>
                            <div>
                                <p class="text-[8px] font-black uppercase tracking-widest text-dark-muted mb-1 opacity-50">Method</p>
                                <p class="text-xs font-black text-white italic uppercase tracking-widest">{{ $order->order_type }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-5">
                            <div class="w-12 h-12 rounded-xl bg-white/5 border border-white/5 flex items-center justify-center text-gold-400/40">
                                <i class="fas fa-wallet text-lg"></i>
                            </div>
                            <div>
                                <p class="text-[8px] font-black uppercase tracking-widest text-dark-muted mb-1 opacity-50">Payment</p>
                                <p class="text-xs font-black text-white italic uppercase tracking-widest">{{ str_replace('_', ' ', $order->payment_method) }}</p>
                            </div>
                        </div>
                    </div>

                    @if($order->store)
                        <div class="p-8 rounded-[2rem] bg-gradient-to-br from-white/[0.02] to-transparent border border-white/5">
                            <p class="text-[8px] font-black uppercase tracking-widest text-dark-muted mb-4 opacity-50">Fulfillment Store</p>
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 rounded-2xl bg-gold-400/5 border border-gold-400/20 flex items-center justify-center text-gold-400 shadow-gold group-hover:scale-105 transition-transform">
                                    <i class="fas fa-store text-xl"></i>
                                </div>
                                <div>
                                    <h5 class="text-lg font-black text-white italic tracking-tighter uppercase leading-none">{{ $order->store->name }}</h5>
                                    <p class="text-[9px] font-bold text-dark-muted uppercase mt-2 tracking-widest">Store providing your items</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($order->notes)
                        <div class="bg-gold-400/5 p-6 rounded-2xl border border-gold-400/10 border-dashed relative group-hover:border-gold-400/30 transition-colors">
                            <i class="fas fa-quote-left absolute top-4 right-4 text-gold-400/10 text-3xl"></i>
                            <p class="text-[9px] font-black uppercase tracking-widest text-gold-400 mb-3 italic">Order Notes</p>
                            <p class="text-xs text-gray-400 italic leading-relaxed relative z-10">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Line Item Manifest -->
    <div class="premium-glass rounded-[3rem] p-12 border-white/5">
        <h4 class="text-xs font-black uppercase tracking-[0.4em] text-white italic mb-10 pb-6 border-b border-white/5">Your Items</h4>
        
        <div class="space-y-6">
            <div class="hidden md:grid grid-cols-12 gap-8 px-8 mb-4 text-[9px] font-black uppercase tracking-[0.3em] text-dark-muted border-b border-white/5 pb-6">
                <div class="col-span-6">Item Description</div>
                <div class="col-span-2 text-center">Price</div>
                <div class="col-span-2 text-center">Quantity</div>
                <div class="col-span-2 text-right pe-4">Total</div>
            </div>

            @foreach($order->items as $item)
                <div class="group grid grid-cols-1 md:grid-cols-12 items-center gap-8 p-6 md:p-8 rounded-[2.5rem] border border-white/5 bg-black/40 hover:border-gold-400/30 transition-all duration-500 hover:-translate-y-1">
                    <div class="col-span-12 md:col-span-6">
                        <div class="flex items-center gap-8">
                            <div class="w-20 h-20 rounded-3xl overflow-hidden border border-white/10 flex-shrink-0 group-hover:border-gold-400/40 transition-all duration-700 shadow-2xl relative">
                                <div class="absolute inset-0 bg-gold-400/5 group-hover:bg-transparent transition-colors"></div>
                                @if($item->product)
                                    @php
                                        $imgOrderSrc = 'images/placeholder.webp';
                                        if ($item->product->image && file_exists(public_path($item->product->image))) {
                                            $imgOrderSrc = implode('/', array_map('rawurlencode', explode('/', $item->product->image)));
                                        }
                                    @endphp
                                    <img src="{{ asset($imgOrderSrc) }}" class="w-full h-full object-cover grayscale opacity-40 group-hover:grayscale-0 group-hover:opacity-100 group-hover:scale-110 transition-all duration-1000">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-white/5 text-dark-muted"><i class="fas fa-box-archive text-2xl"></i></div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                @if($item->product)
                                    <a href="{{ route('product.detail', $item->product->slug) }}" class="text-xl font-black text-white italic hover:text-gold-400 transition-all tracking-tighter uppercase block leading-tight">{{ $item->product->name }}</a>
                                    <p class="text-[9px] font-black uppercase tracking-widest text-dark-muted mt-2 opacity-50 italic">SKU: {{ $item->product->sku ?: 'JBL-PRC-00' . $item->product->id }}</p>
                                @else
                                    <span class="text-sm font-black text-dark-muted uppercase tracking-widest italic opacity-50">Product Archive Encription: {{ $item->price }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-span-12 md:col-span-2 text-center">
                        <div class="md:hidden text-[9px] font-black uppercase text-dark-muted mb-2">Unit Price</div>
                        <p class="text-base font-black text-gray-400 italic">R {{ number_format($item->price, 2) }}</p>
                    </div>

                    <div class="col-span-12 md:col-span-2 text-center">
                         <div class="md:hidden text-[9px] font-black uppercase text-dark-muted mb-2">Units</div>
                        <span class="px-5 py-2.5 rounded-2xl bg-white/5 border border-white/10 text-white font-black italic">{{ $item->quantity }} Units</span>
                    </div>

                    <div class="col-span-12 md:col-span-2 text-right md:pe-4">
                        <div class="md:hidden text-[9px] font-black uppercase text-dark-muted mb-2">Total</div>
                        <p class="text-2xl font-black text-white italic tracking-tighter">R {{ number_format($item->price * $item->quantity, 2) }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Fiscal Summary -->
        <div class="mt-16 pt-16 border-t border-white/5 flex flex-col md:flex-row justify-between items-start gap-12">
            <div class="max-w-md">
                <div class="premium-glass p-8 rounded-[2rem] border-white/5">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gold-400 mb-4 italic"><i class="fas fa-shield-halved me-2"></i>Secure Documentation</p>
                    <p class="text-[11px] text-gray-500 leading-relaxed font-medium italic">This manifest is a digital confirmation of your transaction. For full tax invoice requirements or disputes, please contact Jabulani Group support.</p>
                </div>
            </div>
            
            <div class="w-full md:w-fit text-right">
                <div class="space-y-6">
                    <div class="flex justify-end gap-16 text-[10px] font-black uppercase tracking-[0.3em] text-dark-muted italic">
                        <span>Subtotal</span>
                        <span class="text-white">R {{ number_format($order->total, 2) }}</span>
                    </div>
                    <div class="flex justify-end gap-16 text-[10px] font-black uppercase tracking-[0.3em] text-dark-muted italic">
                        <span>Delivery</span>
                        <span class="text-green-400">R 0.00 (Free)</span>
                    </div>
                    <div class="flex justify-end gap-16 pt-10 border-t border-white/5 items-center">
                        <span class="text-xs font-black uppercase tracking-[0.5em] text-white italic">Total Amount</span>
                        <span class="text-6xl font-black text-gold-400 italic tracking-tighter shadow-gold-glow">R {{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
