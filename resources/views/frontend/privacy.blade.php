@extends('layouts.frontend')

@section('title', 'Privacy Policy - Jabulani Group')

@section('content')
    <!-- Page Header -->
    <div class="relative py-16 overflow-hidden bg-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-left">
            <h1 class="text-3xl lg:text-5xl font-black mb-2 tracking-tight italic text-white uppercase">Privacy <span
                    class="gradient-text">Policy</span></h1>
            <nav
                class="flex justify-center sm:justify-start items-center gap-2 text-[10px] font-black uppercase tracking-widest text-dark-muted">
                <a href="{{ route('home') }}" class="hover:text-gold-400 transition">Home</a>
                <span class="w-1 h-1 rounded-full bg-gold-400/50"></span>
                <span class="text-gray-400">Privacy Policy</span>
            </nav>
        </div>
    </div>

    <div class="bg-[#050505] min-h-screen py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="card-dark rounded-[3rem] p-10 md:p-16 border-white/5 bg-gradient-to-b from-white/[0.02] to-transparent shadow-2xl">
                <div class="prose prose-invert max-w-none">
                    <p class="text-dark-muted text-sm italic mb-12">Last Updated: March 2026</p>

                    <h2 class="text-2xl font-black text-white italic tracking-tight uppercase mb-6"><span
                            class="text-gold-400">1.</span> Introduction</h2>
                    <p class="text-gray-400 leading-relaxed mb-8">Welcome to Jabulani Group. We respect your privacy and are
                        committed to protecting your personal data. This privacy policy will inform you as to how we look
                        after your personal data when you visit our website and tell you about your privacy rights and how
                        the law protects you.</p>

                    <h2 class="text-2xl font-black text-white italic tracking-tight uppercase mb-6"><span
                            class="text-gold-400">2.</span> The Data We Collect</h2>
                    <p class="text-gray-400 leading-relaxed mb-4">We may collect, use, store and transfer different kinds of
                        personal data about you which we have grouped together as follows:</p>
                    <ul class="list-disc pl-6 text-gray-400 space-y-2 mb-8">
                        <li><strong>Identity Data</strong> includes first name, last name, and username.</li>
                        <li><strong>Contact Data</strong> includes email address, telephone numbers, and delivery addresses.
                        </li>
                        <li><strong>Financial Data</strong> includes payment card details (processed securely via our
                            payment gateways).</li>
                        <li><strong>Transaction Data</strong> includes details about payments to and from you and other
                            details of products you have purchased from us.</li>
                        <li><strong>Technical Data</strong> includes internet protocol (IP) address, your login data,
                            browser type and version.</li>
                    </ul>

                    <h2 class="text-2xl font-black text-white italic tracking-tight uppercase mb-6"><span
                            class="text-gold-400">3.</span> How We Use Your Data</h2>
                    <p class="text-gray-400 leading-relaxed mb-8">We will only use your personal data when the law allows us
                        to. Most commonly, we will use your personal data to perform the contract we are about to enter into
                        or have entered into with you (e.g., fulfilling an order), where it is necessary for our legitimate
                        interests, or where we need to comply with a legal obligation.</p>

                    <h2 class="text-2xl font-black text-white italic tracking-tight uppercase mb-6"><span
                            class="text-gold-400">4.</span> Google Integration</h2>
                    <p class="text-gray-400 leading-relaxed mb-8">If you choose to login via Google, we collect your Gmail
                        address, name, and profile picture from Google. This data is used solely to create and manage your
                        account on our platform and to provide a seamless login experience. We do not share your Google data
                        with third parties except as necessary to provide our services.</p>

                    <h2 class="text-2xl font-black text-white italic tracking-tight uppercase mb-6"><span
                            class="text-gold-400">5.</span> Data Security</h2>
                    <p class="text-gray-400 leading-relaxed mb-8">We have put in place appropriate security measures to
                        prevent your personal data from being accidentally lost, used or accessed in an unauthorised way,
                        altered or disclosed.</p>

                    <h2 class="text-2xl font-black text-white italic tracking-tight uppercase mb-6"><span
                            class="text-gold-400">6.</span> Your Legal Rights</h2>
                    <p class="text-gray-400 leading-relaxed mb-8">Under certain circumstances, you have rights under data
                        protection laws in relation to your personal data, including the right to request access,
                        correction, erasure, or restriction of processing of your personal data.</p>

                    <h2 class="text-2xl font-black text-white italic tracking-tight uppercase mb-6"><span
                            class="text-gold-400">7.</span> Contact Us</h2>
                    <p class="text-gray-400 leading-relaxed">If you have any questions about this privacy policy or our
                        privacy practices, please contact us at: <br>
                        <strong class="text-gold-400">Email: info@jabulanigroupofcompanies.co.za</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection