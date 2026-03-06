<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Jabulani Hardware</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        :root {
            --jabulani-orange: #FF8C00;
            --jabulani-black: #111111;
            --jabulani-dark-grey: #1a1a1a;
            --jabulani-text-grey: #888888;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .sidebar {
            background-color: var(--jabulani-black);
            height: 100vh;
            color: white;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            width: 250px;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            flex-shrink: 0;
        }

        .sidebar-content {
            flex-grow: 1;
            overflow-y: auto;
            padding: 1rem 0;
        }

        /* Custom Scrollbar */
        .sidebar-content::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-content::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-content::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .sidebar-content::-webkit-scrollbar-thumb:hover {
            background: var(--jabulani-orange);
        }

        .sidebar .nav-link {
            color: var(--jabulani-text-grey);
            font-size: 0.85rem;
            transition: all 0.2s ease;
            border-radius: 6px;
            margin: 1px 12px;
            padding: 0.6rem 1rem !important;
        }

        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.03);
            transform: translateX(3px);
        }

        .sidebar .nav-link.active {
            color: white;
            background-color: var(--jabulani-orange);
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(255, 140, 0, 0.2);
        }

        .sidebar .nav-link i {
            width: 18px;
            font-size: 0.9rem;
        }

        .sidebar-heading {
            font-size: 0.7rem !important;
            letter-spacing: 1.2px;
            color: rgba(255, 255, 255, 0.3) !important;
            margin-top: 1rem;
        }

        .main-content {
            margin-left: 250px;
            min-height: 100vh;
        }

        .navbar {
            background-color: white;
            border-bottom: 1px solid #eff2f5;
            padding: 0.75rem 1.5rem !important;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .btn-jabulani {
            background-color: var(--jabulani-orange);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .btn-jabulani:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 140, 0, 0.3);
            color: white;
        }

        /* Select2 Dark Support if needed, otherwise standard Bootstrap overrides */
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="sidebar col-md-2">
                <div class="sidebar-header">
                    <h4 class="text-white fw-bold mb-0">Jabulani</h4>
                    <small class="text-warning text-uppercase ls-1" style="font-size: 0.65rem;">Super Admin</small>
                </div>

                <div class="sidebar-content">
                    <div class="px-3">
                        <!-- Group: Operations -->
                        <div class="sidebar-heading px-3 pb-2 small text-uppercase fw-bold">
                            Operations
                        </div>
                        <ul class="nav flex-column mb-3">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->is('admin/dashboard') ? 'active' : '' }}"
                                    href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-th-large me-2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->is('admin/orders*') ? 'active' : '' }}"
                                    href="{{ route('admin.orders.index') }}">
                                    <i class="fas fa-shopping-bag me-2"></i> Orders
                                </a>
                            </li>
                        </ul>

                        <!-- Group: Catalog -->
                        <div class="sidebar-heading px-3 pb-2 small text-uppercase fw-bold">
                            Catalog
                        </div>
                        <ul class="nav flex-column mb-3">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->is('admin/products*') ? 'active' : '' }}"
                                    href="{{ route('admin.products.index') }}">
                                    <i class="fas fa-tools me-2"></i> Products
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->is('admin/categories*') ? 'active' : '' }}"
                                    href="{{ route('admin.categories.index') }}">
                                    <i class="fas fa-list me-2"></i> Categories
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->is('admin/brands*') ? 'active' : '' }}"
                                    href="{{ route('admin.brands.index') }}">
                                    <i class="fas fa-tag me-2"></i> Brands
                                </a>
                            </li>
                        </ul>

                        <!-- Group: Network -->
                        <div class="sidebar-heading px-3 pb-2 small text-uppercase fw-bold">
                            Network
                        </div>
                        <ul class="nav flex-column mb-3">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->is('admin/stores*') ? 'active' : '' }}"
                                    href="{{ route('admin.stores.index') }}">
                                    <i class="fas fa-store me-2"></i> Stores
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->is('admin/users*') ? 'active' : '' }}"
                                    href="{{ route('admin.users.index') }}">
                                    <i class="fas fa-users-cog me-2"></i> Staff
                                </a>
                            </li>
                        </ul>

                        <!-- Group: Website -->
                        <div class="sidebar-heading px-3 pb-2 small text-uppercase fw-bold">
                            Website
                        </div>
                        <ul class="nav flex-column mb-3">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->is('admin/banners*') ? 'active' : '' }}"
                                    href="{{ route('admin.banners.index') }}">
                                    <i class="fas fa-image me-2"></i> Banners
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->is('admin/services*') ? 'active' : '' }}"
                                    href="{{ route('admin.services.index') }}">
                                    <i class="fas fa-concierge-bell me-2"></i> Services
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}"
                                    href="{{ route('admin.blog.index') }}">
                                    <i class="fas fa-newspaper me-2"></i> Blog Posts
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.blog-categories.*') ? 'active' : '' }}"
                                    href="{{ route('admin.blog-categories.index') }}">
                                    <i class="fas fa-tags me-2"></i> Blog Categories
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->is('admin/gallery*') ? 'active' : '' }}"
                                    href="{{ route('admin.gallery.index') }}">
                                    <i class="fas fa-images me-2"></i> Gallery
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->is('admin/team*') ? 'active' : '' }}"
                                    href="{{ route('admin.team.index') }}">
                                    <i class="fas fa-user-friends me-2"></i> Team Members
                                </a>
                            </li>
                        </ul>

                        <div class="nav-item mt-2 border-top border-secondary pt-3 pb-4">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="nav-link border-0 bg-transparent w-100 text-start d-flex align-items-center text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 main-content px-md-4">
                <nav class="navbar navbar-expand-lg px-0 py-3 mb-4 mt-2">
                    <div class="container-fluid px-0">
                        <span class="navbar-brand h1 mb-0">@yield('title', 'Dashboard')</span>
                        <div class="d-flex align-items-center">
                            <span class="me-3">{{ auth()->user()->name }}</span>
                            <div class="flex-shrink-0 dropdown">
                                <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle"
                                    id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}" alt="mdo"
                                        width="32" height="32" class="rounded-circle">
                                </a>
                            </div>
                        </div>
                    </div>
                </nav>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                placeholder: "Select options",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
    @stack('js')
</body>

</html>