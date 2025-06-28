<x-guest-layout>

    <style>
        body {
            background: #f1f6fb;
            font-family: 'Segoe UI', sans-serif;
        }

        .auth-card {
            max-width: 400px;
            margin: 80px auto;
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            animation: fadeIn 0.5s ease;
        }

        .auth-card h1 {
            text-align: center;
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 1.5rem;
        }

        input[type="email"],
        input[type="password"] {
            border: 1px solid #d1d5db;
            padding: 0.75rem 1rem;
            width: 100%;
            border-radius: 8px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #74b9ff;
            box-shadow: 0 0 0 3px rgba(116, 185, 255, 0.2);
            outline: none;
        }

        label {
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 0.5rem;
            display: block;
        }

        .btn-primary {
            background: #74b9ff;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            width: 100%;
            font-weight: 600;
            transition: background 0.3s ease;
            cursor: pointer;
        }

        .btn-primary:hover {
            background: #4aa3ff;
        }

        .forgot-link {
            font-size: 0.85rem;
            color: #4aa3ff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            color: #2d8fe6;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 1rem 0;
            font-size: 0.9rem;
        }

        .error-message {
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div class="auth-card">
        <h1>Welcome Back</h1>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-4">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username">
                @if($errors->has('email'))
                    <div class="error-message">{{ $errors->first('email') }}</div>
                @endif
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
                @if($errors->has('password'))
                    <div class="error-message">{{ $errors->first('password') }}</div>
                @endif
            </div>

            <!-- Remember Me -->
            <div class="remember-me">
                <input id="remember_me" type="checkbox" name="remember">
                <label for="remember_me">Remember me</label>
            </div>

            <div class="flex justify-between items-center mb-4">
                @if (Route::has('password.request'))
                    <a class="forgot-link" href="{{ route('password.request') }}">
                        Forgot your password?
                    </a>
                @endif
            </div>

            <div>
                <button class="btn-primary" type="submit">Log in</button>
            </div>
        </form>
    </div>

</x-guest-layout>
