<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Jabulani Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    
    {{-- Dynamic Theme Injection --}}
    @if(isset($settings['theme_primary_color']))
    <style>
        :root {
            /* Primary Colors */
            --orange-500: {{ $settings['theme_primary_color'] }};
            --orange-400: {{ $settings['theme_primary_color'] }};
            --orange-300: {{ $settings['theme_primary_color'] }};
            --orange-600: {{ $settings['theme_primary_color'] }};
            --border-focus: {{ $settings['theme_primary_color'] }};
            --border-accent: {{ $settings['theme_primary_color'] }}59; /* 35% opacity */
            --button-text: {{ $settings['theme_primary_text_color'] ?? '#ffffff' }};

            /* Background & Surface Colors */
            @if(isset($settings['theme_background_color']))
            --carbon-950: {{ $settings['theme_background_color'] }};
            --carbon-900: {{ $settings['theme_surface_color'] ?? $settings['theme_background_color'] }};
            --text-primary: {{ $settings['theme_text_color'] ?? '#ffffff' }};
            --text-secondary: {{ $settings['theme_muted_text_color'] ?? '#a0a0a0' }};
            @endif
        }
        .admin-portal .btn-jabulani, 
        .admin-portal .btn-premium-gold,
        .admin-portal .pagination .page-item.active .page-link {
            color: var(--button-text) !important;
        }
        .admin-portal .ap-nav-link.active {
            background: linear-gradient(90deg, {{ $settings['theme_primary_color'] }}33, {{ $settings['theme_primary_color'] }}14);
            border-left-color: var(--orange-500);
        }
        .admin-portal .ap-nav-link.active i {
            color: var(--orange-500) !important;
        }
        .admin-portal .btn-link {
            color: var(--orange-500) !important;
        }
        .admin-portal .gradient-title {
            background: linear-gradient(135deg, {{ $settings['theme_primary_color'] }} 0%, {{ $settings['theme_primary_color'] }}bb 50%, var(--text-primary) 100%);
            -webkit-background-clip: text;
            background-clip: text;
        }
        .admin-portal .notif-dot {
            background: var(--orange-500);
            box-shadow: 0 0 6px var(--orange-500);
        }
        @if(isset($settings['theme_text_color']) && $settings['theme_text_color'] === '#000000')
        .admin-portal .card, .admin-portal .modal-content, .admin-portal .dropdown-menu {
            border-color: rgba(0,0,0,0.1) !important;
        }
        @endif
    </style>
    @endif

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('css')
</head>

<body class="admin-portal">

    <div class="ap-sidebar">
        {{-- Brand --}}
        <div class="ap-sidebar-brand">
            <div class="brand-name">Jabulani</div>
            <span class="brand-sub">Admin Portal</span>
        </div>

        {{-- Navigation --}}
        <div class="ap-sidebar-scroll">
            <div class="ap-nav-group-label">Operations</div>
            <a href="{{ route('admin.dashboard') }}"
               class="ap-nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            @if(auth()->user()->hasPermission('manage_orders'))
            <a href="{{ route('admin.orders.index') }}"
               class="ap-nav-link {{ request()->is('admin/orders*') ? 'active' : '' }}">
                <i class="fas fa-shopping-bag"></i> Orders
            </a>
            @endif

            @if(auth()->user()->hasPermission('manage_products'))
            <div class="ap-nav-group-label">Catalog</div>
            <a href="{{ route('admin.products.index') }}"
               class="ap-nav-link {{ request()->is('admin/products*') ? 'active' : '' }}">
                <i class="fas fa-tools"></i> Products
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="ap-nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}">
                <i class="fas fa-list"></i> Categories
            </a>
            <a href="{{ route('admin.brands.index') }}"
               class="ap-nav-link {{ request()->is('admin/brands*') ? 'active' : '' }}">
                <i class="fas fa-tag"></i> Brands
            </a>
            @endif

            @if(auth()->user()->hasPermission('manage_users'))
            <div class="ap-nav-group-label">Network</div>
            <a href="{{ route('admin.stores.index') }}"
               class="ap-nav-link {{ request()->is('admin/stores*') ? 'active' : '' }}">
                <i class="fas fa-store"></i> Stores
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="ap-nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                <i class="fas fa-users-cog"></i> Staff
            </a>
            <a href="{{ route('admin.guests.index') }}"
               class="ap-nav-link {{ request()->is('admin/guests*') ? 'active' : '' }}">
                <i class="fas fa-user-shield"></i> Guests
            </a>
            @endif

            @if(auth()->user()->hasPermission('manage_content'))
            <div class="ap-nav-group-label">Website</div>
            <a href="{{ route('admin.banners.index') }}"
               class="ap-nav-link {{ request()->is('admin/banners*') ? 'active' : '' }}">
                <i class="fas fa-image"></i> Banners
            </a>
            <a href="{{ route('admin.services.index') }}"
               class="ap-nav-link {{ request()->is('admin/services*') ? 'active' : '' }}">
                <i class="fas fa-concierge-bell"></i> Services
            </a>
            <a href="{{ route('admin.blog.index') }}"
               class="ap-nav-link {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                <i class="fas fa-newspaper"></i> Blog Posts
            </a>
            <a href="{{ route('admin.blog-categories.index') }}"
               class="ap-nav-link {{ request()->routeIs('admin.blog-categories.*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i> Blog Categories
            </a>
            <a href="{{ route('admin.gallery.index') }}"
               class="ap-nav-link {{ request()->is('admin/gallery*') ? 'active' : '' }}">
                <i class="fas fa-images"></i> Gallery
            </a>
            <a href="{{ route('admin.team.index') }}"
               class="ap-nav-link {{ request()->is('admin/team*') ? 'active' : '' }}">
                <i class="fas fa-user-friends"></i> Team Members
            </a>
            @endif

            <div class="ap-nav-group-label">System</div>
            @if(auth()->user()->hasPermission('manage_settings'))
            <a href="{{ route('admin.settings.payments') }}"
               class="ap-nav-link {{ request()->routeIs('admin.settings.payments') ? 'active' : '' }}">
                <i class="fas fa-credit-card"></i> Payment Settings
            </a>
            <a href="{{ route('admin.settings.invoice') }}"
               class="ap-nav-link {{ request()->routeIs('admin.settings.invoice') ? 'active' : '' }}">
                <i class="fas fa-file-invoice"></i> Invoice Settings
            </a>
            <a href="{{ route('admin.settings.theme') }}"
               class="ap-nav-link {{ request()->routeIs('admin.settings.theme') ? 'active' : '' }}">
                <i class="fas fa-palette"></i> Theme Settings
            </a>
            @endif
            <a href="{{ route('admin.marketing.index') }}"
               class="ap-nav-link {{ request()->is('admin/marketing*') ? 'active' : '' }}">
                <i class="fas fa-bullhorn"></i> Marketing Push
            </a>
        </div>

        {{-- Footer --}}
        <div class="ap-sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="ap-nav-link border-0 bg-transparent w-100 text-start"
                    style="color: var(--error-color); opacity: 0.75; cursor:pointer;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>

    {{-- Main --}}
    <div class="ap-main">

        {{-- Topbar --}}
        <div class="ap-topbar">
            <div class="ap-topbar-title gradient-title">@yield('title', 'Dashboard')</div>

            <div class="d-flex align-items-center gap-3">

                {{-- Notification Bell --}}
                <div class="dropdown">
                    <a href="#" class="position-relative text-decoration-none" style="color: var(--text-secondary);"
                       id="notifBtn" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="far fa-bell fs-5"></i>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="notif-dot"></span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-end p-0"
                         aria-labelledby="notifBtn" style="width: 310px; max-height: 440px; overflow-y: auto;">
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom border-default">
                            <span style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Notifications</span>
                            <a href="{{ route('admin.notifications.mark-all-read') }}"
                               style="font-size: 0.7rem; color: var(--orange-400); text-decoration: none; font-weight: 600;">Clear all</a>
                        </div>
                        @forelse(auth()->user()->notifications->take(10) as $notif)
                            <a href="{{ $notif->data['url'] ?? '#' }}"
                               class="dropdown-item py-2 {{ $notif->read_at ? '' : '' }}"
                               style="white-space: normal; {{ !$notif->read_at ? 'border-left: 2px solid var(--orange-500);' : '' }}">
                                <div class="d-flex gap-2 align-items-start">
                                    <div style="width: 28px; height: 28px; border-radius: 6px; background: rgba(255,140,0,0.12); display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top: 1px;">
                                        <i class="fas fa-{{ ($notif->data['type'] ?? '') === 'new_order' ? 'shopping-bag' : 'bell' }} fa-xs" style="color: var(--orange-400);"></i>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.78rem; color: var(--text-primary); font-weight: 500;">{{ $notif->data['message'] }}</div>
                                        <div style="font-size: 0.68rem; color: var(--text-muted); margin-top: 2px;">{{ $notif->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="py-4 text-center" style="font-size: 0.78rem; color: var(--text-muted);">
                                <i class="far fa-bell-slash mb-2 d-block fs-5 opacity-30"></i>
                                No notifications
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Divider --}}
                <div class="vr" style="height: 20px;"></div>

                {{-- User Menu --}}
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle"
                       data-bs-toggle="dropdown" id="userMenu" style="color: var(--text-secondary);">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background={{ str_replace('#', '', $settings['theme_primary_color'] ?? 'FF8C00') }}&color={{ str_replace('#', '', $settings['theme_primary_text_color'] ?? 'ffffff') }}&bold=true"
                             width="30" height="30" class="rounded-circle" alt="Avatar">
                        <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-primary);">{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end mt-2" aria-labelledby="userMenu">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user-circle me-2 opacity-50"></i> Profile</a></li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item" style="color: var(--error-color) !important;">
                                    <i class="fas fa-sign-out-alt me-2 opacity-50"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="ap-content">
            @if(session('success'))
                <div class="alert alert-success d-flex align-items-center gap-2 mb-4 py-2">
                    <i class="fas fa-check-circle" style="color: var(--success-color);"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger d-flex align-items-center gap-2 mb-4 py-2">
                    <i class="fas fa-exclamation-circle" style="color: var(--error-color);"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({ placeholder: "Select options", allowClear: true, width: '100%' });
        });
    </script>
    @stack('js')
</body>
</html>