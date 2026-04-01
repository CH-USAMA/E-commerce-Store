@extends('layouts.user')

@section('title', 'Dashboard')

@section('content')

    <!-- Welcome Hero Section -->
    <div class="relative overflow-hidden mb-12 p-10 rounded-[3rem] border border-white/5 bg-gradient-to-br from-dark to-[#1a1400]">
        <div class="absolute -right-20 -top-20 w-96 h-96 bg-gold-400/5 rounded-full blur-[100px]"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="max-w-2xl">
                <header class="flex items-center gap-3 mb-4">
                    <span class="w-10 h-px bg-gold-400/30"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.4em] text-gold-400/80">Welcome Back</span>
                </header>
                <h2 class="text-4xl lg:text-6xl font-black text-white italic tracking-tighter mb-4 leading-none uppercase">
                    Hello, <span class="gradient-text">{{ explode(' ', $user->name)[0] }}</span>
                </h2>
                <p class="text-gray-400 text-sm font-medium leading-relaxed max-w-lg mb-0 italic">
                    Great to see you again. Your private partner dashboard is ready. All your recent activities and specialized orders are listed below.
                </p>
            </div>
            
            <div class="flex-shrink-0">
                <div class="premium-glass p-8 rounded-[2.5rem] border-white/5 text-center min-w-[200px]">
                    <div class="text-[9px] font-black uppercase tracking-[0.2em] text-dark-muted mb-2">Member Status</div>
                    <div class="text-green-400 text-xs font-black uppercase tracking-widest flex items-center justify-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                        Premium Active
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics / Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <!-- Stat 1: Total Orders -->
        <div class="premium-glass p-8 rounded-[2.5rem] border-white/5 relative group transition-all duration-500 hover:border-gold-400/20 stat-card-glow overflow-hidden">
            <div class="absolute -right-4 -bottom-4 opacity-5 rotate-12 group-hover:rotate-45 transition-transform duration-1000">
                <i class="fas fa-shopping-bag text-8xl text-gold-400"></i>
            </div>
            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-dark-muted mb-4">Cumulative Orders</p>
            <h4 class="text-5xl font-black text-white italic tracking-tighter mb-0">{{ auth()->user()->orders()->count() }}</h4>
            <div class="mt-4 flex items-center gap-2 text-green-400 text-[10px] uppercase font-black">
                <i class="fas fa-chevron-up"></i>
                Lifetime Volume
            </div>
        </div>

        <!-- Stat 2: Active Shipments -->
        <div class="premium-glass p-8 rounded-[2.5rem] border-white/5 relative group transition-all duration-500 hover:border-gold-400/20 stat-card-glow overflow-hidden">
             <div class="absolute -right-4 -bottom-4 opacity-5 rotate-12 group-hover:rotate-45 transition-transform duration-1000">
                <i class="fas fa-truck text-8xl text-gold-400"></i>
            </div>
            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-dark-muted mb-4">Pending Fulfillment</p>
            <h4 class="text-5xl font-black text-gold-400 italic tracking-tighter mb-0">
                {{ auth()->user()->orders()->whereIn('status', ['pending', 'processing', 'shipped'])->count() }}
            </h4>
            <div class="mt-4 flex items-center gap-2 text-blue-400 text-[10px] uppercase font-black font-sans">
                <i class="fas fa-info-circle"></i>
                Processing
            </div>
        </div>

        <!-- Stat 3: Total Investment -->
        <div class="premium-glass p-8 rounded-[2.5rem] border-white/5 relative group transition-all duration-500 hover:border-gold-400/20 stat-card-glow overflow-hidden">
             <div class="absolute -right-4 -bottom-4 opacity-10 rotate-12 group-hover:rotate-45 transition-transform duration-1000">
                <i class="fas fa-chart-line text-8xl text-white"></i>
            </div>
            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-dark-muted mb-4">Transactional Value</p>
            <h4 class="text-3xl font-black text-white italic tracking-tighter mb-0">R {{ number_format($totalSpent ?? 0, 2) }}</h4>
            <div class="mt-4 flex items-center gap-2 text-gold-400 text-[10px] uppercase font-black font-sans tracking-widest">
                Account Equity
            </div>
        </div>
    </div>

    <!-- Recent Deliveries Section -->
    <div class="premium-glass rounded-[3rem] border-white/5 overflow-hidden">
        <div class="p-10 border-b border-white/5 flex items-center justify-between">
            <div>
                <h3 class="text-xs font-black uppercase tracking-[0.4em] text-white italic mb-1">Recent Intelligence</h3>
                <p class="text-[10px] font-black uppercase tracking-widest text-dark-muted">Latest procurement entries</p>
            </div>
            <a href="{{ route('user.orders.index') }}" class="px-6 py-3 bg-white/5 rounded-full text-[10px] font-black uppercase tracking-widest text-gold-400 border border-white/10 hover:border-gold-400 hover:text-white transition-all">
                Full Record History
            </a>
        </div>

        <div class="p-10">
            @if($recentOrders->count() > 0)
                <div class="space-y-4">
                    @foreach($recentOrders as $order)
                        <div class="group flex flex-col sm:flex-row sm:items-center justify-between gap-8 p-8 rounded-[2rem] border border-white/5 bg-black/40 hover:border-gold-400/30 transition-all duration-500 translate-y-0 hover:-translate-y-1">
                            <div class="flex items-center gap-8">
                                <div class="w-14 h-14 rounded-2xl bg-white/5 flex items-center justify-center border border-white/10 group-hover:border-gold-400/20 group-hover:bg-gold-400/5 transition-all duration-500 shadow-xl text-gold-400 opacity-60">
                                    <i class="fas fa-box text-xl"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-4 mb-1">
                                        <h4 class="text-lg font-black text-white italic tracking-tight uppercase">Entry #{{ $order->order_number }}</h4>
                                        <span class="text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded bg-gold-400/10 text-gold-400 border border-gold-400/20 italic">{{ $order->order_type ?: 'B2B' }}</span>
                                    </div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-dark-muted italic">Registered on {{ $order->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-12">
                                <div class="text-right hidden md:block">
                                    <p class="text-[9px] font-black uppercase tracking-widest text-dark-muted mb-1 opacity-50 italic">Investment</p>
                                    <p class="text-xl font-black text-white italic tracking-tighter leading-none">R {{ number_format($order->total, 2) }}</p>
                                </div>
                                
                                <div class="flex items-center gap-6">
                                    @php
                                        $sc = match($order->status) {
                                            'completed', 'delivered' => 'bg-green-500/10 text-green-400 border-green-500/20',
                                            'pending', 'awaiting_payment' => 'bg-gold-400/10 text-gold-400 border-gold-400/20',
                                            'cancelled' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                            default => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                        };
                                    @endphp
                                    <span class="px-6 py-2.5 rounded-full text-[9px] font-black uppercase tracking-widest italic border {{ $sc }}">
                                        {{ $order->status === 'delivered' ? 'Completed' : ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                    <a href="{{ route('user.orders.show', $order) }}" class="w-12 h-12 rounded-full premium-glass flex items-center justify-center text-dark-muted hover:text-gold-400 hover:border-gold-400/30 transition-all shadow-xl">
                                        <i class="fas fa-long-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-24 bg-black/20 rounded-[2.5rem] border border-white/5 border-dashed">
                    <div class="w-24 h-24 rounded-full bg-white/5 border border-white/5 flex items-center justify-center mx-auto mb-10 shadow-2xl opacity-40">
                        <i class="fas fa-shopping-basket text-4xl text-dark-muted"></i>
                    </div>
                    <h4 class="text-2xl font-black text-gray-500 italic mb-6">Database currently empty.</h4>
                    <a href="{{ route('products') }}" class="btn-gold px-12 py-5 text-[10px] font-black uppercase tracking-widest rounded-full shadow-gold-glow inline-flex items-center gap-4">
                        <i class="fas fa-shopping-cart"></i> Initialize first procurement
                    </a>
                </div>
            @endif
        </div>
    </div>

@endsection
