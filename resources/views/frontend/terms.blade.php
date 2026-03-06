@extends('layouts.frontend')

@section('title', 'Terms of Service - Jabulani Group')

@section('content')
    <!-- Page Header -->
    <div class="relative py-16 overflow-hidden bg-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-left">
            <h1 class="text-3xl lg:text-5xl font-black mb-2 tracking-tight italic text-white uppercase">Terms of <span
                    class="gradient-text">Service</span></h1>
            <nav
                class="flex justify-center sm:justify-start items-center gap-2 text-[10px] font-black uppercase tracking-widest text-dark-muted">
                <a href="{{ route('home') }}" class="hover:text-gold-400 transition">Home</a>
                <span class="w-1 h-1 rounded-full bg-gold-400/50"></span>
                <span class="text-gray-400">Terms of Service</span>
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
                            class="text-gold-400">1.</span> Agreement to Terms</h2>
                    <p class="text-gray-400 leading-relaxed mb-8">By accessing or using the Jabulani Group website, you
                        agree to be bound by these Terms of Service. If you disagree with any part of the terms, then you
                        may not access the service.</p>

                    <h2 class="text-2xl font-black text-white italic tracking-tight uppercase mb-6"><span
                            class="text-gold-400">2.</span> Accounts</h2>
                    <p class="text-gray-400 leading-relaxed mb-8">When you create an account with us, you must provide
                        information that is accurate, complete, and current at all times. Failure to do so constitutes a
                        breach of the Terms, which may result in immediate termination of your account on our Service. You
                        are responsible for safeguarding the password that you use to access the Service.</p>

                    <h2 class="text-2xl font-black text-white italic tracking-tight uppercase mb-6"><span
                            class="text-gold-400">3.</span> Purchases</h2>
                    <p class="text-gray-400 leading-relaxed mb-8">If you wish to purchase any product or service made
                        available through the Service, you may be asked to supply certain information relevant to your
                        Purchase including, without limitation, your credit card number, the expiration date of your credit
                        card, and your billing/shipping address. You represent and warrant that you have the legal right to
                        use any credit card(s) or other payment method(s) in connection with any Purchase.</p>

                    <h2 class="text-2xl font-black text-white italic tracking-tight uppercase mb-6"><span
                            class="text-gold-400">4.</span> Intellectual Property</h2>
                    <p class="text-gray-400 leading-relaxed mb-8">The Service and its original content, features, and
                        functionality are and will remain the exclusive property of Jabulani Group and its licensors. Our
                        trademarks and trade dress may not be used in connection with any product or service without the
                        prior written consent of Jabulani Group.</p>

                    <h2 class="text-2xl font-black text-white italic tracking-tight uppercase mb-6"><span
                            class="text-gold-400">5.</span> Links To Other Web Sites</h2>
                    <p class="text-gray-400 leading-relaxed mb-8">Our Service may contain links to third-party web sites or
                        services that are not owned or controlled by Jabulani Group. Jabulani Group has no control over, and
                        assumes no responsibility for, the content, privacy policies, or practices of any third party web
                        sites or services.</p>

                    <h2 class="text-2xl font-black text-white italic tracking-tight uppercase mb-6"><span
                            class="text-gold-400">6.</span> Limitation Of Liability</h2>
                    <p class="text-gray-400 leading-relaxed mb-8">In no event shall Jabulani Group, nor its directors,
                        employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental,
                        special, consequential or punitive damages, including without limitation, loss of profits, data,
                        use, goodwill, or other intangible losses.</p>

                    <h2 class="text-2xl font-black text-white italic tracking-tight uppercase mb-6"><span
                            class="text-gold-400">7.</span> Governing Law</h2>
                    <p class="text-gray-400 leading-relaxed mb-8">These Terms shall be governed and construed in accordance
                        with the laws of South Africa, without regard to its conflict of law provisions.</p>

                    <h2 class="text-2xl font-black text-white italic tracking-tight uppercase mb-6"><span
                            class="text-gold-400">8.</span> Changes</h2>
                    <p class="text-gray-400 leading-relaxed mb-8">We reserve the right, at our sole discretion, to modify or
                        replace these Terms at any time. If a revision is material, we will try to provide at least 30 days'
                        notice prior to any new terms taking effect. What constitutes a material change will be determined
                        at our sole discretion.</p>

                    <h2 class="text-2xl font-black text-white italic tracking-tight uppercase mb-6"><span
                            class="text-gold-400">9.</span> Contact Us</h2>
                    <p class="text-gray-400 leading-relaxed">If you have any questions about these Terms, please contact us
                        at: <br>
                        <strong class="text-gold-400">Email: info@jabulanigroupofcompanies.co.za</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection