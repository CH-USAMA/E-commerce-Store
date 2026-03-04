@extends('layouts.frontend')

@section('title', 'Contact Us - Jabulani Group of Companies')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">Contact <span>us</span></h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">contact us</li>
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

    <!-- Page Contact Us Start -->
    <div class="page-contact-us py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="contact-information">
                        <div class="section-title">
                            <h2 class="text-anime-style-2">Get in <span>touch</span> with us</h2>
                            <p class="wow fadeInUp">We’re here to help!...</p>
                        </div>
                        <div class="contact-info-box">
                            <div class="info-box-1 wow fadeInUp">
                                <div class="contact-info-item">
                                    <div class="icon-box">
                                        <img src="{{ asset('images/icon-phone.svg') }}" alt="">
                                    </div>
                                    <div class="contact-item-content">
                                        <h3>phone number</h3>
                                        <p>0660684585</p>
                                    </div>
                                </div>
                                <div class="contact-info-item">
                                    <div class="icon-box">
                                        <img src="{{ asset('images/icon-mail.svg') }}" alt="">
                                    </div>
                                    <div class="contact-item-content">
                                        <h3>email address</h3>
                                        <p>jabulanigroup2002@gmail.com</p>
                                    </div>
                                </div>
                            </div>
                            <div class="info-box-2 wow fadeInUp" data-wow-delay="0.25s">
                                <div class="contact-info-item">
                                    <div class="icon-box">
                                        <img src="{{ asset('images/icon-location.svg') }}" alt="">
                                    </div>
                                    <div class="contact-item-content">
                                        <h3>Address</h3>
                                        <p>54 N2 Mount Frere, Eastern Cape, South Africa</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="contact-us-form">
                        <form id="contactForm" action="{{ route('contact.submit') }}" method="POST" class="wow fadeInUp"
                            data-wow-delay="0.25s">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-6 mb-4">
                                    <input type="text" name="fname" class="form-control" placeholder="First Name" required>
                                </div>
                                <div class="form-group col-md-6 mb-4">
                                    <input type="text" name="lname" class="form-control" placeholder="Last Name" required>
                                </div>
                                <div class="form-group col-md-12 mb-4">
                                    <input type="text" name="phone" class="form-control" placeholder="Phone No" required>
                                </div>
                                <div class="form-group col-md-12 mb-4">
                                    <input type="email" name="email" class="form-control" placeholder="E-mail" required>
                                </div>
                                <div class="form-group col-md-12 mb-5">
                                    <textarea name="message" class="form-control" rows="4" placeholder="Message"></textarea>
                                </div>
                                <div class="col-lg-12">
                                    <div class="contact-form-btn">
                                        <button type="submit" class="btn-highlighted">submit message</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Contact Us End -->

    <!-- Google Map Start -->
    <div class="google-map py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="google-map-iframe">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3423.3397266525467!2d28.989990975380184!3d-30.90512687450158!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1ef55dddd5017edd%3A0x1a1f0b57b2a10a25!2sJabulani%20Build%20%26Tile%20Mount%20frere!5e0!3m2!1sen!2sza!4v1739871166340!5m2!1sen!2sza"
                            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Google Map End -->

@endsection