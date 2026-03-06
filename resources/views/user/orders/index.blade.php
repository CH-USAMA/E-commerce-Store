@extends('layouts.frontend')

@section('title', 'Order Archive - Jabulani Group')

@section('content')
    <!-- Page Header -->
    <div class="relative py-16 overflow-hidden bg-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-left">
            <h1 class="text-3xl lg:text-5xl font-black mb-2 tracking-tight italic text-white uppercase">Order <span
                    class="gradient-text">Archive</span></h1>
            <nav
                class="flex justify-center sm:justify-start items-center gap-2 text-[10px] font-black uppercase tracking-widest text-dark-muted">
                <a href="{{ route('home') }}" class="hover:text-gold-400 transition">Home</a>
                <span class="w-1 h-1 rounded-full bg-gold-400/50"></span>
                <a href="{{ route('user.dashboard') }}" class="hover:text-gold-400 transition">Dashboard</a>
                <span class="w-1 h-1 rounded-full bg-gold-400/50"></span>
                <span class="text-gray-400">Ledger History</span>
            </nav>
        </div>
    </div>

    <div class="bg-[#050505] min-h-screen py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-12">

                <!-- Sidebar Navigation -->
                <aside class="w-full lg:w-72 flex-shrink-0">
                    <div
                        class="card-dark rounded-[2.5rem] overflow-hidden border-white/5 bg-gradient-to-b from-white/[0.03] to-transparent sticky top-32">
                        <div class="p-8 border-b border-white/5 flex flex-col items-center text-center">
                            <div class="relative mb-6">
                                <div
                                    class="w-20 h-20 rounded-3xl bg-white/5 border border-white/10 flex items-center justify-center shadow-2xl">
                                    <i class="fas fa-user-circle text-gold-400 text-3xl"></i>
                                </div>
                            </div>
                            <h3 class="text-lg font-black text-white italic tracking-tight">{{ auth()->user()->name }}</h3>
                            <p class="text-[8px] font-black uppercase tracking-widest text-dark-muted mt-1">Verified Client
                                Account</p>
                        </div>

                        <nav class="p-4 space-y-2">
                            <a href="{{ route('user.dashboard') }}"
                                class="flex items-center gap-4 px-6 py-4 rounded-2xl text-[11px] font-black uppercase tracking-widest text-dark-muted hover:text-white hover:bg-white/5 transition-all duration-300">
                                <i class="fas fa-chart-line text-sm"></i> Intelligence
                            </a>
                            <a href="{{ route('user.orders.index') }}"
                                class="flex items-center gap-4 px-6 py-4 rounded-2xl text-[11px] font-black uppercase tracking-widest bg-gold-400 text-dark shadow-xl transition-all duration-300">
                                <i class="fas fa-folder-open text-sm"></i> Order Archive
                            </a>
                            <div class="h-px bg-white/5 my-4 mx-4"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-4 px-6 py-4 rounded-2xl text-[11px] font-black uppercase tracking-widest text-red-400/50 hover:text-red-400 hover:bg-red-400/5 transition-all duration-300">
                                    <i class="fas fa-power-off text-sm"></i> Terminate Session
                                </button>
                            </form>
                        </nav>
                    </div>
                </aside>

                <!-- Order History Matrix -->
                <div class="flex-1 space-y-8">
                    <div
                        class="card-dark rounded-[3rem] p-10 border-white/5 bg-gradient-to-t from-white/[0.01] to-transparent">
                        <div class="flex items-center justify-between mb-10 border-b border-white/5 pb-6">
                            <h3 class="text-[11px] font-black uppercase tracking-widest text-white italic">Historical Ledger
                            </h3>
                            <span
                                class="text-[9px] font-black uppercase tracking-[0.3em] text-dark-muted">{{ $orders->total() }}
                                Total Manifests</span>
                        </div>

                        @if($orders->count() > 0)
                            <div class="space-y-4">
                                @foreach($orders as $order)
                                    <div
                                        class="group flex flex-col sm:flex-row sm:items-center justify-between gap-6 p-6 rounded-[2rem] border border-white/5 bg-black/40 hover:border-gold-400/30 transition-all duration-500 shadow-inner">
                                        <div class="flex items-center gap-6">
                                            <div
                                                class="w-14 h-14 rounded-2xl bg-white/5 flex items-center justify-center border border-white/10 group-hover:border-gold-400/20 group-hover:bg-gold-400/5 transition-all duration-500 shadow-xl">
                                                <i
                                                    class="fas fa-file-contract text-gold-400 opacity-40 group-hover:opacity-100 italic transition-all"></i>
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-3">
                                                    <p class="text-lg font-black text-white italic tracking-tighter">
                                                        #{{ $order->order_number }}</p>
                                                    <span
                                                        class="text-[8px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full bg-white/5 text-dark-muted border border-white/10">{{ $order->order_type }}</span>
                                                </div>
                                                <p
                                                    class="text-[9px] font-black uppercase tracking-widest text-dark-muted mt-1 italic opacity-60">
                                                    {{ $order->created_at->format('d M Y, h:i A') }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-8">
                                            <div class="text-right hidden md:block">
                                                <p
                                                    class="text-[9px] font-black uppercase tracking-widest text-dark-muted mb-1 opacity-50 italic">
                                                    Settlement</p>
                                                <p class="text-xl font-black text-white italic tracking-tighter">
                                                    R{{ number_format($order->total, 2) }}</p>
                                            </div>
                                            <div class="flex items-center gap-4">
                                                <span
                                                    class="px-5 py-2 rounded-full text-[9px] font-black uppercase tracking-widest italic shadow-lg border {{ $order->status === 'completed' ? 'bg-green-500/10 text-green-400 border-green-500/20' : ($order->status === 'pending' ? 'bg-gold-400/10 text-gold-400 border-gold-400/20' : ($order->status === 'cancelled' ? 'bg-red-500/10 text-red-400 border-red-500/20' : 'bg-blue-500/10 text-blue-400 border-blue-500/20')) }}">
                                                    {{ $order->status }}
                                                </span>
                                                <a href="{{ route('user.orders.show', $order) }}"
                                                    class="btn-outline-gold group px-6 py-3 text-[9px] font-black uppercase tracking-widest rounded-full flex items-center gap-2">
                                                    Analyze <i
                                                        class="fas fa-chevron-right group-hover:translate-x-1 transition-transform text-[8px]"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-12">
                                {{ $orders->links() }}
                            </div>
                        @else
                            <div class="text-center py-20 bg-black/20 rounded-[2.5rem] border border-white/5 border-dashed">
                                <div
                                    class="w-20 h-20 rounded-full bg-white/5 border border-white/5 flex items-center justify-center mx-auto mb-8 shadow-2xl opacity-40">
                                    <i class="fas fa-folder-closed text-3xl text-dark-muted"></i>
                                </div>
                                <h4 class="text-xl font-black text-gray-500 italic mb-4">No Archived Manifests Found.</h4>
                                <a href="{{ route('products') }}"
                                    class="btn-gold px-12 py-4 text-[10px] font-black uppercase tracking-widest rounded-full shadow-2xl inline-flex items-center gap-3">
                                    <i class="fas fa-shopping-bag"></i> Start Shopping
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection