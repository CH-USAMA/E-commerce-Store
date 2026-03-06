@extends('layouts.frontend')

@section('title', 'Client Portal - Jabulani Group')

@section('content')
    <!-- Page Header -->
    <div class="relative py-16 overflow-hidden bg-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-left">
            <h1 class="text-3xl lg:text-5xl font-black mb-2 tracking-tight italic text-white uppercase">Client <span
                    class="gradient-text">Command</span></h1>
            <nav
                class="flex justify-center sm:justify-start items-center gap-2 text-[10px] font-black uppercase tracking-widest text-dark-muted">
                <a href="{{ route('home') }}" class="hover:text-gold-400 transition">Home</a>
                <span class="w-1 h-1 rounded-full bg-gold-400/50"></span>
                <span class="text-gray-400">Dashboard</span>
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
                                    class="w-20 h-20 rounded-3xl bg-gold-400/10 border border-gold-400/20 flex items-center justify-center shadow-2xl">
                                    <i class="fas fa-user-tie text-gold-400 text-3xl"></i>
                                </div>
                                <div
                                    class="absolute -right-2 -bottom-2 w-6 h-6 rounded-full bg-green-500 border-4 border-dark shadow-xl">
                                </div>
                            </div>
                            <h3 class="text-lg font-black text-white italic tracking-tight">{{ $user->name }}</h3>
                            <p class="text-[9px] font-black uppercase tracking-widest text-dark-muted mt-1 opacity-60">
                                Verified Shareholder</p>
                        </div>

                        <nav class="p-4 space-y-2">
                            <a href="{{ route('user.dashboard') }}"
                                class="flex items-center gap-4 px-6 py-4 rounded-2xl text-[11px] font-black uppercase tracking-widest bg-gold-400 text-dark shadow-xl transition-all duration-300">
                                <i class="fas fa-chart-line text-sm"></i> Intelligence
                            </a>
                            <a href="{{ route('user.orders.index') }}"
                                class="flex items-center gap-4 px-6 py-4 rounded-2xl text-[11px] font-black uppercase tracking-widest text-dark-muted hover:text-white hover:bg-white/5 transition-all duration-300">
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

                <!-- Primary Display -->
                <div class="flex-1 space-y-12">

                    <!-- Welcome Broadcast -->
                    <div
                        class="card-dark rounded-[3rem] p-10 border-gold-400/20 bg-gradient-to-r from-dark to-[#1a1400] relative overflow-hidden group">
                        <div
                            class="absolute right-0 top-0 p-12 opacity-5 scale-150 rotate-12 transition-transform duration-1000 group-hover:rotate-45">
                            <i class="fas fa-crown text-9xl text-gold-400"></i>
                        </div>
                        <div class="relative z-10">
                            <h2 class="text-3xl md:text-4xl font-black text-white italic mb-2 tracking-tight">System Ready,
                                <span class="text-gold-400 uppercase">{{ explode(' ', $user->name)[0] }}</span>.</h2>
                            <p class="text-dark-muted text-sm font-medium max-w-lg leading-relaxed">Your procurement history
                                and live logistics data are synchronized. Manage your acquisitions with precision.</p>
                        </div>
                    </div>

                    <!-- Statistics Matrix -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div
                            class="card-dark p-8 rounded-[2.5rem] border-white/5 text-center bg-gradient-to-b from-white/[0.02] to-transparent">
                            <p class="text-[9px] font-black uppercase tracking-[0.3em] text-dark-muted mb-4 italic">Total
                                Acquisitions</p>
                            <p class="text-4xl font-black text-white italic tracking-tighter">
                                {{ auth()->user()->orders()->count() }}</p>
                        </div>
                        <div
                            class="card-dark p-8 rounded-[2.5rem] border-white/5 text-center bg-gradient-to-b from-white/[0.02] to-transparent">
                            <p class="text-[9px] font-black uppercase tracking-[0.3em] text-dark-muted mb-4 italic">Pending
                                Fulfillment</p>
                            <p class="text-4xl font-black text-gold-400 italic tracking-tighter">
                                {{ auth()->user()->orders()->whereIn('status', ['pending', 'processing'])->count() }}</p>
                        </div>
                        <div
                            class="card-dark p-8 rounded-[2.5rem] border-white/5 text-center bg-gradient-to-b from-white/[0.02] to-transparent">
                            <p class="text-[9px] font-black uppercase tracking-[0.3em] text-dark-muted mb-4 italic">Ledger
                                Status</p>
                            <p
                                class="text-sm font-black text-green-400 uppercase tracking-widest mt-4 italic bg-green-400/5 py-1 rounded-full border border-green-400/10">
                                Active & Clear</p>
                        </div>
                    </div>

                    <!-- Recent Logistics Snapshot -->
                    <div class="card-dark rounded-[3rem] p-10 border-white/5">
                        <div class="flex items-center justify-between mb-10 border-b border-white/5 pb-6">
                            <h3 class="text-[11px] font-black uppercase tracking-widest text-white italic">Recent Manifests
                            </h3>
                            <a href="{{ route('user.orders.index') }}"
                                class="text-[10px] font-black uppercase tracking-widest text-gold-400 hover:text-white transition-all flex items-center gap-2">
                                System Archive <i class="fas fa-arrow-right-long text-[8px]"></i>
                            </a>
                        </div>

                        @if($recentOrders->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentOrders as $order)
                                    <div
                                        class="group flex flex-col sm:flex-row sm:items-center justify-between gap-6 p-6 rounded-[2rem] border border-white/5 bg-black/40 hover:border-gold-400/30 transition-all duration-500 shadow-inner">
                                        <div class="flex items-center gap-6">
                                            <div
                                                class="w-14 h-14 rounded-2xl bg-white/5 flex items-center justify-center border border-white/10 group-hover:border-gold-400/20 group-hover:bg-gold-400/5 transition-all duration-500 shadow-xl">
                                                <i
                                                    class="fas fa-barcode text-gold-400 opacity-40 group-hover:opacity-100 italic transition-all"></i>
                                            </div>
                                            <div>
                                                <p class="text-lg font-black text-white italic tracking-tighter">
                                                    #{{ $order->order_number }}</p>
                                                <p
                                                    class="text-[9px] font-black uppercase tracking-widest text-dark-muted mt-1 italic">
                                                    {{ $order->created_at->format('M d, Y') }} · {{ $order->order_type }} DISPATCH
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-8">
                                            <div class="text-right hidden sm:block">
                                                <p
                                                    class="text-[9px] font-black uppercase tracking-widest text-dark-muted mb-1 opacity-50">
                                                    Settlement</p>
                                                <p class="text-lg font-black text-white italic tracking-tighter">
                                                    R{{ number_format($order->total, 2) }}</p>
                                            </div>
                                            <div class="flex items-center gap-4">
                                                <span
                                                    class="px-5 py-2 rounded-full text-[9px] font-black uppercase tracking-widest italic shadow-lg border {{ $order->status === 'completed' ? 'bg-green-500/10 text-green-400 border-green-500/20' : ($order->status === 'pending' ? 'bg-gold-400/10 text-gold-400 border-gold-400/20' : 'bg-blue-500/10 text-blue-400 border-blue-500/20') }}">
                                                    {{ $order->status }}
                                                </span>
                                                <a href="{{ route('user.orders.show', $order) }}"
                                                    class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-dark-muted hover:text-gold-400 hover:bg-gold-400/5 border border-white/10 transition-all">
                                                    <i class="fas fa-chevron-right text-xs"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-20 bg-black/20 rounded-[2.5rem] border border-white/5 border-dashed">
                                <div
                                    class="w-20 h-20 rounded-full bg-white/5 border border-white/5 flex items-center justify-center mx-auto mb-8 shadow-2xl opacity-40">
                                    <i class="fas fa-box-open text-3xl text-dark-muted"></i>
                                </div>
                                <h4 class="text-xl font-black text-gray-500 italic mb-4">No Historical Manifests Found.</h4>
                                <a href="{{ route('products') }}"
                                    class="btn-gold px-12 py-4 text-[10px] font-black uppercase tracking-widest rounded-full shadow-2xl inline-flex items-center gap-3">
                                    <i class="fas fa-plus"></i> Initiate Procurement
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection