@extends('layouts.frontend')

@section('title', 'Select Checkout Method - Jabulani Group')

@section('content')
    <div class="relative min-h-[80vh] flex items-center justify-center px-4 py-20 overflow-hidden bg-dark">
        <!-- Abstract Background Effects -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-gold-400/5 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-gold-400/5 blur-[120px] rounded-full"></div>

        <div class="w-full max-w-5xl relative z-10">
            <div class="text-center mb-16">
                <div
                    class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-white/5 border border-white/10 mb-8 shadow-2xl">
                    <img src="{{ asset('images/logo_yellow2.png') }}" alt="Jabulani" class="h-10 w-auto">
                </div>
                <h1 class="text-4xl md:text-5xl font-black mb-6 tracking-tight text-white italic">Almost <span
                        class="gradient-text">There!</span></h1>
                <p class="text-dark-muted text-lg max-w-xl mx-auto font-medium">How would you like to finish your
                    order?</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- Guest Checkout -->
                <form action="/checkout/guest" method="POST" class="h-full">
                    @csrf
                    <button type="submit"
                        class="w-full h-full card-dark rounded-[2rem] p-8 flex flex-col items-center text-center border-white/5 hover:border-gold-400 group transition-all duration-500">
                        <div
                            class="w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center mb-6 group-hover:bg-gold-400 transition-all">
                            <i class="fas fa-bolt text-xl text-dark-muted group-hover:text-dark"></i>
                        </div>
                        <h3 class="text-lg font-black text-white mb-2 uppercase italic">Guest Checkout</h3>
                        <p class="text-xs text-dark-muted leading-relaxed">No account needed. Quick and easy for one-time
                            orders.</p>
                        <div class="mt-8 text-[10px] font-black uppercase tracking-widest text-gold-400">Continue as Guest
                            <i class="fas fa-arrow-right ml-1"></i>
                        </div>
                    </button>
                </form>

                <!-- Signup -->
                <a href="{{ route('register') }}"
                    class="card-dark rounded-[2rem] p-8 flex flex-col items-center text-center border-gold-400/20 bg-gradient-to-br from-[#1a1400] to-dark hover:border-gold-400 transition-all duration-500 group relative">
                    <div
                        class="w-16 h-16 rounded-2xl bg-gold-400 flex items-center justify-center mb-6 shadow-lg shadow-gold-400/20">
                        <i class="fas fa-user-plus text-xl text-dark"></i>
                    </div>
                    <h3 class="text-lg font-black text-white mb-2 uppercase italic">New Account</h3>
                    <p class="text-xs text-dark-muted leading-relaxed">Save your details for next time & track orders
                        easily.</p>
                    <div class="mt-8 text-[10px] font-black uppercase tracking-widest text-gold-400">Sign Up Now <i
                            class="fas fa-plus ml-1"></i></div>
                </a>

                <!-- Login -->
                <a href="{{ route('login') }}"
                    class="card-dark rounded-[2rem] p-8 flex flex-col items-center text-center border-white/5 hover:border-gold-400 group transition-all duration-500">
                    <div
                        class="w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center mb-6 group-hover:bg-gold-400 transition-all">
                        <i class="fas fa-lock text-lg text-dark-muted group-hover:text-dark"></i>
                    </div>
                    <h3 class="text-lg font-black text-white mb-2 uppercase italic">Returning User</h3>
                    <p class="text-xs text-dark-muted leading-relaxed">Sign in to your account to use your saved profile.
                    </p>
                    <div class="mt-8 text-[10px] font-black uppercase tracking-widest text-gold-400">Login to Account <i
                            class="fas fa-sign-in-alt ml-1"></i></div>
                </a>

                <!-- Google Login -->
                <a href="{{ route('auth.google') }}"
                    class="card-dark rounded-[2rem] p-8 flex flex-col items-center text-center border-white/5 hover:border-gold-400 group transition-all duration-500 bg-gradient-to-br from-[#0a0a0a] to-[#111]">
                    <div
                        class="w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center mb-6 group-hover:bg-white transition-all">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-7 h-7" alt="Google">
                    </div>
                    <h3 class="text-lg font-black text-white mb-2 uppercase italic">Google One-Tap</h3>
                    <p class="text-xs text-dark-muted leading-relaxed">Instantly login or sign up using your Google account.
                    </p>
                    <div class="mt-8 text-[10px] font-black uppercase tracking-widest text-gold-400">Continue with Google <i
                            class="fas fa-arrow-right ml-1"></i></div>
                </a>
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('cart') }}"
                    class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-dark-muted hover:text-gold-400 transition-all">
                    <i class="fas fa-chevron-left"></i> Return to Cart
                </a>
            </div>
        </div>
    </div>
@endsection