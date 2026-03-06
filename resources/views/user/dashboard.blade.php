@extends('layouts.frontend')

@section('title', 'My Dashboard - Jabulani Group')

@section('content')
    <!-- Page Header -->
    <div class="relative py-16 overflow-hidden bg-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-left">
            <h1 class="text-3xl lg:text-5xl font-black mb-2 tracking-tight italic text-white uppercase">Account <span
                    class="gradient-text">Dashboard</span></h1>
            <nav
                class="flex justify-center sm:justify-start items-center gap-2 text-[10px] font-black uppercase tracking-widest text-dark-muted">
                <a href="{{ route('home') }}" class="hover:text-gold-400 transition">Home</a>
                <span class="w-1 h-1 rounded-full bg-gold-400/50"></span>
                <span class="text-gray-400">My Account</span>
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
                            <h3 class="text-xl font-black text-white italic tracking-tight">{{ explode(' ', $user->name)[0] }}</h3>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gold-400 mt-2">
                                Active Customer</p>
                        </div>

                        <nav class="p-4 space-y-2">
                            <a href="{{ route('user.dashboard') }}"
                                class="flex items-center gap-4 px-6 py-4 rounded-xl text-[11px] font-black uppercase tracking-widest {{ request()->routeIs('user.dashboard') ? 'bg-gold-400 text-dark shadow-xl' : 'text-dark-muted hover:text-white hover:bg-white/5' }} transition-all duration-300">
                                <i class="fas fa-th-large text-sm"></i> Dashboard
                            </a>
                            <a href="{{ route('user.orders.index') }}"
                                class="flex items-center gap-4 px-6 py-4 rounded-xl text-[11px] font-black uppercase tracking-widest {{ request()->routeIs('user.orders.*') && !request()->routeIs('user.dashboard') ? 'bg-gold-400 text-dark shadow-xl' : 'text-dark-muted hover:text-white hover:bg-white/5' }} transition-all duration-300">
                                <i class="fas fa-shopping-bag text-sm"></i> My Orders
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

                <!-- Primary Display -->
                <div class="flex-1 space-y-8">

                    <!-- Welcome Box -->
                    <div
                        class="card-dark rounded-[2.5rem] p-8 border-white/5 bg-gradient-to-r from-dark to-[#1a1400] relative overflow-hidden group">
                        <div
                            class="absolute right-0 top-0 p-12 opacity-5 scale-150 rotate-12 transition-transform duration-1000 group-hover:rotate-45">
                            <i class="fas fa-shopping-cart text-9xl text-gold-400"></i>
                        </div>
                        <div class="relative z-10">
                            <h2 class="text-3xl font-black text-white italic mb-2 tracking-tight">Welcome back,
                                <span class="text-gold-400 uppercase font-black">{{ explode(' ', $user->name)[0] }}!</span></h2>
                            <p class="text-gray-400 text-sm font-medium max-w-lg leading-relaxed">It's great to see you again. Here's a quick look at your recent activity and orders.</p>
                        </div>
                    </div>

                    <!-- Statistics Grids -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div
                            class="card-dark p-8 rounded-3xl border-white/5 text-center bg-gradient-to-b from-white/[0.02] to-transparent">
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-dark-muted mb-4">Total Orders</p>
                            <p class="text-4xl font-black text-white italic">
                                {{ auth()->user()->orders()->count() }}</p>
                        </div>
                        <div
                            class="card-dark p-8 rounded-3xl border-white/5 text-center bg-gradient-to-b from-white/[0.02] to-transparent">
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-dark-muted mb-4">On the Way</p>
                            <p class="text-4xl font-black text-gold-400 italic">
                                {{ auth()->user()->orders()->whereIn('status', ['pending', 'processing', 'shipping'])->count() }}</p>
                        </div>
                        <div
                            class="card-dark p-8 rounded-3xl border-white/5 text-center bg-gradient-to-b from-white/[0.02] to-transparent">
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-dark-muted mb-4">Account Status</p>
                            <p
                                class="text-xs font-black text-green-400 uppercase tracking-widest mt-4 italic bg-green-400/5 py-2 px-4 rounded-full border border-green-400/10 inline-block">
                                <i class="fas fa-check-circle mr-1"></i> Excellent</p>
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <div class="card-dark rounded-[2.5rem] p-8 border-white/5">
                        <div class="flex items-center justify-between mb-8 pb-6 border-b border-white/5">
                            <h3 class="text-xs font-black uppercase tracking-widest text-white italic">Recent Orders</h3>
                            <a href="{{ route('user.orders.index') }}"
                                class="text-[10px] font-black uppercase tracking-widest text-gold-400 hover:text-white transition-all flex items-center gap-2">
                                View Full History <i class="fas fa-arrow-right-long text-[8px]"></i>
                            </a>
                        </div>

                        @if($recentOrders->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentOrders as $order)
                                    <div
                                        class="group flex flex-col sm:flex-row sm:items-center justify-between gap-6 p-6 rounded-3xl border border-white/5 bg-black/40 hover:border-gold-400/30 transition-all duration-500">
                                        <div class="flex items-center gap-6">
                                            <div
                                                class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center border border-white/10 group-hover:border-gold-400/20 group-hover:bg-gold-400/5 transition-all duration-500 shadow-xl text-gold-400 opacity-60">
                                                <i class="fas fa-box text-xl"></i>
                                            </div>
                                            <div>
                                                <p class="text-lg font-black text-white italic tracking-tighter">
                                                    Order #{{ $order->order_number }}</p>
                                                <p
                                                    class="text-[10px] font-black uppercase tracking-widest text-dark-muted mt-1 italic">
                                                    Placed on {{ $order->created_at->format('M d, Y') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-8">
                                            <div class="text-right hidden sm:block">
                                                <p
                                                    class="text-[9px] font-black uppercase tracking-widest text-dark-muted mb-1 opacity-50">
                                                    Total Price</p>
                                                <p class="text-lg font-black text-white italic tracking-tighter">
                                                    R {{ number_format($order->total, 2) }}</p>
                                            </div>
                                            <div class="flex items-center gap-4">
                                                <span
                                                    class="px-5 py-2 rounded-full text-[9px] font-black uppercase tracking-widest italic shadow-lg border {{ $order->status === 'completed' ? 'bg-green-500/10 text-green-400 border-green-500/20' : ($order->status === 'pending' ? 'bg-gold-400/10 text-gold-400 border-gold-400/20' : 'bg-blue-500/10 text-blue-400 border-blue-500/20') }}">
                                                    {{ ucfirst($order->status) }}
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
                                    <i class="fas fa-shopping-basket text-3xl text-dark-muted"></i>
                                </div>
                                <h4 class="text-xl font-black text-gray-500 italic mb-4">You haven't placed any orders yet.</h4>
                                <p class="text-gray-600 text-sm mb-8 max-w-sm mx-auto">Start exploring our collection and make your first purchase today.</p>
                                <a href="{{ route('products') }}"
                                    class="btn-gold px-12 py-4 text-[10px] font-black uppercase tracking-widest rounded-full shadow-2xl inline-flex items-center gap-3">
                                    <i class="fas fa-shopping-cart"></i> Explore Products
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
