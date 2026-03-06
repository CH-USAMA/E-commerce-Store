<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jabulani Group')</title>
    <!-- Robust Font Loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400;1,700&family=Outfit:wght@300;400;600;800&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: { DEFAULT: '#f5c518', 400: '#f5c518', 500: '#d4a200', 600: '#a87c00' },
                        dark: { DEFAULT: '#0a0a0a', card: '#111111', border: '#2a2a2a', muted: '#888888' }
                    },
                    fontFamily: {
                        sans: ['Outfit', 'Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome -->
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">

    <!-- Toastify JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <style>
        /* CSS Fallback for centering if Tailwind CDN fails to initialize */
        .max-w-7xl {
            max-width: 80rem;
        }

        .mx-auto {
            margin-left: auto;
            margin-right: auto;
        }

        .px-4 {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        body {
            background-color: #0a0a0a;
            color: #f1f1f1;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }

        /* Premium Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #050505;
        }

        ::-webkit-scrollbar-thumb {
            background: #f5c518;
            border-radius: 4px;
        }

        .btn-gold {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #f5c518;
            color: #0a0a0a;
            font-weight: 700;
            border-radius: 9999px;
            padding: 0.75rem 1.75rem;
            transition: all 0.3s ease;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .btn-gold:hover {
            background: #e0b000;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(245, 197, 24, 0.2);
        }

        .btn-outline-gold {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #f5c518;
            color: #f5c518;
            font-weight: 600;
            border-radius: 9999px;
            padding: 0.75rem 1.75rem;
            transition: all 0.3s;
            text-decoration: none;
            background: transparent;
        }

        .btn-outline-gold:hover {
            background: #f5c518;
            color: #0a0a0a;
        }

        .card-dark {
            background: #111111;
            border: 1px solid #2a2a2a;
            border-radius: 1rem;
        }

        .gradient-text {
            color: transparent;
            background-clip: text;
            background-image: linear-gradient(to right, #f5c518, #fef08a);
        }

        /* Fix for layout shifting on left/right */
        * {
            box-sizing: border-box;
        }

        img {
            max-width: 100%;
            height: auto;
            display: block;
        }

        /* Animation for dropdowns */
        .nav-dropdown {
            transform: translateY(10px);
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .group:hover .nav-dropdown {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }

        /* Bridge the gap between nav item and dropdown to maintain hover state */
        .nav-dropdown::before {
            content: '';
            position: absolute;
            top: -40px;
            left: -20px;
            right: -20px;
            height: 40px;
            background: transparent;
        }
    </style>
    @stack('css')
</head>

<body class="flex flex-col min-h-screen" x-data="{ 
    scrolled: false,
    toast: { show: false, message: '', type: 'success' }
}" @scroll.window="scrolled = (window.pageYOffset > 20)">

    <script>
        document.addEventListener('alpine:init', () => {
            window.showToast = (msg, type = 'success') => {
                const bgColor = type === 'success' ? 'linear-gradient(to right, #00b09b, #96c93d)' : 'linear-gradient(to right, #ff5f6d, #ffc371)';
                const icon = type === 'success'
                    ? '<i class="fas fa-check-circle" style="color: white; margin-right: 8px;"></i>'
                    : '<i class="fas fa-exclamation-triangle" style="color: white; margin-right: 8px;"></i>';

                Toastify({
                    text: msg,
                    duration: 4000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: {
                        background: bgColor,
                        borderRadius: "8px",
                        padding: "12px 20px",
                        color: "#fff",
                        boxShadow: "0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)",
                        fontFamily: "'Inter', sans-serif",
                        fontWeight: "600",
                        fontSize: "14px",
                        display: "flex",
                        alignItems: "center"
                    },
                    escapeMarkup: false,
                    node: (() => {
                        const span = document.createElement("span");
                        span.innerHTML = icon + msg;
                        return span;
                    })()
                }).showToast();
            };

            window.updateCartBadge = (count) => {
                const badge = document.getElementById('cart-badge');
                if (badge) {
                    badge.textContent = count;
                    badge.style.display = count > 0 ? 'flex' : 'none';
                } else if (count > 0) {
                    location.reload();
                }
            };

            @if(session()->has('success'))
                @php $msg = session()->pull('success'); @endphp
                @if(is_string($msg) && strlen(trim($msg)) > 0)
                    setTimeout(() => {
                        window.showToast(`{{ $msg }}`, 'success');
                    }, 500);
                @endif
            @endif

            @if(session()->has('error'))
                @php $msg = session()->pull('error'); @endphp
                @if(is_string($msg) && strlen(trim($msg)) > 0)
                    setTimeout(() => {
                        window.showToast(`{{ $msg }}`, 'error');
                    }, 500);
                @endif
            @endif
        });
    </script>

    <!-- Header -->

    <header class="fixed top-0 left-0 right-0 z-[100] transition-all duration-500"
        :class="scrolled ? 'py-2 bg-black/80 backdrop-blur-2xl border-b border-white/5 shadow-2xl' : 'py-6 bg-transparent'"
        x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center transition-all duration-500"
                :class="scrolled ? 'h-12' : 'h-16'">
                <!-- Logo -->
                <a href="{{ route('home') }}"
                    class="flex-shrink-0 flex items-center gap-3 group px-4 py-2 rounded-2xl transition hover:bg-white/5">
                    <img src="{{ asset('images/logo_yellow2.png') }}" class="w-auto transition-all duration-500"
                        :class="scrolled ? 'h-8' : 'h-12'" alt="Jabulani Logo">
                    <div class="hidden sm:block">
                        <span class="text-xl font-black tracking-tighter text-white"
                            :class="scrolled ? 'text-lg' : 'text-xl'">Jabulani<span
                                class="text-gold-400">.</span></span>
                    </div>
                </a>

                <!-- Desktop Nav (Sleek Latest Design) -->
                <nav class="hidden lg:flex items-center space-x-1">
                    <a href="{{ route('home') }}"
                        class="px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ request()->routeIs('home') ? 'bg-gold-400 text-dark' : 'text-white hover:bg-white/5' }}">Home</a>

                    <a href="{{ route('about') }}"
                        class="px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ request()->routeIs('about') ? 'text-gold-400' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">About
                        Us</a>

                    <a href="{{ route('products') }}"
                        class="px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ request()->routeIs('products*') ? 'text-gold-400' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">Products</a>

                    <a href="{{ route('stores') }}"
                        class="px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ request()->routeIs('stores') ? 'text-gold-400' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">Stores</a>

                    <!-- Pages Dropdown (Synced with index.html) -->
                    <div class="relative group">
                        <span
                            class="cursor-default px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest flex items-center gap-2 transition-all {{ request()->routeIs('services*') || request()->routeIs('blog*') || request()->routeIs('team*') || request()->routeIs('testimonials*') || request()->routeIs('gallery*') || request()->routeIs('video*') ? 'text-gold-400' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                            Pages <i
                                class="fas fa-chevron-down text-[7px] opacity-40 group-hover:rotate-180 transition-transform"></i>
                        </span>
                        <div
                            class="nav-dropdown absolute top-full left-[-2rem] mt-2 w-64 bg-[#0f0f0f] border border-white/10 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] p-4">
                            <div class="grid grid-cols-1 gap-1">
                                <a href="{{ route('services') }}"
                                    class="flex items-center gap-4 p-3 rounded-xl hover:bg-white/5 transition group">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-white group-hover:text-gold-400">
                                        <i class="fas fa-gears text-xs"></i>
                                    </div>
                                    <p class="text-[10px] font-black text-white uppercase tracking-widest">Services</p>
                                </a>
                                <a href="{{ route('blog') }}"
                                    class="flex items-center gap-4 p-3 rounded-xl hover:bg-white/5 transition group">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-white group-hover:text-gold-400">
                                        <i class="fas fa-newspaper text-xs"></i>
                                    </div>
                                    <p class="text-[10px] font-black text-white uppercase tracking-widest">Blog</p>
                                </a>
                                <a href="{{ route('team') }}"
                                    class="flex items-center gap-4 p-3 rounded-xl hover:bg-white/5 transition group">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-white group-hover:text-gold-400">
                                        <i class="fas fa-users text-xs"></i>
                                    </div>
                                    <p class="text-[10px] font-black text-white uppercase tracking-widest">Team</p>
                                </a>
                                <a href="{{ route('testimonials') }}"
                                    class="flex items-center gap-4 p-3 rounded-xl hover:bg-white/5 transition group">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-white group-hover:text-gold-400">
                                        <i class="fas fa-star text-xs"></i>
                                    </div>
                                    <p class="text-[10px] font-black text-white uppercase tracking-widest">Testimonials
                                    </p>
                                </a>
                                <a href="{{ route('gallery') }}"
                                    class="flex items-center gap-4 p-3 rounded-xl hover:bg-white/5 transition group">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-white group-hover:text-gold-400">
                                        <i class="fas fa-images text-xs"></i>
                                    </div>
                                    <p class="text-[10px] font-black text-white uppercase tracking-widest">Gallery</p>
                                </a>
                                <a href="{{ route('video.gallery') }}"
                                    class="flex items-center gap-4 p-3 rounded-xl hover:bg-white/5 transition group">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-white group-hover:text-gold-400">
                                        <i class="fas fa-play text-xs"></i>
                                    </div>
                                    <p class="text-[10px] font-black text-white uppercase tracking-widest">Video
                                        Showcase</p>
                                </a>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('specials') }}"
                        class="px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ request()->routeIs('specials') ? 'text-gold-400' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">Specials</a>

                    <a href="{{ route('contact') }}"
                        class="px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ request()->routeIs('contact') ? 'text-gold-400' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">Contact
                        Us</a>

                    <a href="{{ route('order.track') }}"
                        class="px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest flex items-center gap-2 transition-all {{ request()->routeIs('order.track') ? 'bg-white/10 text-gold-400' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-truck-fast text-[9px]"></i> Track Delivery
                    </a>

                    {{-- Site-wide Search Bar --}}
                    <form action="{{ route('products') }}" method="GET" class="relative" x-data="{ searchOpen: false }">
                        <button type="button" @click="searchOpen = !searchOpen"
                            class="px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-white hover:bg-white/5 transition-all flex items-center gap-2">
                            <i class="fas fa-search text-[11px]"></i>
                        </button>
                        <div x-show="searchOpen" @click.away="searchOpen = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            class="absolute right-0 top-full mt-2 w-72 bg-[#0f0f0f] border border-white/10 rounded-2xl shadow-2xl p-3 z-50"
                            style="display:none;">
                            <div class="flex items-center gap-2">
                                <input type="text" name="search" placeholder="Search products, SKU..."
                                    class="flex-1 bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-gray-200 placeholder-gray-500 focus:outline-none focus:border-gold-400/40 transition"
                                    autofocus>
                                <button type="submit"
                                    class="bg-gold-400 text-dark px-4 py-2.5 rounded-xl font-black text-xs hover:bg-gold-300 transition">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- User Desktop --}}
                    <div class="ml-4 border-l border-white/10 pl-4">
                        @auth
                            <div class="relative group" x-data="{ open: false }">
                                <button @click="open = !open"
                                    class="flex items-center gap-3 p-1.5 pr-4 rounded-2xl bg-white/5 border border-white/5 hover:border-gold-400/30 transition shadow-inner">
                                    <div
                                        class="w-8 h-8 rounded-xl bg-gold-400 flex items-center justify-center text-dark font-black text-xs italic">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                    <span
                                        class="text-[10px] font-black uppercase tracking-widest text-white">{{ explode(' ', auth()->user()->name)[0] }}</span>
                                </button>
                                <div x-show="open" @click.away="open = false"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    class="absolute right-0 mt-3 w-64 bg-[#0f0f0f] border border-white/10 rounded-[1.5rem] shadow-2xl py-4 z-50 p-2 overflow-hidden"
                                    style="display:none;">
                                    <div class="px-4 py-3 mb-2 bg-white/5 rounded-xl">
                                        <p class="text-[9px] font-black uppercase tracking-widest text-dark-muted mb-1">
                                            Credential identity</p>
                                        <p class="text-xs font-bold text-white truncate italic">{{ auth()->user()->email }}
                                        </p>
                                    </div>
                                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')
                                        <a href="{{ route('admin.dashboard') }}"
                                            class="flex items-center gap-4 px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-white hover:bg-white/5 transition rounded-xl"><i
                                                class="fas fa-tachometer-alt w-4 text-gold-400"></i> View Dashboard</a>
                                    @else
                                        <a href="{{ route('user.dashboard') }}"
                                            class="flex items-center gap-4 px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-white hover:bg-white/5 transition rounded-xl"><i
                                                class="fas fa-tachometer-alt w-4 text-gold-400"></i> View Dashboard</a>
                                        <a href="{{ route('user.orders.index') }}"
                                            class="flex items-center gap-4 px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-white hover:bg-white/5 transition rounded-xl"><i
                                                class="fas fa-history w-4 text-gold-400"></i> Order History</a>
                                    @endif
                                    <div class="h-px bg-white/5 my-2"></div>
                                    <form action="/logout" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full flex items-center gap-4 px-4 py-3 text-[10px] font-black uppercase tracking-widest text-red-400/50 hover:text-red-400 hover:bg-red-400/5 transition rounded-xl"><i
                                                class="fas fa-power-off w-4"></i> Log out</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}"
                                class="btn-gold px-5 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl shadow-[0_0_30px_rgba(245,197,24,0.15)] flex items-center gap-2">
                                <i class="fas fa-user-lock text-[9px]"></i> <span>Login</span>
                            </a>
                        @endauth
                    </div>
                </nav>

                <!-- Actions -->
                <div class="flex items-center space-x-4 md:space-x-4">
                    <!-- Cart -->
                    <a href="{{ route('cart') }}"
                        class="relative w-12 h-12 flex items-center justify-center text-gray-300 hover:text-gold-400 hover:bg-white/5 rounded-2xl transition-all duration-300">
                        <i class="fas fa-shopping-bag text-lg"></i>
                        @php $cartQty = collect(session()->get('cart', []))->sum(); @endphp
                        <span id="cart-badge"
                            class="absolute -top-1 -right-1 bg-gold-400 text-dark text-[10px] font-black w-5 h-5 flex items-center justify-center rounded-lg shadow-xl ring-4 ring-black/50"
                            style="{{ $cartQty > 0 ? '' : 'display: none;' }}">{{ $cartQty }}</span>
                    </a>

                    <!-- Mobile Toggle -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="lg:hidden w-12 h-12 flex items-center justify-center text-white bg-white/5 border border-white/5 rounded-2xl hover:bg-gold-400 hover:text-dark transition-all duration-300">
                        <i class="fas fa-bars-staggered text-xl" x-show="!mobileMenuOpen"></i>
                        <i class="fas fa-times text-xl" x-show="mobileMenuOpen" style="display: none;"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu (Full coverage) -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 -translate-y-full" x-transition:enter-end="opacity-100 translate-y-0"
            class="lg:hidden fixed inset-0 z-[-1] bg-black/98 backdrop-blur-3xl pt-32 pb-12 px-8 overflow-y-auto"
            style="display:none;">
            <div class="space-y-12">
                <div>
                    <p
                        class="text-[10px] font-black uppercase tracking-[0.3em] text-gold-400 mb-6 border-b border-gold-400/20 pb-2">
                        Navigation HUB</p>
                    <div class="flex flex-col gap-6">
                        <a href="{{ route('home') }}"
                            class="text-4xl font-black text-white hover:text-gold-400 transition uppercase tracking-tighter">Home</a>
                        <a href="{{ route('about') }}"
                            class="text-4xl font-black text-white hover:text-gold-400 transition uppercase tracking-tighter">About
                            Us</a>
                        <a href="{{ route('products') }}"
                            class="text-4xl font-black text-white hover:text-gold-400 transition uppercase tracking-tighter">Products</a>
                        <a href="{{ route('stores') }}"
                            class="text-4xl font-black text-white hover:text-gold-400 transition uppercase tracking-tighter">Stores</a>
                        <a href="{{ route('specials') }}"
                            class="text-4xl font-black text-white hover:text-gold-400 transition uppercase tracking-tighter text-red-400">Specials</a>
                        <a href="{{ route('contact') }}"
                            class="text-4xl font-black text-white hover:text-gold-400 transition uppercase tracking-tighter">Contact</a>
                        <a href="{{ route('order.track') }}"
                            class="text-4xl font-black text-gold-400 hover:text-white transition uppercase tracking-tighter">Track
                            Delivery</a>
                    </div>
                </div>

                <div>
                    <p
                        class="text-[10px] font-black uppercase tracking-[0.3em] text-dark-muted mb-6 border-b border-white/5 pb-2">
                        Additional Pages</p>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('services') }}"
                            class="text-xs font-bold text-gray-400 uppercase tracking-widest hover:text-white">Services</a>
                        <a href="{{ route('blog') }}"
                            class="text-xs font-bold text-gray-400 uppercase tracking-widest hover:text-white">Blog</a>
                        <a href="{{ route('team') }}"
                            class="text-xs font-bold text-gray-400 uppercase tracking-widest hover:text-white">Team</a>
                        <a href="{{ route('testimonials') }}"
                            class="text-xs font-bold text-gray-400 uppercase tracking-widest hover:text-white">Testimonials</a>
                        <a href="{{ route('gallery') }}"
                            class="text-xs font-bold text-gray-400 uppercase tracking-widest hover:text-white">Gallery</a>
                        <a href="{{ route('video.gallery') }}"
                            class="text-xs font-bold text-gray-400 uppercase tracking-widest hover:text-white">Video
                            Showcase</a>
                    </div>
                </div>

                <div class="pt-8 border-t border-white/10">
                    @auth
                        <div class="bg-white/5 p-8 rounded-[2rem] border border-white/5 text-center">
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')
                                <div class="grid grid-cols-1 gap-4">
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="btn-gold py-4 text-[11px] tracking-widest">View Dashboard</a>
                                    <form action="/logout" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full bg-red-400/10 text-red-400 hover:bg-red-400/20 py-4 text-[11px] font-black uppercase rounded-xl transition">Log
                                            out</button>
                                    </form>
                                </div>
                            @else
                                <div class="grid grid-cols-1 gap-4">
                                    <a href="{{ route('user.dashboard') }}"
                                        class="btn-gold py-4 text-[11px] tracking-widest">View Dashboard</a>
                                    <a href="{{ route('user.orders.index') }}"
                                        class="btn-gold py-4 text-[11px] tracking-widest opacity-80">Order History</a>
                                    <form action="/logout" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full bg-red-400/10 text-red-400 hover:bg-red-400/20 py-4 text-[11px] font-black uppercase rounded-xl transition">Log
                                            out</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                            class="btn-gold w-full py-6 text-sm tracking-[0.2em] uppercase font-black">Secure Login
                            Access</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col relative w-full overflow-hidden">
        @if($errors->any())
            <div class="max-w-2xl mx-auto w-full px-4 mt-32 mb-[-64px] z-20">
                <div class="bg-red-500/10 border border-red-500/20 rounded-2xl p-6 backdrop-blur-xl">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-red-500/20 flex items-center justify-center text-red-500">
                            <i class="fas fa-exclamation-circle text-sm"></i>
                        </div>
                        <h4 class="text-xs font-black uppercase tracking-widest text-red-400">Attention Required</h4>
                    </div>
                    <ul class="space-y-2">
                        @foreach($errors->all() as $error)
                            <li class="text-xs font-bold text-red-200/70 flex items-start gap-2">
                                <span class="w-1 h-1 rounded-full bg-red-400 mt-1.5 flex-shrink-0"></span>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#050505] border-t border-dark-border py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="col-span-1 md:col-span-1">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3 mb-6">
                    <img src="{{ asset('images/logo_yellow2.png') }}" alt="Jabulani" class="h-10">
                    <span class="text-xl font-black text-white">Jabulani<span class="text-gold-400">.</span></span>
                </a>
                <p class="text-sm text-dark-muted mb-8 leading-relaxed">Your Reliable Hardware Partner Since 2002.
                    Supplying top-quality building materials and hardware globally with unwavering commitment.</p>
                <div class="flex space-x-5">
                    <a href="#"
                        class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center text-gray-400 hover:bg-gold-400 hover:text-dark transition duration-300"><i
                            class="fab fa-facebook-f"></i></a>
                    <a href="#"
                        class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center text-gray-400 hover:bg-gold-400 hover:text-dark transition duration-300"><i
                            class="fab fa-instagram"></i></a>
                    <a href="#"
                        class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center text-gray-400 hover:bg-gold-400 hover:text-dark transition duration-300"><i
                            class="fab fa-twitter"></i></a>
                    <a href="#"
                        class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center text-gray-400 hover:bg-gold-400 hover:text-dark transition duration-300"><i
                            class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div>
                <h4 class="text-white font-black mb-8 uppercase text-xs tracking-[0.2em]">Quick Links</h4>
                <ul class="space-y-4 text-sm text-gray-400">
                    <li><a href="{{ route('about') }}" class="hover:text-gold-400 flex items-center gap-2"><span
                                class="w-1.5 h-1.5 rounded-full bg-gold-400/30 group-hover:bg-gold-400 transition"></span>
                            About Us</a></li>
                    <li><a href="{{ route('products') }}" class="hover:text-gold-400 flex items-center gap-2">Shop
                            Products</a></li>
                    <li><a href="{{ route('stores') }}" class="hover:text-gold-400 flex items-center gap-2">Find a
                            Branch</a></li>
                    <li><a href="{{ route('blog') }}" class="hover:text-gold-400 flex items-center gap-2">Inside
                            Jabulani</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-black mb-8 uppercase text-xs tracking-[0.2em]">Customer Care</h4>
                <ul class="space-y-4 text-sm text-gray-400">
                    <li><a href="{{ route('contact') }}" class="hover:text-gold-400">Contact Us</a></li>
                    <li><a href="{{ route('order.track') }}" class="hover:text-gold-400">Track Order</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-gold-400">My Account</a></li>
                    <li><a href="#" class="hover:text-gold-400">Privacy Policy</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-black mb-8 uppercase text-xs tracking-[0.2em]">Contact Info</h4>
                <ul class="space-y-5 text-sm text-gray-400">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt mt-1 text-gold-400"></i>
                        <span>Eastern Cape, South Africa<br /><span class="text-xs text-dark-muted">MT Frere, Qumbu,
                                Tsolo & Umtata</span></span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-phone text-gold-400"></i>
                        <span>+27 6606 84585</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-envelope text-gold-400"></i>
                        <span class="truncate">info@jabulani.co.za</span>
                    </li>
                </ul>
            </div>
        </div>
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-20 pt-10 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-xs text-dark-muted tracking-wide">&copy; {{ date('Y') }} JABULANI GROUP OF COMPANIES. ALL
                RIGHTS RESERVED.</p>
        </div>
    </footer>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/27660684585" target="_blank"
        class="fixed bottom-8 right-8 bg-[#25d366] text-white w-16 h-16 rounded-2xl flex items-center justify-center text-3xl shadow-[0_10px_40px_rgba(37,211,102,0.3)] hover:scale-110 active:scale-95 transition-all z-50 group">
        <i class="fab fa-whatsapp"></i>
        <span
            class="absolute right-full mr-4 bg-white text-dark text-xs font-bold px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition whitespace-nowrap shadow-xl">Chat
            with us</span>
    </a>

    @stack('js')
</body>

</html>