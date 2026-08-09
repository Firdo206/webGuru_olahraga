<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Guru — TKS (Tes Kebugaran Siswa)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --font-main: 'Plus Jakarta Sans', sans-serif;
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --glass-hover: rgba(255, 255, 255, 0.08);
            --accent-green: #10b981;
            --accent-green-glow: rgba(16, 185, 129, 0.25);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --text-faint: #64748b;
        }

        body {
            margin: 0;
            font-family: var(--font-main);
            background: #090d16;
            color: var(--text-main);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        .field-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 50% 20%, rgba(16, 185, 129, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(59, 130, 246, 0.1) 0%, transparent 45%);
            z-index: -1;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
        }

        .brand-icon-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 24px;
        }

      .login-logo {
    width: 180px;
    height: 180px;
    display: flex;
    align-items: center;
    justify-content: center;
    filter: drop-shadow(0 0 22px var(--accent-green-glow));
}

.login-logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    filter: brightness(0) invert(1)
            drop-shadow(0 0 10px rgba(16,185,129,.45));
}

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 40px 32px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .eyebrow {
            text-transform: uppercase;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            color: var(--accent-green);
            text-align: center;
            display: block;
            margin-bottom: 6px;
        }

        .login-title {
            text-align: center;
            font-size: 26px;
            font-weight: 800;
            margin: 0 0 6px 0;
            letter-spacing: -0.5px;
        }

        .login-subtitle {
            text-align: center;
            color: var(--text-muted);
            font-size: 14px;
            margin: 0 0 28px 0;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 20px;
            text-align: center;
        }

        .field {
            margin-bottom: 20px;
        }

        .field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        .field input[type="email"],
        .field input[type="password"],
        .field input[type="text"] {
            width: 100%;
            background: rgba(0, 0, 0, 0.25);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 12px 16px;
            color: var(--text-main);
            font-family: inherit;
            font-size: 14px;
            box-sizing: border-box;
            transition: all 0.2s ease;
            outline: none;
        }

        .field input:focus {
            border-color: var(--accent-green);
            box-shadow: 0 0 0 3px var(--accent-green-glow);
            background: rgba(0, 0, 0, 0.4);
        }

        .field-password-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .field-password-wrap input {
            padding-right: 48px;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s ease;
        }

        .toggle-password:hover {
            color: var(--text-main);
        }

        .toggle-password .icon-eye-off {
            display: none;
        }

        .toggle-password.is-visible .icon-eye {
            display: none;
        }

        .toggle-password.is-visible .icon-eye-off {
            display: block;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            border-radius: 6px;
            accent-color: var(--accent-green);
            cursor: pointer;
        }

        .checkbox-group label {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 500;
            cursor: pointer;
            user-select: none;
        }

        .btn-primary {
            width: 100%;
            background: var(--accent-green);
            color: #090d16;
            border: none;
            border-radius: 12px;
            padding: 14px 20px;
            font-size: 15px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 16px var(--accent-green-glow);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px var(--accent-green-glow);
            filter: brightness(1.05);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .footer-text {
            text-align: center;
            color: var(--text-faint);
            font-size: 13px;
            margin-top: 24px;
        }

        .dev-watermark {
            position: fixed;
            bottom: 14px;
            right: 18px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: var(--text-faint);
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--glass-border);
            padding: 6px 12px;
            border-radius: 999px;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 10;
            user-select: none;
            pointer-events: none;
        }

        .dev-watermark span {
            color: var(--accent-green);
        }

        .footer-credit {
            text-align: center;
            color: var(--text-faint);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-top: 8px;
            opacity: 0.7;
        }

        .footer-credit span {
            color: var(--accent-green);
        }
    </style>
</head>
<body>
    <div class="field-bg"></div>

    <div class="dev-watermark">BY <span>IQI_PJOK</span></div>

    <div class="login-wrapper">
        <div class="login-container">

            <div class="brand-icon-wrapper">
               <div class="login-logo">
    <img src="{{ asset('images/logo-tks-new.png') }}" alt="Logo TKS">
</div>
            </div>

            <div class="glass-card">
                <span class="eyebrow">Portal Guru</span>
                <h1 class="login-title">TKS<br>Tes Kebugaran Siswa</h1>
                <p class="login-subtitle">Masuk untuk mengelola kelas dan sesi tes</p>

                @if ($errors->any())
                    <div class="alert-error">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <div class="field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="guru@sekolah.com" required autofocus>
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <div class="field-password-wrap">
                            <input type="password" name="password" id="password" placeholder="••••••••" required>
                            <button type="button" class="toggle-password" onclick="togglePassword()" aria-label="Tampilkan password">
                                <svg class="icon-eye" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg class="icon-eye-off" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a21.6 21.6 0 0 1 5.06-6.06M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a21.6 21.6 0 0 1-2.16 3.19"></path>
                                    <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Ingat saya</label>
                    </div>

                    <button type="submit" class="btn-primary">Masuk</button>
                </form>
            </div>

            <p class="footer-text">
                Akun guru dibuat oleh administrator sistem
            </p>
            <p class="footer-credit">BY <span>IQI_PJOK</span></p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const btn = document.querySelector('.toggle-password');
            const isPassword = input.type === 'password';

            input.type = isPassword ? 'text' : 'password';
            btn.classList.toggle('is-visible', isPassword);
        }
    </script>
</body>
</html>