@extends('layouts.frontend')

@section('title', 'Track Your Order - Jabulani Group')

@section('content')
    <!-- Page Header -->
    <div class="relative py-24 overflow-hidden bg-dark">
        <div class="absolute inset-0 opacity-10">
            <img src="{{ asset('images/qumbu_special_compressed.webp') }}" class="w-full h-full object-cover"
                alt="Tracking Hero">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-5xl lg:text-7xl font-black mb-6 tracking-tight italic">Live <span
                    class="gradient-text">Tracking</span></h1>
            <p class="text-gold-400 font-bold uppercase tracking-[0.4em] text-xs mb-8">Real-time status of your order</p>
            <nav
                class="flex justify-center items-center gap-2 text-[10px] font-black uppercase tracking-widest text-dark-muted">
                <a href="{{ route('home') }}" class="hover:text-gold-400 transition">Home</a>
                <span class="w-1 h-1 rounded-full bg-gold-400/50"></span>
                <span class="text-gray-400">Order Tracking</span>
            </nav>
        </div>
    </div>

    <div class="bg-[#050505] min-h-screen py-24">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Tracking Search -->
            <div
                class="card-dark rounded-[3.5rem] p-12 md:p-16 border-white/5 bg-gradient-to-br from-[#111] to-dark shadow-2xl relative overflow-hidden group mb-16">
                <div class="absolute -right-16 -top-16 w-64 h-64 bg-gold-400/5 rounded-full blur-[100px]"></div>

                <div class="relative z-10">
                    <div class="text-center mb-12">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gold-400/10 border border-gold-400/20 mb-6">
                            <i class="fas fa-barcode text-2xl text-gold-400"></i>
                        </div>
                        <h2 class="text-3xl font-black text-white italic tracking-tight uppercase">Enter <span
                                class="text-gold-400">Order Number</span></h2>
                        <p class="text-dark-muted text-xs font-black uppercase tracking-widest mt-2 opacity-60 italic">
                            Check your order and delivery status</p>
                    </div>

                    <form action="/track-order" method="POST" class="max-w-2xl mx-auto">
                        @csrf
                        <div
                            class="relative flex flex-col sm:flex-row gap-4 p-2 bg-black/40 border border-white/10 rounded-[2rem] shadow-inner">
                            <input type="text" name="order_number"
                                class="flex-1 bg-transparent text-white px-8 py-4 focus:outline-none font-bold text-lg placeholder-dark-muted uppercase tracking-wider"
                                placeholder="e.g. JB-2024..." value="{{ old('order_number', $order->order_number ?? '') }}"
                                required>
                            <button type="submit"
                                class="btn-gold px-12 py-5 rounded-[1.5rem] text-[11px] font-black uppercase tracking-widest shadow-2xl flex items-center justify-center gap-3">
                                <i class="fas fa-search text-sm"></i> Track Order
                            </button>
                        </div>
                        @if($errors->has('order_number'))
                            <p class="text-red-400 text-[10px] font-black uppercase tracking-widest mt-4 ml-6"><i
                                    class="fas fa-exclamation-triangle mr-2"></i> {{ $errors->first('order_number') }}</p>
                        @endif
                    </form>
                </div>
            </div>

            @if(isset($order))
                <!-- Tracking Results -->
                <div class="space-y-12">

                    <!-- Status Overview -->
                    <div class="card-dark rounded-[3rem] p-10 border-gold-400/20 shadow-2xl relative overflow-hidden">
                        <div
                            class="flex flex-col md:flex-row justify-between items-center gap-8 border-b border-white/5 pb-10 mb-10">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-dark-muted mb-2">Order
                                    Number</p>
                                <h3 class="text-4xl font-black text-white italic tracking-tighter">#{{ $order->order_number }}
                                </h3>
                                <p class="text-[9px] font-black uppercase tracking-widest text-gold-400 mt-2">Placed on
                                    {{ $order->created_at->format('M d, Y') }}
                                </p>
                            </div>
                            <div class="text-center md:text-right">
                                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-dark-muted mb-3">Order
                                    Status</p>
                                <span
                                    class="px-8 py-3 rounded-full text-[11px] font-black uppercase tracking-[0.2em] shadow-2xl italic {{ $order->status === 'delivered' ? 'bg-green-500/10 text-green-400 border border-green-500/20' : ($order->status === 'cancelled' ? 'bg-red-500/10 text-red-400 border border-red-500/20' : 'bg-gold-400/10 text-gold-400 border border-gold-400/20') }}">
                                    {{ $order->status }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                            <!-- Info -->
                            <div class="space-y-8">
                                <h4
                                    class="text-[10px] font-black uppercase tracking-widest text-dark-muted italic border-l-2 border-gold-400 pl-4">
                                    Order Information</h4>
                                <div class="grid grid-cols-2 gap-6">
                                    <div>
                                        <p
                                            class="text-[9px] font-black uppercase tracking-widest text-dark-muted mb-1 opacity-50">
                                            Recipient</p>
                                        <p class="text-sm font-bold text-white">{{ $order->customer_name }}</p>
                                    </div>
                                    <div>
                                        <p
                                            class="text-[9px] font-black uppercase tracking-widest text-dark-muted mb-1 opacity-50">
                                            Fulfillment Store</p>
                                        <p class="text-sm font-bold text-white">
                                            {{ $order->store->name ?? 'Regional Distribution' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p
                                            class="text-[9px] font-black uppercase tracking-widest text-dark-muted mb-1 opacity-50">
                                            Delivery Method</p>
                                        <p class="text-sm font-bold text-white uppercase">{{ $order->order_type }}</p>
                                    </div>
                                    <div>
                                        <p
                                            class="text-[9px] font-black uppercase tracking-widest text-dark-muted mb-1 opacity-50">
                                            Payment Method</p>
                                        <p class="text-sm font-bold text-white uppercase">{{ $order->payment_method }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="space-y-8">
                                <h4
                                    class="text-[10px] font-black uppercase tracking-widest text-dark-muted italic border-l-2 border-gold-400 pl-4">
                                    Shipping Address</h4>
                                <div class="bg-black/40 p-6 rounded-3xl border border-white/5 shadow-inner">
                                    <p class="text-sm text-gray-300 leading-relaxed font-medium">
                                        {{ $order->customer_address }}<br>
                                        <span class="text-white font-black">{{ $order->customer_city }},
                                            {{ $order->customer_postal_code }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Manifest -->
                    <div
                        class="card-dark rounded-[3rem] p-10 border-white/5 relative overflow-hidden bg-gradient-to-t from-black/[0.1] to-transparent">
                        <h4
                            class="text-[10px] font-black uppercase tracking-widest text-dark-muted italic mb-8 pb-4 border-b border-white/5">
                            Order Items</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-[9px] font-black uppercase tracking-widest text-dark-muted">
                                        <th class="pb-6">Item Name</th>
                                        <th class="pb-6 text-center">Quantity</th>
                                        <th class="pb-6 text-right">Price</th>
                                        <th class="pb-6 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @foreach($order->items as $item)
                                        <tr class="group hover:bg-white/[0.02] transition-all">
                                            <td class="py-6 pr-4">
                                                <div class="flex items-center gap-4">
                                                    <div
                                                        class="w-14 h-14 rounded-2xl overflow-hidden border border-white/10 flex-shrink-0 group-hover:border-gold-400/30 transition-all duration-500">
                                                        @php
                                                            $image = $item->product->image ?? '';
                                                            $imageSrc = $image ? (Str::contains($image, 'images/') ? asset($image) : asset('' . $image)) : asset('images/placeholder.webp');
                                                        @endphp
                                                        <img src="{{ $imageSrc }}"
                                                            class="w-full h-full object-cover grayscale opacity-50 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-700">
                                                    </div>
                                                    <span
                                                        class="text-sm font-bold text-white italic group-hover:text-gold-400 transition-all">{{ $item->product->name ?? 'Material Specification' }}</span>
                                                </div>
                                            </td>
                                            <td class="py-6 text-center font-black text-gray-400 italic">{{ $item->quantity }}</td>
                                            <td class="py-6 text-right font-black text-gray-400 italic">
                                                R{{ number_format($item->price, 2) }}</td>
                                            <td class="py-6 text-right font-black text-white italic tracking-tighter">
                                                R{{ number_format($item->price * $item->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3"
                                            class="pt-10 text-right text-[10px] font-black uppercase tracking-widest text-dark-muted">
                                            Subtotal</td>
                                        <td class="pt-10 text-right text-lg font-black text-white italic">
                                            R{{ number_format($order->total - $order->vat, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"
                                            class="pt-4 text-right text-[10px] font-black uppercase tracking-widest text-dark-muted">
                                            VAT (0%)</td>
                                        <td class="text-end text-white">
                                            R{{ number_format($order->vat, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"
                                            class="pt-10 text-right text-[10px] font-black uppercase tracking-widest text-white border-t border-white/5 mt-6">
                                            Total Amount</td>
                                        <td
                                            class="pt-10 text-right text-4xl font-black text-gold-400 italic tracking-tighter border-t border-white/5 mt-6">
                                            R{{ number_format($order->total, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        @if($order->notes)
                            <div class="mt-12 p-8 bg-gold-400/[0.03] rounded-[2rem] border border-gold-400/20 border-dashed">
                                <h5 class="text-[10px] font-black uppercase tracking-widest text-gold-400 mb-4 italic">Order
                                    Notes:</h5>
                                <p class="text-sm text-gray-400 italic leading-relaxed">{{ $order->notes }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Contact Footer -->
                    <div class="text-center pt-12">
                        <p class="text-[10px] font-black uppercase tracking-widest text-dark-muted mb-6 italic">Need help with your delivery?</p>
                        <div class="flex justify-center gap-6">
                            <a href="https://wa.me/27660684585"
                                class="btn-outline-gold px-10 py-4 text-[11px] rounded-full">Contact Support</a>
                        </div>
                    </div>

                </div>
            @endif
        </div>
    </div>
@endsection