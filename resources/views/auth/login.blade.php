<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jabulani Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --jabulani-orange: #FF8C00;
            --jabulani-black: #1A1A1A;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--jabulani-black) 0%, #2d2d2d 50%, #3a3a3a 100%);
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            overflow: hidden;
        }

        .login-header {
            background: var(--jabulani-black);
            padding: 2rem;
            text-align: center;
            border-bottom: 3px solid var(--jabulani-orange);
        }

        .login-header img {
            height: 60px;
            margin-bottom: 0.75rem;
        }

        .login-header h4 {
            color: white;
            margin: 0;
            font-weight: 700;
        }

        .login-header p {
            color: #aaa;
            font-size: 0.85rem;
            margin: 0;
        }

        .login-body {
            padding: 2rem;
            background: white;
        }

        .form-control:focus {
            border-color: var(--jabulani-orange);
            box-shadow: 0 0 0 0.2rem rgba(255, 140, 0, 0.25);
        }

        .btn-jabulani {
            background-color: var(--jabulani-orange);
            color: white;
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            font-size: 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-jabulani:hover {
            background-color: #e67e00;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 140, 0, 0.4);
        }

        .form-label {
            font-weight: 600;
            color: #333;
        }

        .back-link {
            color: #999;
            text-decoration: none;
            font-size: 0.85rem;
        }

        .back-link:hover {
            color: var(--jabulani-orange);
        }
    </style>
</head>

<body>
    <div class="card login-card">
        <div class="login-header">
            <img src="{{ asset('images/logo_yellow2.png') }}" alt="Jabulani Logo">
            <h4>Jabulani<span style="color: var(--jabulani-orange);">.</span> Admin</h4>
            <p>Sign in to manage your stores</p>
        </div>
        <div class="login-body">
            @if($errors->any())
                <div class="alert alert-danger py-2 small">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required
                        autofocus placeholder="admin@jabulani.com">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required
                        placeholder="Enter your password">
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button type="submit" class="btn btn-jabulani w-100 mb-3">Sign In</button>
                <div class="text-center">
                    <a href="{{ route('home') }}" class="back-link">&larr; Back to Website</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>