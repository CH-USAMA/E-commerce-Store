<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jabulani Group</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon-96x96.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { gold: { DEFAULT: '#f5c518' }, dark: { DEFAULT: '#0a0a0a', card: '#111111', border: '#2a2a2a', muted: '#888' } }, fontFamily: { sans: ['Inter', 'sans-serif'] } } } }
    </script>
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <style>
        body {
            background: #0a0a0a;
            font-family: 'Inter', sans-serif;
        }

        .btn-gold {
            background: #f5c518;
            color: #0a0a0a;
            font-weight: 700;
            transition: all 0.2s;
        }

        .btn-gold:hover {
            background: #e0b000;
            transform: translateY(-1px);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center px-4 py-12">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo_yellow2.png') }}" alt="Jabulani" class="h-12 w-auto mx-auto mb-5">
            </a>
            <h1 class="text-2xl font-black text-white mb-1">Welcome Back<span class="text-yellow-400">.</span></h1>
            <p class="text-sm" style="color:#888">Sign in to continue to your account</p>
        </div>

        <div class="rounded-2xl p-8" style="background:#111;border:1px solid #2a2a2a">

            @if($errors->any())
                <div class="rounded-xl px-4 py-3 mb-5 text-sm flex items-center gap-2"
                    style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#f87171">
                    <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
                </div>
            @endif

            @if(session('success'))
                <div class="rounded-xl px-4 py-3 mb-5 text-sm flex items-center gap-2"
                    style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);color:#4ade80">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-semibold mb-2"
                        style="color:#888;text-transform:uppercase;letter-spacing:0.05em">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="you@example.com"
                        class="w-full rounded-xl px-4 py-3 text-sm focus:outline-none transition"
                        style="background:#0a0a0a;border:1px solid #2a2a2a;color:#f1f1f1;caret-color:#f5c518"
                        onfocus="this.style.borderColor='#f5c518'" onblur="this.style.borderColor='#2a2a2a'">
                </div>
                <div class="mb-5">
                    <label class="block text-xs font-semibold mb-2"
                        style="color:#888;text-transform:uppercase;letter-spacing:0.05em">Password</label>
                    <input type="password" name="password" required placeholder="Your password"
                        class="w-full rounded-xl px-4 py-3 text-sm focus:outline-none transition"
                        style="background:#0a0a0a;border:1px solid #2a2a2a;color:#f1f1f1;caret-color:#f5c518"
                        onfocus="this.style.borderColor='#f5c518'" onblur="this.style.borderColor='#2a2a2a'">
                </div>
                <div class="flex items-center justify-between mb-5 text-xs" style="color:#888">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded accent-yellow-400">
                        <span>Remember me</span>
                    </label>
                </div>
                <button type="submit"
                    class="btn-gold w-full py-3.5 rounded-xl text-sm font-bold flex items-center justify-center gap-2">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>

            <div class="border-t mt-6 pt-6 text-center" style="border-color:#2a2a2a">
                <p class="text-sm" style="color:#888">Don't have an account?
                    <a href="{{ route('register') }}" class="font-bold" style="color:#f5c518">Create one free</a>
                </p>
            </div>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-xs inline-flex items-center gap-1.5" style="color:#555">
                <i class="fas fa-arrow-left text-xs"></i> Back to website
            </a>
        </div>
    </div>

</body>

</html>