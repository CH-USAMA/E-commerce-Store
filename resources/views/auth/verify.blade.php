@extends('layouts.frontend')

@section('title', 'Verify Email - Jabulani Group')

@section('content')
    <div class="pt-32 pb-24 bg-dark min-h-[70vh] flex flex-col justify-center">
        <div class="max-w-md mx-auto w-full px-4 sm:px-6 lg:px-8">
            <div class="card-dark p-8 md:p-10 rounded-3xl border border-white/10text-center relative overflow-hidden group">
                {{-- Background Accent --}}
                <div
                    class="absolute -right-20 -top-20 w-64 h-64 bg-gold-400/5 rounded-full blur-3xl group-hover:bg-gold-400/10 transition-colors duration-700 pointer-events-none">
                </div>

                <div class="flex justify-center mb-6 relative z-10">
                    <div
                        class="w-20 h-20 rounded-full bg-gold-400/10 flex items-center justify-center border border-gold-400/20">
                        <i class="fas fa-envelope-open-text text-3xl text-gold-400"></i>
                    </div>
                </div>

                <h2 class="text-3xl font-black text-white mb-2 tracking-tight text-center relative z-10">Verify Your <span
                        class="text-gold-400">Email</span></h2>
                <p class="text-gray-400 text-sm mb-8 text-center relative z-10">
                    Thanks for signing up! Before getting started, could you verify your email address by clicking on the
                    link we just emailed to you?
                </p>

                @if (session('success'))
                    <div
                        class="mb-6 bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-3 relative z-10">
                        <i class="fas fa-check-circle"></i>
                        A new verification link has been sent to the email address you provided during registration.
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.send') }}" class="relative z-10">
                    @csrf
                    <button type="submit"
                        class="w-full btn-gold py-4 px-6 rounded-xl font-black uppercase tracking-widest text-sm flex items-center justify-center gap-2 group/btn">
                        Resend Verification Email
                        <i class="fas fa-paper-plane group-hover/btn:translate-x-1 transition-transform"></i>
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="mt-6 text-center relative z-10">
                    @csrf
                    <button type="submit"
                        class="text-xs font-bold text-dark-muted hover:text-white transition-colors uppercase tracking-wider">
                        <i class="fas fa-sign-out-alt mr-1"></i> Log Out
                    </button>
                </form>

            </div>
        </div>
    </div>
@endsection