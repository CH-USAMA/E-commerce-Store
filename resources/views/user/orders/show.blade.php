@extends('layouts.frontend')

@section('title', 'Order #' . $order->order_number . ' - Jabulani Group')

@section('content')
    <!-- Page Header -->
    <div class="relative py-16 overflow-hidden bg-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-left">
            <h1 class="text-3xl lg:text-5xl font-black mb-2 tracking-tight italic text-white uppercase">Order <span class="gradient-text">Details</span></h1>
            <nav class="flex justify-center sm:justify-start items-center gap-2 text-[10px] font-black uppercase tracking-widest text-dark-muted">
                <a href="{{ route('home') }}" class="hover:text-gold-400 transition">Home</a>
                <span class="w-1 h-1 rounded-full bg-gold-400/50"></span>
                <a href="{{ route('user.orders.index') }}" class="hover:text-gold-400 transition">My Orders</a>
                <span class="w-1 h-1 rounded-full bg-gold-400/50"></span>
                <span class="text-gray-400">#{{ $order->order_number }}</span>
            </nav>
        </div>
    </div>

    <div class="bg-[#050505] min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">

                <!-- Sidebar Navigation -->
                <aside class="w-full lg:w-72 flex-shrink-0">
                    <div
                        class="card-dark rounded-[2rem] border-white/5 bg-gradient-to-b from-white/[0.03] to-transparent sticky top-32">
                        <div class="p-8 border-b border-white/5 flex flex-col items-center text-center">
                            <div class="relative mb-6">
                                <div
                                    class="w-20 h-20 rounded-full bg-gold-400/10 border border-gold-400/20 flex items-center justify-center shadow-2xl">
                                    <i class="fas fa-user text-gold-400 text-3xl"></i>
                                </div>
                                <div
                                    class="absolute right-0 bottom-0 w-6 h-6 rounded-full bg-green-500 border-4 border-dark shadow-xl">
                                </div>
                            </div>
                            <h3 class="text-xl font-black text-white italic tracking-tight">{{ explode(' ', auth()->user()->name)[0] }}</h3>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gold-400 mt-2">Active Customer</p>
                        </div>

                        <nav class="p-4 space-y-2">
                            <a href="{{ route('user.dashboard') }}"
                                class="flex items-center gap-4 px-6 py-4 rounded-xl text-[11px] font-black uppercase tracking-widest text-dark-muted hover:text-white hover:bg-white/5 transition-all duration-300">
                                <i class="fas fa-th-large text-sm"></i> Dashboard
                            </a>
                            <a href="{{ route('user.orders.index') }}"
                                class="flex items-center gap-4 px-6 py-4 rounded-xl text-[11px] font-black uppercase tracking-widest bg-white/5 text-gold-400 border border-gold-400/20 shadow-xl transition-all duration-300">
                                <i class="fas fa-shopping-bag text-sm"></i> My Orders
                            </a>
                            <a href="{{ route('user.notifications.index') }}"
                                class="flex items-center gap-4 px-6 py-4 rounded-xl text-[11px] font-black uppercase tracking-widest text-dark-muted hover:text-white hover:bg-white/5 transition-all duration-300">
                                <i class="fas fa-bell text-sm"></i> Alert Center
                            </a>
                            <div class="h-px bg-white/5 my-4 mx-4"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-4 px-6 py-4 rounded-xl text-[11px] font-black uppercase tracking-widest text-red-400/50 hover:text-white hover:bg-red-500/10 transition-all duration-300">
                                    <i class="fas fa-sign-out-alt text-sm"></i> Log Out
                                </button>
                            </form>
                        </nav>
                        
                        <div class="p-8 border-t border-white/5 flex flex-col items-center text-center">
                            <a href="{{ route('order.track', ['order_number' => $order->order_number]) }}" class="btn-gold w-full py-4 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-2xl flex items-center justify-center gap-3">
                                <i class="fas fa-location-arrow"></i> Track Order
                            </a>
                        </div>
                    </div>
                </aside>

                <!-- Order Details -->
                <div class="flex-1 space-y-8">
                    
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
                        $statusLabels = ['Order Placed','Processing',$order->order_type == "pickup" ? "Ready for Pickup" : "Out for Delivery",$cancelled ? "Cancelled" : "Completed"];
                    @endphp

                    <!-- Order Status -->
                    <div class="card-dark rounded-[2.5rem] p-10 border-white/5 bg-gradient-to-br from-white/[0.02] to-transparent shadow-2xl relative overflow-hidden">
                        <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-12">
                            <div>
                                <h3 class="text-xl font-black text-white italic tracking-tight uppercase">Order <span class="text-gold-400">Status</span></h3>
                                <p class="text-[9px] font-black uppercase tracking-widest text-dark-muted mt-1 italic">Real-time update for order #{{ $order->order_number }}</p>
                            </div>
                            <span class="px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-widest italic border {{ $order->status === 'completed' ? 'bg-green-500/10 text-green-400 border-green-500/20 shadow-[0_0_20px_rgba(34,197,94,0.1)]' : ($cancelled ? 'bg-red-500/10 text-red-400 border-red-500/20 shadow-[0_0_20px_rgba(239,68,68,0.1)]' : 'bg-gold-400/10 text-gold-400 border-gold-400/20 shadow-[0_0_20px_rgba(245,197,24,0.15)]') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        <!-- Progress visualizer -->
                        <div class="relative py-12 px-4 max-w-3xl mx-auto">
                            <!-- Background track -->
                            <div class="absolute top-1/2 -translate-y-1/2 left-0 right-0 h-[2px] bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full transition-all duration-1000" style="background:{{ $color }};width:{{ min(100, ($step-1)*33.33) }}%"></div>
                            </div>
                            <!-- Milestones -->
                            <div class="relative flex justify-between">
                                @foreach($statusLabels as $i => $label)
                                    <div class="flex flex-col items-center group/step" style="width:20%">
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-sm font-black z-10 border-2 transition-all duration-500 {{ ($i+1) <= $step ? 'border-transparent shadow-2xl' : 'border-white/5 bg-dark' }}"
                                             style="{{ ($i+1) <= $step ? 'background:'.$color.';color:'.($cancelled&&($i+1)==$step?'white':'#0a0a0a') : 'color:#333' }}">
                                            @if(($i+1) < $step)<i class="fas fa-check text-xs"></i>
                                            @elseif(($i+1) == $step)<i class="fas fa-{{ $cancelled ? 'times' : 'shipping-fast' }} {{ $cancelled ? '' : 'animate-pulse' }}"></i>
                                            @else{{ $i+1 }}@endif
                                        </div>
                                        <p class="text-[8px] font-black uppercase tracking-widest text-center mt-6 transition-colors duration-500 {{ ($i+1) <= $step ? 'text-white' : 'text-dark-muted opacity-40' }}">{{ $label }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Order Info Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Delivery Info -->
                        <div class="card-dark rounded-[2.5rem] p-10 border-white/5 bg-gradient-to-b from-white/[0.03] to-transparent">
                            <h4 class="text-[10px] font-black uppercase tracking-widest text-dark-muted italic border-l-2 border-gold-400 pl-4 mb-8">Delivery Info</h4>
                            <div class="space-y-6">
                                <div>
                                    <p class="text-[9px] font-black uppercase tracking-widest text-dark-muted mb-2 opacity-50">Customer Name</p>
                                    <p class="text-lg font-black text-white italic tracking-tighter">{{ $order->customer_name }}</p>
                                </div>
                                <div class="flex flex-wrap gap-8">
                                    <div>
                                        <p class="text-[9px] font-black uppercase tracking-widest text-dark-muted mb-1 opacity-50">Email</p>
                                        <p class="text-xs font-bold text-gray-300">{{ $order->customer_email }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black uppercase tracking-widest text-dark-muted mb-1 opacity-50">Phone</p>
                                        <p class="text-xs font-bold text-gray-300">{{ $order->customer_phone }}</p>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black uppercase tracking-widest text-dark-muted mb-2 opacity-50">Delivery Address</p>
                                    <p class="text-xs text-gray-300 leading-relaxed font-medium bg-black/40 p-4 rounded-2xl border border-white/5 shadow-inner">
                                        {{ $order->customer_address }}<br>
                                        <span class="text-white font-black">{{ $order->customer_city }}, {{ $order->customer_postal_code }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="card-dark rounded-[2.5rem] p-10 border-white/5 bg-gradient-to-b from-white/[0.03] to-transparent">
                            <h4 class="text-[10px] font-black uppercase tracking-widest text-dark-muted italic border-l-2 border-gold-400 pl-4 mb-8">Order Information</h4>
                            <div class="space-y-8">
                                <div class="grid grid-cols-2 gap-8 pb-8 border-b border-white/5">
                                    <div>
                                        <p class="text-[9px] font-black uppercase tracking-widest text-dark-muted mb-2 opacity-50">Order Type</p>
                                        <div class="flex items-center gap-3 text-white font-black italic">
                                            <i class="fas fa-truck text-gold-400 opacity-40"></i>
                                            <span class="uppercase tracking-widest text-xs">{{ $order->order_type }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black uppercase tracking-widest text-dark-muted mb-2 opacity-50">Payment</p>
                                        <div class="flex items-center gap-3 text-white font-black italic">
                                            <i class="fas fa-credit-card text-gold-400 opacity-40"></i>
                                            <span class="uppercase tracking-widest text-xs">{{ $order->payment_method }}</span>
                                        </div>
                                    </div>
                                </div>
                                @if($order->store)
                                <div class="pt-2">
                                    <p class="text-[9px] font-black uppercase tracking-widest text-dark-muted mb-3 opacity-50">Fulfillment Store</p>
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-gold-400 flex-shrink-0">
                                            <i class="fas fa-store text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-white italic">{{ $order->store->name }}</p>
                                            <p class="text-[9px] font-bold text-dark-muted uppercase tracking-widest mt-1">Your Local Store</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if($order->notes)
                                <div class="bg-gold-400/[0.02] p-5 rounded-2xl border border-gold-400/10 border-dashed">
                                    <p class="text-[8px] font-black uppercase tracking-widest text-gold-400 mb-2">Order Notes</p>
                                    <p class="text-[10px] text-gray-400 italic leading-relaxed">{{ $order->notes }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Items Ordered -->
                    <div class="card-dark rounded-[2.5rem] p-10 border-white/5">
                        <h4 class="text-[10px] font-black uppercase tracking-widest text-dark-muted italic mb-8 pb-4 border-b border-white/5">Items Ordered</h4>
                        <div class="space-y-4">
                            @foreach($order->items as $item)
                                <div class="group flex items-center gap-6 p-6 rounded-3xl border border-white/5 bg-black/40 hover:border-gold-400/30 transition-all duration-500 shadow-inner">
                                    <div class="w-16 h-16 rounded-xl overflow-hidden border border-white/10 flex-shrink-0 group-hover:border-gold-400/40 transition-all duration-500">
                                        @if($item->product)
                                            @php
                                                $imgOrderSrc = 'images/placeholder.webp';
                                                if ($item->product->image && file_exists(public_path($item->product->image))) {
                                                    $imgOrderSrc = implode('/', array_map('rawurlencode', explode('/', $item->product->image)));
                                                }
                                            @endphp
                                            <img src="{{ asset($imgOrderSrc) }}" class="w-full h-full object-cover grayscale opacity-50 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-700">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-white/5 text-dark-muted"><i class="fas fa-box-archive"></i></div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        @if($item->product)
                                            <a href="{{ route('product.detail', $item->product->slug) }}" class="text-base font-black text-white italic hover:text-gold-400 transition-all tracking-tighter">{{ $item->product->name }}</a>
                                        @else
                                            <span class="text-sm font-black text-dark-muted uppercase tracking-widest italic opacity-50">Product no longer available</span>
                                        @endif
                                        <p class="text-[10px] font-black uppercase tracking-widest text-dark-muted mt-1 italic">{{ $item->quantity }} x R {{ number_format($item->price, 2) }}</p>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <p class="text-[9px] font-black uppercase tracking-widest text-dark-muted mb-1 opacity-50">Total</p>
                                        <p class="text-lg font-black text-white italic tracking-tighter">R {{ number_format($item->price * $item->quantity, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Payment Summary -->
                        <div class="mt-12 flex flex-col md:flex-row justify-end items-end gap-12 pt-12 border-t border-white/5">
                            <div class="space-y-4 text-right">
                                <div class="flex justify-end gap-12 text-[9px] font-black uppercase tracking-widest text-dark-muted">
                                    <span>Order Subtotal</span>
                                    <span class="text-gray-300">R {{ number_format($order->total, 2) }}</span>
                                </div>
                                <div class="hidden flex justify-end gap-12 text-[9px] font-black uppercase tracking-widest text-gold-400">
                                    <span>VAT (0%)</span>
                                    <span>R 0.00</span>
                                </div>
                                <div class="flex justify-end gap-12 pt-6 border-t border-white/5">
                                    <span class="text-[11px] font-black uppercase tracking-[0.3em] text-white italic">Total Amount</span>
                                    <span class="text-5xl font-black text-gold-400 italic tracking-tighter">R {{ number_format($order->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
