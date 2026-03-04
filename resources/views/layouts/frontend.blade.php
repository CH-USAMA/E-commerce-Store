<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <meta name="description"
        content="@yield('meta_description', 'Jabulani Group of Companies provides reliable and efficient services in hardware, building material, construction, and logistics across South Africa.')">
    <meta name="keywords"
        content="@yield('meta_keywords', 'Jabulani, construction services, Jabulani Group, hardware stores, building materials, South Africa')">
    <meta name="author" content="Jabulani Group">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Jabulani - Hardware & Building Materials')">
    <meta property="og:description" content="@yield('meta_description', 'Your Reliable Hardware Partner Since 2002.')">
    <meta property="og:image" content="{{ asset('images/logo_yellow2.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', 'Jabulani - Hardware & Building Materials')">
    <meta property="twitter:description"
        content="@yield('meta_description', 'Your Reliable Hardware Partner Since 2002.')">
    <meta property="twitter:image" content="{{ asset('images/logo_yellow2.png') }}">

    <title>@yield('title', 'Jabulani - Group of hardware stores and building material companies')</title>

    <!-- Favicon Icon -->
    <link href="{{ asset('images/logo_yellow2.png') }}" rel="shortcut icon" type="image/x-icon" />
    <link href="{{ asset('favicon-96x96.png') }}" rel="icon" sizes="96x96" type="image/png">
    <link href="{{ asset('favicon.svg') }}" rel="icon" type="image/svg+xml" />
    <link href="{{ asset('apple-touch-icon.png') }}" rel="apple-touch-icon" sizes="180x180" />
    <link href="{{ asset('site.webmanifest') }}" rel="manifest" />

    <!-- Google Fonts Css-->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Fustat:wght@200..800&display=swap" rel="stylesheet" />

    <!-- Bootstrap Css -->
    <link href="{{ asset('css/bootstrap.min.css') }}" media="screen" rel="stylesheet" />
    <!-- SlickNav Css -->
    <link href="{{ asset('css/slicknav.min.css') }}" rel="stylesheet" />
    <!-- Swiper Css -->
    <link href="{{ asset('css/swiper-bundle.min.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icon Css-->
    <link href="{{ asset('css/all.css') }}" media="screen" rel="stylesheet" />
    <!-- Animated Css -->
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet" />
    <!-- Magnific Popup Core Css File -->
    <link href="{{ asset('css/magnific-popup.css') }}" rel="stylesheet" />
    <!-- Mouse Cursor Css File -->
    <link href="{{ asset('css/mousecursor.css') }}" rel="stylesheet" />
    <!-- Main Custom Css -->
    <link href="{{ asset('css/custom.css') }}" media="screen" rel="stylesheet" />

    @stack('css')
    <style>
        /* High-priority alert overrides */
        .alert-danger,
        .container .alert-danger,
        body .alert-danger {
            background-color: #ff0000 !important;
            background: #ff0000 !important;
            color: #ffffff !important;
            border: 2px solid #ffffff !important;
            opacity: 1 !important;
            visibility: visible !important;
            display: block !important;
            z-index: 9999 !important;
        }

        .alert-danger *,
        .alert-danger ul,
        .alert-danger li,
        .alert-danger p,
        .alert-danger span,
        .alert-danger div {
            color: #ffffff !important;
            background-color: transparent !important;
            background: transparent !important;
            opacity: 1 !important;
            visibility: visible !important;
            font-weight: 700 !important;
        }

        .invalid-feedback {
            color: #ffffff !important;
            background-color: #ff0000 !important;
            padding: 8px !important;
            display: block !important;
            font-weight: 700 !important;
            border-radius: 4px !important;
        }
    </style>
</head>

<body>
    <!-- Preloader Start -->
    <div class="preloader">
        <div class="loading-container">
            <div class="loading"></div>
            <div id="loading-icon"><img alt="" src="{{ asset('images/loader.png') }}" /></div>
        </div>
    </div>
    <!-- Preloader End -->

    <!-- Header Start -->
    <header class="main-header" id="main-header">
        <div class="header-sticky">
            <nav class="navbar navbar-expand-lg">
                <div class="container">
                    <!-- Logo Start -->
                    <a class="navbar-brand" href="{{ route('home') }}">
                        <img alt="Logo" src="{{ asset('images/logo_yellow2.png') }}" />
                        <div>
                            <h3 class="logoTitle">Jabulani<span style="color: yellow;">.</span></h3>
                        </div>
                    </a>

                    <button id="themeToggle" title="Switch Light/Dark Mode"
                        style="background: none; border: none; cursor: pointer;margin-left: auto; margin-top: 6px;">
                        <i class="fa fa-adjust" style="color: var(--accent-color); font-size: 20px;"></i>
                    </button>
                    <!-- Logo End -->

                    <!-- Main Menu Start -->
                    <div class="collapse navbar-collapse main-menu">
                        <div class="nav-menu-wrapper">
                            <ul class="navbar-nav mr-auto" id="menu">
                                <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About Us</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('products') }}">Products</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('stores') }}">Stores</a></li>
                                <li class="nav-item submenu"><a class="nav-link" href="#">Pages</a>
                                    <ul>
                                        <li class="nav-item"><a class="nav-link"
                                                href="{{ route('services') }}">Services</a></li>
                                        <li class="nav-item"><a class="nav-link" href="{{ route('blog') }}">Blog</a>
                                        </li>
                                        <li class="nav-item"><a class="nav-link" href="{{ route('team') }}">Team</a>
                                        </li>
                                        <li class="nav-item"><a class="nav-link"
                                                href="{{ route('testimonials') }}">Testimonials</a></li>
                                        <li class="nav-item"><a class="nav-link"
                                                href="{{ route('gallery') }}">Gallery</a></li>
                                        <li class="nav-item"><a class="nav-link"
                                                href="{{ route('video.gallery') }}">Video Showcase</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('specials') }}">Specials</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact Us</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link position-relative" href="{{ route('cart') }}">
                                        <i class="fas fa-shopping-cart"></i>
                                        <span id="cart-badge"
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                            style="font-size: 0.6rem;">0</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <!-- Header End -->

    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    @yield('content')

    <!-- Footer Start -->
    <footer class="main-footer">
        <!-- Let's Work Together Start -->
        <div class="footer-work-together">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="work-together-box">
                            <div class="work-together-content">
                                <h3>Let's Build</h3>
                                <h2>Let's Build Together</h2>
                            </div>
                            <div class="work-together-btn">
                                <a href="{{ route('contact') }}">
                                    <img src="{{ asset('images/arrow-dark.svg') }}" alt="">
                                    <span>Get in Touch</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Let's Work Together End -->
        <div class="container">
            <!-- Footer Main Start -->
            <div class="footer-main">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <!-- About Footer Start -->
                        <div class="about-footer">
                            <!-- Footer Logo Start -->
                            <div class="footer-logo">
                                <a href="{{ route('home') }}">
                                    <img src="{{ asset('images/logo_yellow2.png') }}" alt="Jabulani">
                                    <div>
                                        <h3 class="logoTitle">Jabulani<span style="color: yellow;">.</span></h3>
                                    </div>
                                </a>
                            </div>
                            <!-- Footer Logo End -->
                            <!-- Footer Contact Box Start -->
                            <div class="footer-contact-box">
                                <div class="footer-contact-item">
                                    <div class="icon-box">
                                        <img alt="" src="{{ asset('images/icon-phone.svg') }}" />
                                    </div>
                                    <div class="footer-contact-content">
                                        <p>(+27) 6606 84585</p>
                                    </div>
                                </div>
                                <div class="footer-contact-item">
                                    <div class="icon-box">
                                        <img alt="" src="{{ asset('images/icon-mail.svg') }}" />
                                    </div>
                                    <div class="footer-contact-content">
                                        <p>jabulanigroup2002@gmail.com</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Footer Contact Box End -->
                        </div>
                        <!-- About Footer End -->
                    </div>
                    <div class="col-lg-2 col-md-3 col-6">
                        <!-- Footer Links start -->
                        <div class="footer-links">
                            <h3>quick link</h3>
                            <ul>
                                <li><a href="{{ route('home') }}">Home</a></li>
                                <li><a href="{{ route('about') }}">About Us</a></li>
                                <li><a href="{{ route('services') }}">Services</a></li>
                                <li><a href="{{ route('blog') }}">Blog</a></li>
                            </ul>
                        </div>
                        <!-- Footer Links end -->
                    </div>
                    <div class="col-lg-2 col-md-3 col-6">
                        <!-- Footer Links start -->
                        <div class="footer-links">
                            <h3>support</h3>
                            <ul>
                                <li><a href="{{ route('contact') }}">Contact</a></li>
                                <li><a href="#">Terms &amp; Conditions</a></li>
                                <li><a href="{{ route('stores') }}">Our Stores</a></li>
                                <li><a href="{{ route('products') }}">Products</a></li>
                            </ul>
                        </div>
                        <!-- Footer Links end -->
                    </div>
                    <div class="col-lg-4">
                        <!-- Footer Newsletter Form Start -->
                        <div class="footer-newsletter-form">
                            <h3>Subscribe our newsletter</h3>
                            <form action="#" id="newslettersForm" method="POST">
                                @csrf
                                <div class="form-group">
                                    <input class="form-control" name="mail" placeholder="Enter your email" required
                                        type="email" />
                                    <button class="btn-highlighted" type="submit">subscribe</button>
                                </div>
                            </form>
                        </div>
                        <!-- Footer Newsletter Form End -->
                        <!-- Footer Social Link Start -->
                        <div class="footer-social-links">
                            <ul>
                                <li><a href="https://www.instagram.com/jabulani_group_hardware?igsh=MWJrb2d1d3J6aXJ2ZQ%3D%3D&utm_source=qr"
                                        target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
                                <li><a href="https://www.facebook.com/share/18Ca1HgEJG/?mibextid=wwXIfr"
                                        target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
                                <li><a href="https://youtube.com/@jabulanigroup?si=GgvEK0U6KCtqioxy" target="_blank"><i
                                            class="fa-brands fa-youtube"></i></a></li>
                                <li><a href="https://www.tiktok.com/@jabulani.logistic" target="_blank"><i
                                            class="fa-brands fa-tiktok"></i></a></li>
                            </ul>
                        </div>
                        <!-- Footer Social Link End -->
                    </div>
                </div>
                <!-- Footer Copyright Section Start -->
                <div class="footer-copyright">
                    <div class="row align-items-center">
                        <div class="col-lg-12">
                            <div class="footer-copyright-text">
                                <p>Copyright &copy; {{ date('Y') }} Jabulani Group of Companies. All Rights Reserved.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Footer Copyright Section End -->
            </div>
            <!-- Footer Main End -->
        </div>
    </footer>
    <!-- Footer End -->

    <!-- WhatsApp Float Button -->
    <a class="whatsapp-float" href="https://wa.me/27660684585" target="_blank" title="Chat with us on WhatsApp">
        <img alt="WhatsApp" src="https://cdn-icons-png.flaticon.com/512/124/124034.png">
    </a>

    <!-- Jquery Library File -->
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <!-- Bootstrap js file -->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <!-- Validator js file -->
    <script src="{{ asset('js/validator.min.js') }}"></script>
    <!-- SlickNav js file -->
    <script src="{{ asset('js/jquery.slicknav.js') }}"></script>
    <!-- Swiper js file -->
    <script src="{{ asset('js/swiper-bundle.min.js') }}"></script>
    <!-- Counter js file -->
    <script src="{{ asset('js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('js/jquery.counterup.min.js') }}"></script>
    <!-- Magnific Popup core JS file -->
    <script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
    <!-- GSAP -->
    <script src="{{ asset('js/gsap.min.js') }}"></script>
    <script src="{{ asset('js/ScrollTrigger.min.js') }}"></script>
    <script src="{{ asset('js/SplitText.js') }}"></script>
    <!-- Smooth Scroll -->
    <script src="{{ asset('js/SmoothScroll.js') }}"></script>
    <!-- Text Effect -->
    <script src="{{ asset('js/typed.js') }}"></script>
    <!-- WOW Animation -->
    <script src="{{ asset('js/wow.js') }}"></script>
    <!-- ImagesLoaded (CDN) -->
    <script src="https://unpkg.com/imagesloaded@5/imagesloaded.pkgd.min.js"></script>
    <!-- Main Custom js file -->
    <script src="{{ asset('js/function.js') }}"></script>

    <script>
        // Auto-load cart badge count
        document.addEventListener('DOMContentLoaded', function () {
            fetch('{{ route("cart.count") }}')
                .then(r => r.json())
                .then(data => {
                    let badge = document.getElementById('cart-badge');
                    if (badge) {
                        badge.textContent = data.cart_count;
                        badge.style.display = data.cart_count > 0 ? 'inline-block' : 'none';
                    }
                }).catch(() => { });
        });
    </script>

    @stack('js')
</body>

</html>