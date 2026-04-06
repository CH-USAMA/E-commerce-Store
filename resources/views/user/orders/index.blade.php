@extends('layouts.user')

@section('title', 'Order History')

@section('content')

    <!-- Navigation / Statistics Grid -->
    <div class="row g-4 mb-10">
        <div class="col-md-9">
            <header class="flex flex-col gap-2">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-1 bg-gold-400 rounded-full"></span>
                    <span class="text-[10px] font-black uppercase tracking-[.4em] text-gold-400">Order History</span>
                </div>
                <h2 class="text-4xl lg:text-5xl font-black text-white italic tracking-tighter uppercase mb-0">My <span class="gradient-text">Orders</span></h2>
                <p class="text-[11px] font-black uppercase tracking-widest text-dark-muted opacity-60">List of all your past and current orders</p>
            </header>
        </div>
        <div class="col-md-3">
            <div class="premium-glass p-6 rounded-3xl border-white/5 text-center stat-card-glow">
                <p class="text-[10px] font-black uppercase tracking-widest text-dark-muted mb-2">Total Orders</p>
                <div class="text-3xl font-black text-white italic tracking-tighter leading-none">{{ $orders->total() }} Orders</div>
            </div>
        </div>
    </div>

    <!-- Active Filter View (Optional, placeholder for future) -->
    @if(request('per_page') || request('page'))
        <div class="mb-10 flex items-center justify-between p-6 rounded-2xl bg-white/5 border border-white/5">
            <div class="flex items-center gap-4">
                <span class="text-[10px] font-black uppercase tracking-widest text-dark-muted">Active View:</span>
                <span class="px-3 py-1 bg-gold-400/10 border border-gold-400/20 text-gold-400 text-[10px] font-black uppercase rounded-full">Showing {{ request('per_page', 20) }} units per page</span>
            </div>
            <a href="{{ route('user.orders.index') }}" class="text-[10px] font-black uppercase tracking-widest text-red-400 hover:text-white transition-all">Clear Filters</a>
        </div>
    @endif

    <!-- Orders Management Table -->
    <div class="premium-glass rounded-[3rem] border-white/5 overflow-hidden">
        <div class="p-10 border-b border-white/5 flex items-center justify-between">
            <h3 class="text-xs font-black uppercase tracking-widest text-white italic">Order List</h3>
            <div class="flex items-center gap-4">
                <a href="{{ route('user.orders.export') }}" class="px-6 py-3 bg-white/5 rounded-full text-[10px] font-black uppercase tracking-widest text-green-400 border border-green-500/10 hover:border-green-500/30 transition-all">
                    <i class="fas fa-file-csv me-2"></i> Download CSV
                </a>
            </div>
        </div>

        <div class="p-10 pt-0">
            @if($orders->count() > 0)
                <div class="table-responsive mt-6">
                    <table class="table table-borderless align-middle mb-0 text-white">
                        <thead>
                            <tr class="border-b border-white/5">
                                <th class="py-6 ps-4 text-[10px] font-black uppercase tracking-widest text-dark-muted">Order Number</th>
                                <th class="py-6 text-[10px] font-black uppercase tracking-widest text-dark-muted">Date</th>
                                <th class="py-6 text-[10px] font-black uppercase tracking-widest text-dark-muted">Total Amount</th>
                                <th class="py-6 text-[10px] font-black uppercase tracking-widest text-dark-muted">Status</th>
                                <th class="py-6 pe-4 text-center text-[10px] font-black uppercase tracking-widest text-dark-muted">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($orders as $order)
                                <tr class="group hover:bg-white/[0.02] transition-colors duration-300">
                                    <td class="py-8 ps-4">
                                        <div class="flex items-center gap-6">
                                            <div class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center border border-white/10 text-gold-400 opacity-60 group-hover:scale-110 transition-transform">
                                                <i class="fas fa-shopping-cart text-lg"></i>
                                            </div>
                                            <div>
                                                <div class="text-lg font-black italic tracking-tight uppercase leading-none group-hover:text-gold-400 transition-colors">#{{ $order->order_number }}</div>
                                                <div class="flex items-center gap-2 mt-2">
                                                    <span class="text-[8px] font-black uppercase tracking-widest px-2 py-0.5 rounded bg-white/5 text-dark-muted border border-white/5">{{ $order->order_type ?: 'B2B' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        <div class="text-sm font-semibold opacity-80">{{ $order->created_at->format('M d, Y') }}</div>
                                        <div class="text-[10px] font-black uppercase tracking-widest text-dark-muted italic opacity-60 mt-1">{{ $order->created_at->format('H:i T') }}</div>
                                    </td>
                                    <td class="py-4 font-sans">
                                        <div class="text-lg font-black text-white italic tracking-tighter">R {{ number_format($order->total, 2) }}</div>
                                        <div class="text-[9px] font-black uppercase tracking-widest text-dark-muted italic opacity-50">Total Paid</div>
                                    </td>
                                    <td class="py-4">
                                        @php
                                            $sc = match($order->status) {
                                                'completed', 'delivered' => 'text-green-400 border-green-500/20 bg-green-500/10',
                                                'pending', 'awaiting_payment' => 'text-gold-400 border-gold-400/20 bg-gold-400/10',
                                                'cancelled' => 'text-red-400 border-red-500/20 bg-red-500/10',
                                                default => 'text-blue-400 border-blue-500/20 bg-blue-500/10',
                                            };
                                        @endphp
                                        <span class="px-5 py-2.5 rounded-full text-[9px] font-black uppercase tracking-widest italic border {{ $sc }}">
                                            <i class="fas fa-circle text-[6px] me-2"></i>
                                            {{ $order->status === 'delivered' ? 'Completed' : ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>
                                    <td class="py-4 pe-4 text-center">
                                        <a href="{{ route('user.orders.show', $order) }}" class="inline-flex items-center justify-center w-12 h-12 rounded-full border border-white/10 text-dark-muted hover:text-gold-400 hover:border-gold-400/30 transition-all">
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Footer Operations -->
                <div class="mt-12 flex flex-col md:flex-row items-center justify-between gap-10 bg-black/20 p-8 rounded-[2rem] border border-white/5 border-dashed">
                    <div class="flex-1 w-full pagination-premium">
                        {{ $orders->appends(request()->all())->links() }}
                    </div>
                    
                    <form action="{{ route('user.orders.index') }}" method="GET" class="flex-shrink-0 flex items-center gap-4 bg-white/5 px-6 py-3 rounded-2xl border border-white/5">
                        <span class="text-[10px] font-black uppercase tracking-widest text-dark-muted opacity-60">Items per page</span>
                        <select name="per_page" onchange="this.form.submit()" class="bg-transparent text-[10px] font-black uppercase tracking-widest text-white border-none focus:ring-0 cursor-pointer p-0">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }} class="bg-dark text-white">10 Items</option>
                            <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }} class="bg-dark text-white">20 Items</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }} class="bg-dark text-white">50 Items</option>
                        </select>
                    </form>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-32 bg-black/20 rounded-[3rem] border border-white/5 border-dashed m-10">
                    <div class="w-24 h-24 rounded-full bg-white/5 border border-white/5 flex items-center justify-center mx-auto mb-10 shadow-2xl opacity-40">
                        <i class="fas fa-database text-4xl text-dark-muted"></i>
                    </div>
                    <h4 class="text-2xl font-black text-gray-500 italic mb-6">No orders found.</h4>
                    <p class="text-sm text-gray-600 max-w-sm mx-auto mb-10 italic">Your order history is empty.</p>
                    <a href="{{ route('products') }}" class="px-12 py-5 bg-gold-400 rounded-full text-[11px] font-black uppercase tracking-widest text-black shadow-gold-glow flex items-center justify-center gap-4 mx-auto w-fit">
                        <i class="fas fa-shopping-bag"></i> Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>

@endsection