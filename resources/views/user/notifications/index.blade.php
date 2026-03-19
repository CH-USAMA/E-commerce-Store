@extends('layouts.frontend')

@section('title', 'Alert Center - Jabulani Group')

@section('content')
    <!-- Page Header -->
    <div class="relative py-16 overflow-hidden bg-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-left">
            <h1 class="text-3xl lg:text-5xl font-black mb-2 tracking-tight italic text-white uppercase">Alert <span
                    class="gradient-text">Center</span></h1>
            <nav
                class="flex justify-center sm:justify-start items-center gap-2 text-[10px] font-black uppercase tracking-widest text-dark-muted">
                <a href="{{ route('home') }}" class="hover:text-gold-400 transition">Home</a>
                <span class="w-1 h-1 rounded-full bg-gold-400/50"></span>
                <a href="{{ route('user.dashboard') }}" class="hover:text-gold-400 transition">Dashboard</a>
                <span class="w-1 h-1 rounded-full bg-gold-400/50"></span>
                <span class="text-gray-400">All Notifications</span>
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
                                class="flex items-center gap-4 px-6 py-4 rounded-xl text-[11px] font-black uppercase tracking-widest text-dark-muted hover:text-white hover:bg-white/5 transition-all duration-300">
                                <i class="fas fa-shopping-bag text-sm"></i> My Orders
                            </a>
                            <a href="{{ route('user.notifications.index') }}"
                                class="flex items-center gap-4 px-6 py-4 rounded-xl text-[11px] font-black uppercase tracking-widest bg-gold-400 text-dark shadow-xl transition-all duration-300">
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

                <!-- Notifications Grid -->
                <div class="flex-1 space-y-8">
                    <div
                        class="card-dark rounded-[2.5rem] p-8 border-white/5 bg-gradient-to-t from-white/[0.01] to-transparent">
                        <div class="flex items-center justify-between mb-10 border-b border-white/5 pb-6">
                            <h3 class="text-xs font-black uppercase tracking-widest text-white italic">Recent Alerts</h3>
                            <div class="flex items-center gap-4">
                                <span class="text-[9px] font-black uppercase tracking-[0.3em] text-dark-muted">{{ auth()->user()->notifications->count() }} Total</span>
                                <a href="{{ route('user.notifications.mark-read') }}" class="text-[10px] font-black text-gold-400 uppercase tracking-widest hover:text-white transition">Clear All</a>
                            </div>
                        </div>

                        @if($notifications->count() > 0)
                            <div class="space-y-4">
                                @foreach($notifications as $notification)
                                    <div class="group p-8 rounded-[2rem] border border-white/5 bg-black/40 hover:border-gold-400/20 transition-all duration-500 {{ $notification->read_at ? 'opacity-50' : 'bg-white/[0.02] shadow-[inset_0_0_40px_rgba(255,255,255,0.02)]' }}">
                                        <div class="flex items-start gap-6">
                                            <div class="w-14 h-14 rounded-2xl bg-white/5 flex items-center justify-center text-gold-400 flex-shrink-0 border border-white/10 group-hover:bg-gold-400 group-hover:text-dark transition-all duration-500">
                                                @if(($notification->data['type'] ?? '') === 'status_change')
                                                    <i class="fas fa-sync-alt text-xl"></i>
                                                @elseif(($notification->data['type'] ?? '') === 'marketing')
                                                    <i class="fas fa-bullhorn text-xl"></i>
                                                @else
                                                    <i class="fas fa-bell text-xl"></i>
                                                @endif
                                            </div>
                                            <div class="flex-grow">
                                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 mb-3">
                                                    <h4 class="text-lg font-black text-white italic tracking-tighter uppercase">{{ $notification->data['title'] ?? 'Order Notification' }}</h4>
                                                    <span class="text-[9px] font-black text-dark-muted uppercase tracking-[0.2em]">{{ $notification->created_at->format('M d, Y H:i') }}</span>
                                                </div>
                                                <p class="text-sm text-gray-400 leading-relaxed font-medium mb-6">{{ $notification->data['message'] }}</p>
                                                
                                                @if(isset($notification->data['url']))
                                                    <a href="{{ $notification->data['url'] }}" class="inline-flex items-center gap-2 text-[10px] font-black text-gold-400 uppercase tracking-[0.2em] hover:text-white transition group/link">
                                                        Access Update <i class="fas fa-arrow-right group-hover/link:translate-x-1 transition-transform"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-12">
                                {{ $notifications->links() }}
                            </div>
                        @else
                            <div class="text-center py-24 bg-black/20 rounded-[2.5rem] border border-white/5 border-dashed">
                                <div class="w-24 h-24 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-8 opacity-20">
                                    <i class="fas fa-bell-slash text-4xl text-dark-muted"></i>
                                </div>
                                <h4 class="text-xl font-black text-gray-500 italic mb-2 uppercase tracking-tight">Your frequency is clear</h4>
                                <p class="text-[10px] font-black text-dark-muted uppercase tracking-widest italic opacity-60">No new alerts or broadcasts detected in your perimeter.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
