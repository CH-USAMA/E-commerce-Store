@extends('layouts.frontend')

@section('title', 'My Orders - Jabulani Group')

@section('content')
    <!-- Page Header -->
    <div class="relative py-16 overflow-hidden bg-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-left">
            <h1 class="text-3xl lg:text-5xl font-black mb-2 tracking-tight italic text-white uppercase">My <span
                    class="gradient-text">Orders</span></h1>
            <nav
                class="flex justify-center sm:justify-start items-center gap-2 text-[10px] font-black uppercase tracking-widest text-dark-muted">
                <a href="{{ route('home') }}" class="hover:text-gold-400 transition">Home</a>
                <span class="w-1 h-1 rounded-full bg-gold-400/50"></span>
                <a href="{{ route('user.dashboard') }}" class="hover:text-gold-400 transition">Dashboard</a>
                <span class="w-1 h-1 rounded-full bg-gold-400/50"></span>
                <span class="text-gray-400">Order History</span>
            </nav>
        </div>
    </div>

    <div class="bg-[#050505] min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">

                <!-- Sidebar Navigation -->
                <aside class="w-full lg:w-72 flex-shrink-0">
                    <div
                        class="card-dark rounded-[2rem] overflow-hidden border-white/5 bg-gradient-to-b from-white/[0.03] to-transparent sticky top-32">
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
                            <h3 class="text-xl font-black text-white italic tracking-tight">
                                {{ explode(' ', auth()->user()->name)[0] }}</h3>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gold-400 mt-2">Active Customer
                            </p>
                        </div>

                        <nav class="p-4 space-y-2">
                            <a href="{{ route('user.dashboard') }}"
                                class="flex items-center gap-4 px-6 py-4 rounded-xl text-[11px] font-black uppercase tracking-widest text-dark-muted hover:text-white hover:bg-white/5 transition-all duration-300">
                                <i class="fas fa-th-large text-sm"></i> Dashboard
                            </a>
                            <a href="{{ route('user.orders.index') }}"
                                class="flex items-center gap-4 px-6 py-4 rounded-xl text-[11px] font-black uppercase tracking-widest bg-gold-400 text-dark shadow-xl transition-all duration-300">
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
                    </div>
                </aside>

                <!-- Order History -->
                <div class="flex-1 space-y-8">
                    <div
                        class="card-dark rounded-[2.5rem] p-8 border-white/5 bg-gradient-to-t from-white/[0.01] to-transparent">
                        <div class="flex items-center justify-between mb-10 border-b border-white/5 pb-6">
                            <h3 class="text-xs font-black uppercase tracking-widest text-white italic">Order History</h3>
                            <div class="flex items-center gap-6">
                                <a href="{{ route('user.orders.export') }}" class="text-[9px] font-black uppercase tracking-widest text-gold-400 hover:text-white transition flex items-center gap-2">
                                    <i class="fas fa-file-csv"></i> Export History
                                </a>
                                <span class="w-1 h-1 rounded-full bg-white/20"></span>
                                <span class="text-[9px] font-black uppercase tracking-[0.3em] text-dark-muted">{{ $orders->total() }} Total Orders</span>
                            </div>
                        </div>

                        @if($orders->count() > 0)
                            <div class="space-y-4">
                                @foreach($orders as $order)
                                    <div
                                        class="group flex flex-col sm:flex-row sm:items-center justify-between gap-6 p-6 rounded-3xl border border-white/5 bg-black/40 hover:border-gold-400/30 transition-all duration-500">
                                        <div class="flex items-center gap-6">
                                            <div
                                                class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center border border-white/10 group-hover:border-gold-400/20 group-hover:bg-gold-400/5 transition-all duration-500 shadow-xl text-gold-400 opacity-60">
                                                <i class="fas fa-box text-xl"></i>
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-3">
                                                    <p class="text-lg font-black text-white italic tracking-tighter" title="{{ $order->manifest }}">
                                                        Order #{{ $order->order_number }}</p>
                                                    <span
                                                        class="text-[8px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full bg-white/5 text-dark-muted border border-white/10">{{ $order->order_type }}</span>
                                                </div>
                                                <p
                                                    class="text-[10px] font-black uppercase tracking-widest text-dark-muted mt-1 italic opacity-60">
                                                    Placed on {{ $order->created_at->format('d M Y') }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-8">
                                            <div class="text-right hidden md:block">
                                                <p
                                                    class="text-[9px] font-black uppercase tracking-widest text-dark-muted mb-1 opacity-50 italic">
                                                    Total Price</p>
                                                <p class="text-xl font-black text-white italic tracking-tighter">
                                                    R {{ number_format($order->total, 2) }}</p>
                                            </div>
                                            <div class="flex items-center gap-4">
                                                <span
                                                    class="px-5 py-2 rounded-full text-[9px] font-black uppercase tracking-widest italic shadow-lg border {{ in_array($order->status, ['completed', 'delivered']) ? 'bg-green-500/10 text-green-400 border-green-500/20 shadow-[0_0_15px_rgba(34,197,94,0.1)]' : ($order->status === 'pending' ? 'bg-gold-400/10 text-gold-400 border-gold-400/20' : ($order->status === 'cancelled' ? 'bg-red-500/10 text-red-400 border-red-500/20' : 'bg-blue-500/10 text-blue-400 border-blue-500/20')) }}">
                                                    {{ $order->status === 'delivered' ? 'Completed' : ucfirst($order->status) }}
                                                </span>
                                                <a href="{{ route('user.orders.show', $order) }}"
                                                    class="btn-outline-gold group px-6 py-3 text-[9px] font-black uppercase tracking-widest rounded-full flex items-center gap-2">
                                                    View Details <i
                                                        class="fas fa-chevron-right group-hover:translate-x-1 transition-transform text-[8px]"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-12 flex flex-col md:flex-row items-center justify-between gap-8">
                                <div class="flex-1">
                                    {{ $orders->links() }}
                                </div>
                                <form action="{{ route('user.orders.index') }}" method="GET" class="flex items-center gap-4 bg-white/5 px-4 py-2 rounded-xl border border-white/5">
                                    <span class="text-[9px] font-black uppercase tracking-widest text-dark-muted">Show</span>
                                    <select name="per_page" onchange="this.form.submit()" class="bg-transparent text-[10px] font-black uppercase tracking-widest text-white border-none focus:ring-0 cursor-pointer">
                                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }} class="bg-dark">10</option>
                                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }} class="bg-dark">20</option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }} class="bg-dark">50</option>
                                    </select>
                                    <span class="text-[9px] font-black uppercase tracking-widest text-dark-muted">Orders</span>
                                </form>
                            </div>
                        @else
                            <div class="text-center py-20 bg-black/20 rounded-[2.5rem] border border-white/5 border-dashed">
                                <div
                                    class="w-20 h-20 rounded-full bg-white/5 border border-white/5 flex items-center justify-center mx-auto mb-8 shadow-2xl opacity-40">
                                    <i class="fas fa-shopping-basket text-3xl text-dark-muted"></i>
                                </div>
                                <h4 class="text-xl font-black text-gray-500 italic mb-4">No orders found.</h4>
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