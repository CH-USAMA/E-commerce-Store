@extends('layouts.frontend')

@section('title', 'About Us - Jabulani Group of Companies')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">About <span>us</span></h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">about us</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Scrolling Ticker Section Start -->
    <div class="our-scrolling-ticker subpages-scrolling-ticker">
        <div class="scrolling-ticker-box">
            <div class="scrolling-content">
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Blocks SABS</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Aluminium & Glass</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Doors & Windows</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Tiles</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Hardware Tools</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Roofing Material</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Boards</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Plumbing Material</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Electrical Appliances</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Wall & Ceiling</span>
            </div>
            <div class="scrolling-content">
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Blocks SABS</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Aluminium & Glass</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Doors & Windows</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Tiles</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Hardware Tools</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Roofing Material</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Boards</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Plumbing Material</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Electrical Appliances</span>
                <span><img src="{{ asset('images/asterisk-icon.svg') }}" alt="">Wall & Ceiling</span>
            </div>
        </div>
    </div>
    <!-- Scrolling Ticker Section End -->

    <section class="section slider-section swiper-gallery-wrapper">
        <div class="container slider-column">
            <div class="swiper swiper-gallery">
                <div class="swiper-wrapper">
                    <div class="swiper-slide"><img src="{{ asset('images/JABULANI_Fleet.webp') }}" alt="Gallery Slide">
                    </div>
                    <div class="swiper-slide"><img src="{{ asset('images/meeting.webp') }}" alt="Gallery Slide"></div>
                    <div class="swiper-slide"><img src="{{ asset('images/window_pro.webp') }}" alt="Gallery Slide"></div>
                    <div class="swiper-slide"><img src="{{ asset('images/big_machine.webp') }}" alt="Gallery Slide"></div>
                    <div class="swiper-slide"><img src="{{ asset('images/quarry_truck.webp') }}" alt="Gallery Slide"></div>
                </div>
                <div class="swiper-gallery-pagination"></div>
                <div class="swiper-gallery-prev"></div>
                <div class="swiper-gallery-next"></div>
            </div>
        </div>
    </section>

    <!-- About Jabulani Section Start -->
    <div class="about-agency py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="about-agency-content">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">about us</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque"><span>Jabulani Group</span> of companies -
                                Your Reliable Hardware Partner Since 2002 – Quality You Can Afford</h2>
                        </div>
                        <div class="section-btn wow fadeInUp" data-wow-delay="0.25s">
                            <a href="{{ route('contact') }}" class="btn-default">Contact Us</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="about-agency-list">
                        <div class="about-agency-item wow fadeInUp">
                            <div class="icon-box">
                                <img src="{{ asset('images/logo_yellow2.png') }}" alt="">
                            </div>
                            <div class="agency-item-content">
                                <h3>Supplying Quality & Affordability Since 2002</h3>
                                <p>For over two decades, <span style="color: #ffe507;">Jabulani Group of Companies</span>
                                    has been the trusted name in hardware and building materials, offering top-quality
                                    products at the lowest prices across the Eastern Cape. We source from the best suppliers
                                    and brands, ensuring our customers get the best value for their money. </p>
                            </div>
                        </div>

                        <div class="about-agency-item wow fadeInUp" data-wow-delay="0.2s">
                            <div class="icon-box">
                                <img src="{{ asset('images/concrete (1).png') }}" alt="">
                            </div>
                            <div class="agency-item-content">
                                <h3>The Biggest Supplier of Blocks in Transkei</h3>
                                <p>As the leading supplier of SABS-approved blocks, we produce 100,000 to 150,000 blocks
                                    daily and deliver 80,000 to 100,000 blocks with our 15 superlink Trucks across the
                                    Eastern Cape. Our high-quality blocks power over 50 major stores and building material
                                    giants, ensuring strong and durable construction.</p>
                            </div>
                        </div>

                        <div class="about-agency-item wow fadeInUp" data-wow-delay="0.6s">
                            <div class="icon-box">
                                <img src="{{ asset('images/window (1).png') }}" alt="">
                            </div>
                            <div class="agency-item-content">
                                <h3>Manufacturer of Concrete & Aluminum Products</h3>
                                <p><span style="color: #ffe507;">Jabulani Group of Companies</span> takes pride in in-house
                                    production of concrete products like lintels and pillars, along with custom aluminum
                                    doors and windows, ensuring superior quality and durability for every project. </p>
                            </div>
                        </div>

                        <div class="about-agency-item wow fadeInUp" data-wow-delay="0.4s">
                            <div class="icon-box">
                                <img src="{{ asset('images/free-trade.png') }}" alt="">
                            </div>
                            <div class="agency-item-content">
                                <h3>Expanding Our Reach with Global Imports</h3>
                                <p>We go beyond local sourcing by importing top-quality hardware and building materials from
                                    different foreign countries like China, offering customers even more affordable options
                                    while maintaining high standards.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About Us Section End -->

    <!-- Our Approach Start -->
    <div class="our-approach py-5">
        <div class="container">
            <div class="row align-items-center section-row">
                <div class="col-lg-6">
                    <div class="section-title">
                        <h3 class="wow fadeInUp">our approach</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">Building Strong <span>Foundations</span></h2>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="section-title-content wow fadeInUp" data-wow-delay="0.25s">
                        <p>At <span style="color: #ffe507;">Jabulani Group of Companies</span>, we combine experience,
                            reliability, and affordability to provide top-quality hardware and building materials. From
                            sourcing the best products to delivering them efficiently, we ensure that builders, contractors,
                            and homeowners get everything they need to create lasting structures.</p>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-4 col-md-6">
                    <div class="mission-vision-item wow fadeInUp">
                        <div class="mission-vision-image">
                            <div class="mission-vision-img">
                                <figure class="image-anime">
                                    <img src="{{ asset('images/MISSION.JPG') }}" alt="">
                                </figure>
                            </div>
                            <div class="icon-box">
                                <img src="{{ asset('images/icon-our-mission.svg') }}" alt="">
                            </div>
                        </div>
                        <div class="mission-vision-content">
                            <h3>our mission</h3>
                            <p><strong style="color: #FFE507;">Jabulani Group of Companies</strong> mission is to make
                                construction accessible to all, ensuring that everyone—regardless of financial status—can
                                build with confidence. We believe in building strong relationships with both our customers
                                and suppliers, fostering trust and long-term partnerships.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="mission-vision-item wow fadeInUp" data-wow-delay="0.2s">
                        <div class="mission-vision-image">
                            <div class="mission-vision-img">
                                <figure class="image-anime">
                                    <img src="{{ asset('images/CEO2.png') }}" alt="">
                                </figure>
                            </div>
                            <div class="icon-box">
                                <img src="{{ asset('images/icon-our-vision.svg') }}" alt="">
                            </div>
                        </div>
                        <div class="mission-vision-content">
                            <h3>our vision</h3>
                            <p>From the beginning, Our <strong style="color: #FFE507;">CEO Naeem Ahmad</strong> envisioned a
                                Company where every person, whether rich or poor, could afford the materials needed for
                                their homes and projects hence By sourcing globally and manufacturing locally, we continue
                                to offer the best quality at the lowest prices.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="mission-vision-item wow fadeInUp" data-wow-delay="0.4s">
                        <div class="mission-vision-image">
                            <div class="mission-vision-img">
                                <figure class="image-anime">
                                    <img src="{{ asset('images/Values2.JPG') }}" alt="">
                                </figure>
                            </div>
                            <div class="icon-box">
                                <img src="{{ asset('images/icon-our-value.svg') }}" alt="">
                            </div>
                        </div>
                        <div class="mission-vision-content">
                            <h3>our values</h3>
                            <p>
                            <ul class="text-white list-unstyled">
                                <li><strong style="color: #FFE507;">Integrity</strong> – Following our CEO's vision, we
                                    ensure fair pricing for all.</li>
                                <li><strong style="color: #FFE507;">Quality</strong> – Supplying only best,from SABS block
                                    to custom aluminum products.</li>
                                <li><strong style="color: #FFE507;">Innovation</strong> – Constantly improving and sourcing
                                    the best materials globally.</li>
                                <li><strong style="color: #FFE507;">Customer-Centric</strong> – Creating a welcoming
                                    shopping experience.</li>
                            </ul>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Our Approach End -->

    <!-- Who We Are Start -->
    <div class="who-we-are py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="who-we-are-content">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">who we are</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">Experts in <span>Hardware & Building</span>
                                supplies</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.25s">We specialize in providing top-quality building
                                materials and hardware, ensuring durability, affordability, and efficiency for every
                                project. With a commitment to excellence, we support builders, contractors, and homeowners
                                by delivering trusted solutions.</p>
                        </div>

                        <div class="experts-rating-video">
                            <div class="experts-rating-video-image">
                                <div class="video-image">
                                    <a href="https://www.youtube.com/watch?v=eQKOShYBd4I" class="popup-video"
                                        data-cursor-text="Play">
                                        <figure class="image-anime">
                                            <img src="{{ asset('images/who_we.png') }}" alt="">
                                        </figure>
                                    </a>
                                </div>
                                <div class="video-play-button">
                                    <a href="https://www.youtube.com/watch?v=eQKOShYBd4I" class="popup-video"
                                        data-cursor-text="Play">
                                        <i class="fa-solid fa-play"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="who-we-are-client">
                                <div class="comapny-client-rating wow fadeInUp">
                                    <ul>
                                        <li>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                        </li>
                                    </ul>
                                    <p>( <span class="counter">40</span>+ Reviews)</p>
                                </div>
                                <div class="testimonial-review-image">
                                    <div class="satisfy-client-image"><img src="{{ asset('images/customer-2.JPG') }}"
                                            alt=""></div>
                                    <div class="satisfy-client-image"><img src="{{ asset('images/customer-1.png') }}"
                                            alt=""></div>
                                    <div class="satisfy-client-image"><img src="{{ asset('images/customer-4.JPG') }}"
                                            alt=""></div>
                                </div>
                                <div class="contact-now-btn wow fadeInUp" data-wow-delay="0.2s">
                                    <a href="{{ route('contact') }}" class="contact-btn">contact now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="experts-counters-list">
                        <div class="experts-counter-box expert-box-1">
                            <div class="experts-counter-item">
                                <div class="icon-box"><img src="{{ asset('images/icon-who-we-are-counter-1.svg') }}" alt="">
                                </div>
                                <div class="experts-counter-content">
                                    <h2><span class="counter">35</span>k+</h2>
                                    <p>Happy Customer Around</p>
                                </div>
                            </div>
                            <div class="experts-counter-item">
                                <div class="icon-box"><img src="{{ asset('images/icon-who-we-are-counter-3.svg') }}" alt="">
                                </div>
                                <div class="experts-counter-content">
                                    <h2><span class="counter">72</span>+</h2>
                                    <p>Retailers Using our products</p>
                                </div>
                            </div>
                        </div>
                        <div class="experts-counter-box expert-box-2">
                            <div class="experts-counter-item">
                                <div class="icon-box"><img src="{{ asset('images/icon-who-we-are-counter-2.svg') }}" alt="">
                                </div>
                                <div class="experts-counter-content">
                                    <h2><span class="counter">120</span>+</h2>
                                    <p>Trusted suppliers</p>
                                </div>
                            </div>
                            <div class="experts-counter-item">
                                <div class="icon-box"><img src="{{ asset('images/icon-who-we-are-counter-4.svg') }}" alt="">
                                </div>
                                <div class="experts-counter-content">
                                    <h2><span class="counter">5</span>k+</h2>
                                    <p>active users of our products</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Who We Are End -->

    <!-- Executive Partners Start -->
    <div class="executive-partners py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="executive-partners-box">
                        <div class="row align-items-center">
                            <div class="col-lg-5">
                                <div class="section-title">
                                    <h3 class="wow fadeInUp">Our Partners & Suppliers</h3>
                                    <h2 class="text-anime-style-2" data-cursor="-opaque">100+ <span>suppliers</span> &
                                        partners</h2>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="our-partners-list d-flex flex-wrap gap-4">
                                    <div class="company-logo wow fadeInUp"><img src="{{ asset('images/makita.png') }}"
                                            alt=""></div>
                                    <div class="company-logo wow fadeInUp" data-wow-delay="0.2s"><img
                                            src="{{ asset('images/Ingco_Logo.png') }}" alt=""></div>
                                    <div class="company-logo wow fadeInUp" data-wow-delay="0.4s"><img
                                            src="{{ asset('images/Flash.png') }}" alt=""></div>
                                    <div class="company-logo wow fadeInUp" data-wow-delay="0.6s"><img
                                            src="{{ asset('images/Eureka.png') }}" alt=""></div>
                                    <div class="company-logo wow fadeInUp" data-wow-delay="0.8s"><img
                                            src="{{ asset('images/Roofco.png') }}" alt=""></div>
                                    <div class="company-logo wow fadeInUp" data-wow-delay="1s"><img
                                            src="{{ asset('images/Afrisam.png') }}" alt=""></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Executive Partners End -->

    <!-- Our Features Start -->
    <div class="our-features py-5">
        <div class="container">
            <div class="row section-row align-items-center mb-5">
                <div class="col-lg-6">
                    <div class="section-title">
                        <h3 class="wow fadeInUp">Why Choose Us</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">Expertise in <span>Construction &
                                Building</span> Materials</h2>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="section-content-btn">
                        <div class="section-title-content wow fadeInUp" data-wow-delay="0.2s">
                            <p>With decades of experience, we understand the needs of builders, contractors, and homeowners.
                                Our commitment to quality and efficiency ensures you get the best materials for every
                                project.</p>
                        </div>
                        <div class="section-btn wow fadeInUp" data-wow-delay="0.4s">
                            <a href="{{ route('contact') }}" class="btn-default">learn more</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="digital-features-box">
                        <div class="digital-features-item features-item-1 wow fadeInUp">
                            <div class="digital-features-image">
                                <figure class="image-anime">
                                    <img src="{{ asset('images/yellow_fleet.webp') }}" alt="">
                                </figure>
                            </div>
                            <div class="why-choose-content">
                                <div class="why-choose-item active wow fadeInUp">
                                    <h3>Reliable Crush & Quarry Solutions</h3>
                                    <p>At Jabulani Quarry, we produce high-quality crushed stone using our own modern quarry
                                        and crushing plant, enabling us to supply both our in-house block manufacturing
                                        operations and customers at low prices & better quality across the Eastern Cape.</p>
                                </div>
                            </div>
                        </div>
                        <div class="digital-features-item features-item-2 wow fadeInUp" data-wow-delay="0.25s">
                            <div class="digital-features-image">
                                <figure class="image-anime">
                                    <img src="{{ asset('images/Delivery.png') }}" alt="">
                                </figure>
                            </div>
                            <div class="why-choose-content">
                                <div class="why-choose-item active wow fadeInUp">
                                    <h3>Reliable & Fast Delivery</h3>
                                    <p>We offer efficient delivery services for all hardware materials, ensuring your
                                        supplies reach your site on time, anywhere in the Eastern Cape.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Our Features End -->

    <!-- Our Team Section Start -->
    <div class="our-team py-5">
        <div class="container">
            <div class="row section-row align-items-center mb-5">
                <div class="col-lg-6">
                    <div class="section-title">
                        <h3 class="wow fadeInUp">Team Jabulani</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">A Dynamic <span>Team</span> of Hardware &
                            Building Experts.</h2>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="section-content-btn">
                        <div class="section-title-content wow fadeInUp" data-wow-delay="0.2s">
                            <p>Our team of 600+ employees across 8 different stores, 4 shop yards and main jabulani group
                                Blockyard drives innovation in the hardware and building materials industry.</p>
                        </div>
                        <div class="section-btn wow fadeInUp" data-wow-delay="0.4s">
                            <a href="{{ route('team') }}" class="btn-default">view all members</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($teamMembers as $member)
                    <div class="col-lg-3 col-md-6">
                        <div class="team-item wow fadeInUp">
                            <div class="team-image">
                                <figure class="image-anime">
                                    <img src="{{ Str::contains($member->image, 'images/') ? asset($member->image) : asset('storage/' . $member->image) }}"
                                        alt="{{ $member->name }}">
                                </figure>
                            </div>
                            <div class="team-body">
                                <div class="team-content">
                                    <h3>{{ $member->name }}</h3>
                                    <p>{{ $member->designation }}</p>
                                </div>
                                <div class="team-social-list">
                                    <p style="color: #FFE507;">@Jabulani Group</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Our Team Section End -->

    <!-- Our Testimonial Section Start -->
    <div class="our-testimonial py-5">
        <div class="container">
            <div class="row section-row align-items-center mb-5">
                <div class="col-lg-7">
                    <div class="section-title">
                        <h3 class="wow fadeInUp">testimonials</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">Read What Our <span>Customers</span> Share
                            About Us </h2>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="section-content-btn">
                        <div class="section-title-content wow fadeInUp" data-wow-delay="0.2s">
                            <p>Discover how builders, contractors, and businesses have successfully completed their projects
                                with our high-quality materials.</p>
                        </div>
                        <div class="section-btn wow fadeInUp" data-wow-delay="0.4s">
                            <a href="{{ route('testimonials') }}" class="btn-default">all testimonials</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row align-items-center">
                <div class="col-lg-4">
                    <div class="testimonial-review-box">
                        <div class="testimonial-review-header">
                            <h2><span class="counter">4.9</span></h2>
                            <div class="testimonial-rating">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <p>(40+ Reviews)</p>
                        </div>
                        <div class="testimonial-review-content wow fadeInUp">
                            <h3>Customer experiences that speak for themselves</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="testimonial-slider">
                        <div class="swiper">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="testimonial-rating">
                                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                                class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                                class="fa-solid fa-star"></i>
                                        </div>
                                        <div class="testimonial-content">
                                            <p><span style="color: #FFE507;">Jabulani Group of Companies</span> has been our
                                                go-to supplier for SABS-approved blocks and aluminium products. Their
                                                quality is unmatched.</p>
                                        </div>
                                        <div class="testimonial-body">
                                            <div class="author-content">
                                                <h3>Saleem Nazar</h3>
                                                <p>CEO Cingimiso Construction</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="testimonial-rating">
                                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                                class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                                class="fa-solid fa-star"></i>
                                        </div>
                                        <div class="testimonial-content">
                                            <p><span style="color: #FFE507;">Jabulani Group of Companies</span> has been my
                                                trusted supplier for all my hardware and building material needs. From
                                                cement and bricks to roofing and reinforcing products, they always have what
                                                I need at the best prices. Their service is fast, and the quality is
                                                top-notch. I wouldn’t go anywhere else!.</p>
                                        </div>
                                        <div class="testimonial-body">
                                            <div class="author-image">
                                                <figure class="image-anime">
                                                    <img src="{{ asset('images/satisfy-client-img-1.jpg') }}" alt="">
                                                </figure>
                                            </div>
                                            <div class="author-content">
                                                <h3>Aubrey Wilson</h3>
                                                <p>Sales Manager Zolo Constructions</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Key Benefits Start -->
            <div class="agency-benefits py-5">
                <div class="container">
                    <div class="row section-row align-items-center mb-5">
                        <div class="col-lg-7">
                            <div class="section-title">
                                <h3 class="wow fadeInUp">key benefits</h3>
                                <h2 class="text-anime-style-2" data-cursor="-opaque">Discover the <span>benefits</span> of
                                    choosing us today</h2>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="section-title-content wow fadeInUp" data-wow-delay="0.25s">
                                <p>Experience unmatched quality, competitive pricing, and reliable service that ensures your
                                    building projects succeed.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="benefits-steps-item wow fadeInUp">
                                <div class="benefits-steps-no">
                                    <h3>01</h3>
                                </div>
                                <div class="icon-box"><img src="{{ asset('images/affordable.png') }}" alt=""></div>
                                <div class="benefits-steps-content">
                                    <h3>Affordable Pricing</h3>
                                    <p>We offer the lowest prices on hardware and building materials without compromising on
                                        quality and service.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="benefits-steps-item wow fadeInUp" data-wow-delay="0.2s">
                                <div class="benefits-steps-no">
                                    <h3>02</h3>
                                </div>
                                <div class="icon-box"><img src="{{ asset('images/expertise.png') }}" alt=""></div>
                                <div class="benefits-steps-content">
                                    <h3>Industry Expertise</h3>
                                    <p>With over two decades of experience, we understand what builders and contractors need
                                        to complete projects efficiently.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="benefits-steps-item wow fadeInUp" data-wow-delay="0.4s">
                                <div class="benefits-steps-no">
                                    <h3>03</h3>
                                </div>
                                <div class="icon-box"><img src="{{ asset('images/reliability.png') }}" alt=""></div>
                                <div class="benefits-steps-content">
                                    <h3>Top Supply & Delivery</h3>
                                    <p>From SABS approved blocks to aluminium windows we provide everything with consistent
                                        deliveris across Transkei.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="benefits-steps-item wow fadeInUp" data-wow-delay="0.6s">
                                <div class="benefits-steps-no">
                                    <h3>04</h3>
                                </div>
                                <div class="icon-box"><img src="{{ asset('images/office.png') }}" alt=""></div>
                                <div class="benefits-steps-content">
                                    <h3>Customer Service</h3>
                                    <p>We prioritize customer satisfaction with personalized service and dedicated support
                                        for all your construction needs.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Key Benefits End -->
        </div>
    </div>
    <!-- Our Testimonial Section End -->

    <!-- Our FAQs Start -->
    <div class="our-faqs py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="faq-images">
                        <div class="faq-img-1"><img src="{{ asset('images/FAQImage2.png') }}" alt=""></div>
                        <div class="faq-img-2"><img src="{{ asset('images/FAQImage.webp') }}" alt=""></div>
                        <div class="faq-cta-box">
                            <a href="tel:27660684585"><img src="{{ asset('images/faq-cta-phone.svg') }}" alt="">(+27)
                                6606-84585</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="our-faq-section">
                        <div class="section-title">
                            <h2 class="text-anime-style-2" data-cursor="-opaque">Let us address your <span>questions</span>
                                today!</h2>
                        </div>
                        <div class="faq-accordion" id="faqaccordion">
                            <div class="accordion-item wow fadeInUp">
                                <h2 class="accordion-header" id="heading1">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                        What products do you offer?
                                    </button>
                                </h2>
                                <div id="collapse1" class="accordion-collapse collapse" aria-labelledby="heading1"
                                    data-bs-parent="#faqaccordion">
                                    <div class="accordion-body">
                                        <p>We provide a wide range of hardware and building materials, including
                                            SABS-approved blocks, lintels, pillars, custom aluminum doors and windows,
                                            fencing materials, cement, plumbing supplies, roofing, and more. We also import
                                            high-quality products to ensure the best prices and selection.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item wow fadeInUp" data-wow-delay="0.2s">
                                <h2 class="accordion-header" id="heading2">
                                    <button class="accordion-button " type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                        Can you supply bulk orders for contractors and businesses?
                                    </button>
                                </h2>
                                <div id="collapse2" class="accordion-collapse collapse show" aria-labelledby="heading2"
                                    data-bs-parent="#faqaccordion">
                                    <div class="accordion-body">
                                        <p>Absolutely! We manufacture and supply in bulk, ensuring competitive prices and
                                            reliable delivery for contractors, businesses, and retailers across the Eastern
                                            Cape.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item wow fadeInUp" data-wow-delay="0.4s">
                                <h2 class="accordion-header" id="heading3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                        How can I get a price quote?
                                    </button>
                                </h2>
                                <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="heading3"
                                    data-bs-parent="#faqaccordion">
                                    <div class="accordion-body">
                                        <p>You can visit one of our Stores in Mount Frere, Qumbu, Tsolo or Umtata, call us,
                                            or send an inquiry online with details about the materials you need on contact
                                            us form. Our team will provide a detailed price estimate based on your order.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item wow fadeInUp" data-wow-delay="0.6s">
                                <h2 class="accordion-header" id="heading4">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                                        Can I get custom-sized aluminum doors and windows?
                                    </button>
                                </h2>
                                <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="heading4"
                                    data-bs-parent="#faqaccordion">
                                    <div class="accordion-body">
                                        <p>Absolutely! We manufacture custom aluminum doors and windows to fit your specific
                                            requirements, ensuring quality, durability, and affordability.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Our FAQs End -->
@endsection