<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Manager - {{ $store_name ?? 'Jabulani Hardware' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --jabulani-orange: #FF8C00;
            --jabulani-black: #1A1A1A;
            --jabulani-grey: #333333;
        }

        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            background-color: var(--jabulani-black);
            min-height: 100vh;
            color: white;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar .nav-link {
            color: #adb5bd;
            padding: 0.8rem 1.5rem;
            border-left: 4px solid transparent;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 140, 0, 0.1);
            border-left-color: var(--jabulani-orange);
        }

        .navbar {
            background-color: white;
            border-bottom: 2px solid var(--jabulani-orange);
        }

        .btn-jabulani {
            background-color: var(--jabulani-orange);
            color: white;
            border: none;
        }

        .btn-jabulani:hover {
            background-color: #e67e00;
            color: white;
        }

        .card {
            border: none;
            border-radius: 12px;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block sidebar py-4">
                <div class="position-sticky">
                    <div class="px-3 mb-4">
                        <h5 class="text-white mb-1 fw-bold">Jabulani Branch</h5>
                        <small class="text-warning">{{ auth()->user()->managedStore->name ?? 'Unassigned' }}</small>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('branch/dashboard') ? 'active' : '' }}"
                                href="{{ route('branch.dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('branch/orders*') ? 'active' : '' }}"
                                href="{{ route('branch.orders.index') }}">
                                <i class="bi bi-cart-check me-2"></i> Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('branch/stocks*') ? 'active' : '' }}"
                                href="{{ route('branch.stocks.index') }}">
                                <i class="bi bi-box-seam me-2"></i> Local Stock
                            </a>
                        </li>

                        <li class="nav-item mt-5 border-top pt-3">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-md-4">
                <nav class="navbar navbar-expand-lg px-0 py-3 mb-4 mt-2">
                    <div class="container-fluid px-0">
                        <span class="navbar-brand h1 mb-0">@yield('title', 'Branch Dashboard')</span>
                        <div class="d-flex align-items-center">
                            <div class="text-end me-3">
                                <div class="fw-bold">{{ auth()->user()->name }}</div>
                                <small class="text-muted">Branch Manager</small>
                            </div>
                            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=FF8C00&color=fff"
                                alt="user" width="40" height="40" class="rounded-circle shadow-sm">
                        </div>
                    </div>
                </nav>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="py-3">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>