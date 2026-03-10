@extends('layouts.frontend')

@section('title', 'Contact Us - Jabulani Group')

@section('content')

    <!-- Page Header -->
    <div class="relative pt-10 pb-24 overflow-hidden bg-dark">
        <div class="absolute inset-0 opacity-10">
            <img src="{{ asset('images/JB_About_Hero.webp') }}" class="w-full h-full object-cover" alt="Contact Background">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-4xl lg:text-6xl font-black mb-4">Get in <span class="gradient-text">Touch</span></h1>
            <nav class="flex justify-center items-center gap-2 text-xs font-bold uppercase tracking-widest text-dark-muted">
                <a href="{{ route('home') }}" class="hover:text-gold-400 transition">Home</a>
                <span class="w-1 h-1 rounded-full bg-gold-400"></span>
                <span class="text-gray-400">Contact Us</span>
            </nav>
        </div>
    </div>

    <!-- Contact Info & Form -->
    <section class="py-24 bg-[#0a0a0a]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">

                <!-- Info Section -->
                <div class="space-y-12">
                    <div>
                        <h2 class="text-3xl font-black text-white mb-6 leading-tight">We're here to help you build <span
                                class="text-gold-400">your dreams.</span></h2>
                        <p class="text-gray-400 text-lg font-light leading-relaxed">Whether you're a professional contractor
                            or a DIY enthusiast, our team of experts is ready to assist you with the best building materials
                            and hardware solutions in South Africa.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <!-- Phone -->
                        <div class="flex gap-5">
                            <div
                                class="w-12 h-12 rounded-xl bg-gold-400/10 border border-gold-400/20 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-phone text-gold-400"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold mb-1 uppercase text-xs tracking-widest">Phone Number</h4>
                                <p class="text-gray-400 text-sm font-semibold">+27 6606 84585</p>
                                <p class="text-gray-500 text-xs mt-1">Available 8am - 5pm</p>
                            </div>
                        </div>
                        <!-- Email -->
                        <div class="flex gap-5">
                            <div
                                class="w-12 h-12 rounded-xl bg-gold-400/10 border border-gold-400/20 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope text-gold-400"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold mb-1 uppercase text-xs tracking-widest">Email Address</h4>
                                <p class="text-gray-400 text-sm font-semibold">info@jabulanigroupofcompanies.co.za</p>
                                <p class="text-gray-500 text-xs mt-1">Response within 24h</p>
                            </div>
                        </div>
                        <!-- Location -->
                        <div class="flex gap-5 col-span-1 sm:col-span-2">
                            <div
                                class="w-12 h-12 rounded-xl bg-gold-400/10 border border-gold-400/20 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-gold-400"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold mb-1 uppercase text-xs tracking-widest">Main Office</h4>
                                <p class="text-gray-400 text-sm font-semibold">54 N2 Mount Frere, Eastern Cape, South Africa
                                </p>
                                <p class="text-gray-500 text-xs mt-1">Visit any of our 8 branches across Transkei</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 border-t border-dark-border">
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-gold-400 mb-6">Join Our Community</p>
                        <div class="flex gap-4">
                            <a href="https://www.facebook.com/share/18Ca1HgEJG/?mibextid=wwXIfr" target="_blank"
                                class="w-12 h-12 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:bg-gold-400 hover:text-dark hover:border-gold-400 transition duration-300">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://www.instagram.com/jabulani_group_hardware?igsh=MWJrb2d1d3J6aXJ2ZQ%3D%3D&utm_source=qr"
                                target="_blank"
                                class="w-12 h-12 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:bg-gold-400 hover:text-dark hover:border-gold-400 transition duration-300">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://youtube.com/@jabulanigroup?si=GgvEK0U6KCtqioxy" target="_blank"
                                class="w-12 h-12 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:bg-gold-400 hover:text-dark hover:border-gold-400 transition duration-300">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <a href="https://www.tiktok.com/@jabulani.logistic" target="_blank"
                                class="w-12 h-12 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:bg-gold-400 hover:text-dark hover:border-gold-400 transition duration-300">
                                <i class="fab fa-tiktok"></i>
                            </a>
                            <a href="https://www.linkedin.com/company/jabulani-group-of-companies/" target="_blank"
                                class="w-12 h-12 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:bg-gold-400 hover:text-dark hover:border-gold-400 transition duration-300">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Form Section -->
                <div class="relative">
                    <div class="absolute -inset-4 bg-gold-400/5 blur-3xl opacity-50 rounded-full"></div>
                    <form action="/contact" method="POST"
                        class="relative card-dark p-8 md:p-10 rounded-3xl border border-dark-border shadow-2xl">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-dark-muted ml-1">First
                                    Name</label>
                                <input type="text" name="fname" required placeholder="John"
                                    class="w-full bg-[#050505] border border-dark-border rounded-xl px-5 py-4 text-sm text-gray-200 focus:outline-none focus:border-gold-400 transition placeholder-gray-700">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-dark-muted ml-1">Last
                                    Name</label>
                                <input type="text" name="lname" required placeholder="Doe"
                                    class="w-full bg-[#050505] border border-dark-border rounded-xl px-5 py-4 text-sm text-gray-200 focus:outline-none focus:border-gold-400 transition placeholder-gray-700">
                            </div>
                            <div class="sm:col-span-2 space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-dark-muted ml-1">Email
                                    Address</label>
                                <input type="email" name="email" required placeholder="john@example.com"
                                    class="w-full bg-[#050505] border border-dark-border rounded-xl px-5 py-4 text-sm text-gray-200 focus:outline-none focus:border-gold-400 transition placeholder-gray-700">
                            </div>
                            <div class="sm:col-span-2 space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-dark-muted ml-1">Phone
                                    Number</label>
                                <input type="text" name="phone" required placeholder="+27 ..."
                                    class="w-full bg-[#050505] border border-dark-border rounded-xl px-5 py-4 text-sm text-gray-200 focus:outline-none focus:border-gold-400 transition placeholder-gray-700">
                            </div>
                            <div class="sm:col-span-2 space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-dark-muted ml-1">Your
                                    Message</label>
                                <textarea name="message" rows="4" placeholder="How can we help you?"
                                    class="w-full bg-[#050505] border border-dark-border rounded-xl px-5 py-4 text-sm text-gray-200 focus:outline-none focus:border-gold-400 transition placeholder-gray-700 resize-none"></textarea>
                            </div>
                        </div>
                        <div class="mt-8">
                            <button type="submit"
                                class="btn-gold w-full py-4 tracking-widest uppercase text-sm font-black shadow-[0_10px_30px_rgba(245,197,24,0.1)]">
                                Send Message <i class="fas fa-paper-plane ml-2"></i>
                            </button>
                        </div>
                        <p class="text-[10px] text-center text-dark-muted mt-4 uppercase tracking-widest">Typically responds
                            within 24 hours</p>
                    </form>
                </div>

            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="relative h-[450px] w-full bg-[#050505]">
        <div class="absolute inset-0 grayscale invert opacity-30 hover:opacity-50 transition-opacity duration-500">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3423.3397266525467!2d28.989990975380184!3d-30.90512687450158!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1ef55dddd5017edd%3A0x1a1f0b57b2a10a25!2sJabulani%20Build%20%26Tile%20Mount%20frere!5e0!3m2!1sen!2sza!4v1739871166340!5m2!1sen!2sza"
                class="w-full h-full border-0" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="absolute inset-0 pointer-events-none shadow-[inset_0_0_100px_rgba(0,0,0,0.5)]"></div>
        <div
            class="absolute bottom-10 left-1/2 -translate-x-1/2 bg-dark/90 backdrop-blur-md border border-dark-border px-6 py-3 rounded-full text-xs font-bold text-white flex items-center gap-3 shadow-2xl pointer-events-auto">
            <i class="fas fa-map-marker-alt text-gold-400"></i> Open in Google Maps
        </div>
    </section>

@endsection