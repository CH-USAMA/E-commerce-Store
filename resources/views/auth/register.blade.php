@extends('layouts.frontend')

@section('title', 'Register - Jabulani Group')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-2xl">
            <div class="text-center mb-8">
                <img src="{{ asset('images/logo_yellow2.png') }}" alt="Jabulani" class="h-12 w-auto mx-auto mb-5">
                <h1 class="text-2xl font-black mb-1">Create Your <span class="gradient-text">Account</span></h1>
                <p class="text-dark-muted text-sm">Already have an account? <a href="{{ route('login') }}"
                        class="text-gold-400 hover:underline">Sign in</a></p>
            </div>

            <div class="card-dark rounded-2xl p-6 sm:p-8">
                <form action="/register" method="POST" x-data="{ billingSame: true }">
                    @csrf

                    <!-- Personal Info -->
                    <h3 class="text-xs font-bold uppercase tracking-widest text-dark-muted mb-4">Personal Information</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="text-xs text-gray-400 mb-1 block">Full Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full bg-dark border @error('name') border-red-500 @else border-dark-border @enderror rounded-xl px-4 py-3 text-sm text-gray-200 placeholder-dark-muted focus:outline-none focus:border-gold-400 transition">
                            @error('name') <p class="text-[10px] text-red-400 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs text-gray-400 mb-1 block">Email Address *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full bg-dark border @error('email') border-red-500 @else border-dark-border @enderror rounded-xl px-4 py-3 text-sm text-gray-200 placeholder-dark-muted focus:outline-none focus:border-gold-400 transition">
                            @error('email') <p class="text-[10px] text-red-400 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs text-gray-400 mb-1 block">Password *</label>
                            <input type="password" name="password" required
                                class="w-full bg-dark border @error('password') border-red-500 @else border-dark-border @enderror rounded-xl px-4 py-3 text-sm text-gray-200 placeholder-dark-muted focus:outline-none focus:border-gold-400 transition">
                            @error('password') <p class="text-[10px] text-red-400 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs text-gray-400 mb-1 block">Confirm Password *</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full bg-dark border border-dark-border rounded-xl px-4 py-3 text-sm text-gray-200 placeholder-dark-muted focus:outline-none focus:border-gold-400 transition">
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="border-t border-dark-border pt-6 mb-6">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-dark-muted mb-4">Shipping Address</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="text-xs text-gray-400 mb-1 block">Street Address *</label>
                                <input type="text" name="shipping_address_line_1" value="{{ old('shipping_address_line_1') }}" required
                                    class="w-full bg-dark border @error('shipping_address_line_1') border-red-500 @else border-dark-border @enderror rounded-xl px-4 py-3 text-sm text-gray-200 placeholder-dark-muted focus:outline-none focus:border-gold-400 transition">
                                @error('shipping_address_line_1') <p class="text-[10px] text-red-400 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label class="text-xs text-gray-400 mb-1 block">Apartment, suite, etc. (optional)</label>
                                <input type="text" name="shipping_address_line_2" value="{{ old('shipping_address_line_2') }}"
                                    class="w-full bg-dark border border-dark-border rounded-xl px-4 py-3 text-sm text-gray-200 placeholder-dark-muted focus:outline-none focus:border-gold-400 transition">
                            </div>
                            <div>
                                <label class="text-xs text-gray-400 mb-1 block">City *</label>
                                <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" required
                                    class="w-full bg-dark border @error('shipping_city') border-red-500 @else border-dark-border @enderror rounded-xl px-4 py-3 text-sm text-gray-200 focus:outline-none focus:border-gold-400 transition">
                                @error('shipping_city') <p class="text-[10px] text-red-400 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs text-gray-400 mb-1 block">Province *</label>
                                <input type="text" name="shipping_province" value="{{ old('shipping_province') }}" required
                                    class="w-full bg-dark border @error('shipping_province') border-red-500 @else border-dark-border @enderror rounded-xl px-4 py-3 text-sm text-gray-200 focus:outline-none focus:border-gold-400 transition">
                                @error('shipping_province') <p class="text-[10px] text-red-400 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs text-gray-400 mb-1 block">Postal Code *</label>
                                <input type="text" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}" required
                                    class="w-full bg-dark border @error('shipping_postal_code') border-red-500 @else border-dark-border @enderror rounded-xl px-4 py-3 text-sm text-gray-200 focus:outline-none focus:border-gold-400 transition">
                                @error('shipping_postal_code') <p class="text-[10px] text-red-400 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Billing Address -->
                    <div class="border-t border-dark-border pt-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-dark-muted">Billing Address</h3>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" x-model="billingSame" name="billing_same_as_shipping" value="1"
                                    class="w-4 h-4 rounded accent-gold-400">
                                <span class="text-xs text-gray-400">Same as shipping</span>
                            </label>
                        </div>
                        <div x-show="!billingSame" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="text-xs text-gray-400 mb-1 block">Street Address</label>
                                <input type="text" name="billing_address_line_1" value="{{ old('billing_address_line_1') }}"
                                    class="w-full bg-dark border border-dark-border rounded-xl px-4 py-3 text-sm text-gray-200 focus:outline-none focus:border-gold-400 transition">
                                @error('billing_address_line_1') <p class="text-[10px] text-red-400 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs text-gray-400 mb-1 block">City</label>
                                <input type="text" name="billing_city" value="{{ old('billing_city') }}"
                                    class="w-full bg-dark border border-dark-border rounded-xl px-4 py-3 text-sm text-gray-200 focus:outline-none focus:border-gold-400 transition">
                                @error('billing_city') <p class="text-[10px] text-red-400 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs text-gray-400 mb-1 block">Province</label>
                                <input type="text" name="billing_province" value="{{ old('billing_province') }}"
                                    class="w-full bg-dark border border-dark-border rounded-xl px-4 py-3 text-sm text-gray-200 focus:outline-none focus:border-gold-400 transition">
                                @error('billing_province') <p class="text-[10px] text-red-400 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs text-gray-400 mb-1 block">Postal Code</label>
                                <input type="text" name="billing_postal_code" value="{{ old('billing_postal_code') }}"
                                    class="w-full bg-dark border border-dark-border rounded-xl px-4 py-3 text-sm text-gray-200 focus:outline-none focus:border-gold-400 transition">
                                @error('billing_postal_code') <p class="text-[10px] text-red-400 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="btn-gold w-full flex items-center justify-center gap-2 py-3.5 font-bold rounded-xl text-sm">
                        <i class="fas fa-user-plus"></i> Create Account & Continue
                    </button>

                    <div class="relative my-6 px-10">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-white/5"></div>
                        </div>
                        <div class="relative flex justify-center text-xs uppercase tracking-widest text-dark-muted">
                            <span class="bg-dark px-2">Or continue with</span>
                        </div>
                    </div>

                    <a href="{{ route('auth.google') }}"
                        class="w-full py-3.5 rounded-xl text-sm font-bold flex items-center justify-center gap-3 border border-white/5 bg-black/40 hover:bg-white/5 transition-all duration-300 text-white shadow-xl">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5" alt="Google">
                        <span>Sign up with Google</span>
                    </a>

                    <p class="mt-8 text-center text-[10px] text-dark-muted font-bold tracking-wide leading-relaxed uppercase">
                        By creating an account or signing in, you agree to our <br>
                        <a href="{{ route('terms') }}" class="text-gold-400 hover:underline">Terms of Service</a> & 
                        <a href="{{ route('privacy') }}" class="text-gold-400 hover:underline">Privacy Policy</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
@endsection