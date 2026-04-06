<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'User Portal') — {{ config('app.name', 'Jabulani Store') }}</title>

    <!-- Robust Font Loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: { 
                            DEFAULT: '{{ $settings["theme_primary_color"] ?? "#f5c518" }}', 
                            400: '{{ $settings["theme_primary_color"] ?? "#f5c518" }}', 
                            500: '{{ $settings["theme_primary_color"] ?? "#d4a200" }}', 
                            600: '{{ $settings["theme_primary_color"] ?? "#a87c00" }}' 
                        },
                        dark: { 
                            DEFAULT: '{{ $settings["theme_background_color"] ?? "#0a0a0a" }}', 
                            card: '{{ $settings["theme_surface_color"] ?? "#111111" }}', 
                            border: '{{ (isset($settings["theme_text_color"]) && $settings["theme_text_color"] === "#000000") ? "#e5e7eb" : "#2a2a2a" }}', 
                            muted: '{{ $settings["theme_muted_text_color"] ?? "#888888" }}' 
                        }
                    },
                    fontFamily: {
                        sans: ['Outfit', 'Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-main: {{ $settings['theme_background_color'] ?? '#050505' }};
            --bg-surface: {{ $settings['theme_surface_color'] ?? '#111111' }};
            --text-main: {{ $settings['theme_text_color'] ?? '#ffffff' }};
            --text-muted: {{ $settings['theme_muted_text_color'] ?? '#888888' }};
            --brand-primary: {{ $settings['theme_primary_color'] ?? '#f5c518' }};
            @php
                $primaryHex = $settings['theme_primary_color'] ?? '#f5c518';
                $hex = ltrim($primaryHex, '#');
                if (strlen($hex) == 3) $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
                $r = hexdec(substr($hex, 0, 2));
                $g = hexdec(substr($hex, 2, 2));
                $b = hexdec(substr($hex, 4, 2));
                $primaryRgb = "$r, $g, $b";
            @endphp
            --brand-primary-rgb: {{ $primaryRgb }};
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        .text-white { color: var(--text-main) !important; }
        .text-dark-muted { color: var(--text-muted) !important; }
        .bg-dark { background-color: var(--bg-main) !important; }
        .bg-dark-card { background-color: var(--bg-surface) !important; }
        .border-dark-border { border-color: {{ (isset($settings["theme_text_color"]) && $settings["theme_text_color"] === "#000000") ? "rgba(0,0,0,0.1)" : "rgba(255,255,255,0.1)" }} !important; }

        .premium-glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .gradient-text {
            background: linear-gradient(135deg, {{ $settings['theme_primary_color'] ?? '#f5c518' }} 0%, #ffffff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stat-card-glow {
            box-shadow: 0 0 30px rgba(var(--brand-primary-rgb), 0.05);
        }

        /* Nav Link Hover */
        .nav-link-active {
            background: linear-gradient(90deg, {{ $settings['theme_primary_color'] ?? '#f5c518' }}1a 0%, transparent 100%);
            border-left: 3px solid {{ $settings['theme_primary_color'] ?? '#f5c518' }};
            color: {{ $settings['theme_primary_color'] ?? '#f5c518' }} !important;
        }

        .nav-link-hover:hover {
            background: rgba(255, 255, 255, 0.02);
            color: #ffffff !important;
        }
    </style>
    @stack('css')
</head>

<body class="antialiased selection:bg-gold-400 selection:text-black">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="hidden lg:flex w-80 flex-col fixed inset-y-0 border-r border-white/5 bg-[#0a0a0a] z-50">
            <div class="p-8 pb-12">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-gold-400 flex items-center justify-center rounded-xl rotate-3 group-hover:rotate-12 transition-transform duration-500">
                        <span class="text-black font-black text-xl italic p-2">J</span>
                    </div>
                    <span class="text-xl font-black italic tracking-tighter uppercase text-white">Jabulani <span class="text-gold-400">Portal</span></span>
                </a>
            </div>

            <nav class="flex-1 px-4 space-y-1">
                <div class="px-4 py-3 text-[10px] font-black uppercase tracking-[0.2em] text-dark-muted opacity-50">Core Operations</div>
                
                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-4 px-4 py-4 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 {{ request()->routeIs('user.dashboard') ? 'nav-link-active' : 'text-dark-muted nav-link-hover' }}">
                    <i class="fas fa-th-large text-sm"></i> Dashboard
                </a>

                <a href="{{ route('user.orders.index') }}" class="flex items-center gap-4 px-4 py-4 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 {{ request()->routeIs('user.orders.*') ? 'nav-link-active' : 'text-dark-muted nav-link-hover' }}">
                    <i class="fas fa-shopping-bag text-sm"></i> Order History
                </a>

                <div class="pt-6 px-4 py-3 text-[10px] font-black uppercase tracking-[0.2em] text-dark-muted opacity-50">Settings</div>

                <a href="{{ route('profile.addresses') }}" class="flex items-center gap-4 px-4 py-4 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 {{ request()->is('profile/addresses*') ? 'nav-link-active' : 'text-dark-muted nav-link-hover' }}">
                    <i class="fas fa-map-marker-alt text-sm"></i> Shipping Profiles
                </a>

                <a href="{{ route('user.notifications.index') }}" class="flex items-center gap-4 px-4 py-4 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 {{ request()->routeIs('user.notifications.*') ? 'nav-link-active' : 'text-dark-muted nav-link-hover' }}">
                    <i class="fas fa-bell text-sm"></i> Notifications
                </a>
            </nav>

            <div class="p-8 mt-auto">
                <div class="premium-glass p-6 rounded-3xl border-white/5 text-center relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gold-400/5 translate-y-full group-hover:translate-y-0 transition-transform duration-700"></div>
                    <div class="relative z-10">
                        <p class="text-[10px] font-black uppercase tracking-widest text-gold-400 mb-2">Need Support?</p>
                        <p class="text-[11px] text-gray-400 leading-relaxed mb-4">Our premium concierge is ready to assist you.</p>
                        <a href="{{ route('contact') }}" class="block w-full py-3 bg-white/5 rounded-xl text-[10px] font-black uppercase tracking-widest text-white border border-white/10 hover:border-gold-400/30 transition-all">Get in touch</a>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 lg:ml-80">
            <!-- Top Header -->
            <header class="h-24 sticky top-0 bg-[#050505]/80 backdrop-blur-xl border-b border-white/5 z-40 px-8 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <button class="lg:hidden w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 text-white">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-xs font-black uppercase tracking-[0.3em] text-dark-muted italic">
                        Logged in as <span class="text-white">{{ auth()->user()->name }}</span>
                    </h1>
                </div>

                <div class="flex items-center gap-6">
                    <a href="{{ route('products') }}" class="hidden sm:flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-gold-400 hover:text-white transition-all">
                        <i class="fas fa-plus-circle"></i> New Order
                    </a>
                    <div class="w-px h-6 bg-white/10 hidden sm:block"></div>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-full bg-red-500/10 border border-red-500/20 text-red-500 hover:bg-red-500 hover:text-white transition-all">
                            <i class="fas fa-power-off text-sm"></i>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-8 pb-20">
                @if(session('success'))
                    <div class="mb-8 p-6 rounded-2xl bg-green-500/10 border border-green-500/20 text-green-400 flex items-center gap-4 shadow-2xl">
                        <i class="fas fa-check-circle"></i>
                        <span class="text-xs font-bold uppercase tracking-widest">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-8 p-6 rounded-2xl bg-red-500/10 border border-red-500/20 text-red-400 flex items-center gap-4 shadow-2xl">
                        <i class="fas fa-exclamation-circle"></i>
                        <span class="text-xs font-bold uppercase tracking-widest">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="p-8 border-t border-white/5 text-center lg:text-left">
                <p class="text-[10px] font-black uppercase tracking-widest text-dark-muted">
                    &copy; {{ date('Y') }} Jabulani Group (PTY) Ltd. <span class="mx-2 text-white/10">|</span> Secure Partner Portal
                </p>
            </footer>
        </main>
    </div>

    @stack('js')
</body>

</html>
