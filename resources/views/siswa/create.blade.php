<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Siswa — {{ $kelas->nama_kelas }}</title>
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
                radial-gradient(circle at 20% 20%, rgba(16, 185, 129, 0.12) 0%, transparent 45%),
                radial-gradient(circle at 80% 80%, rgba(59, 130, 246, 0.1) 0%, transparent 50%);
            z-index: -1;
        }

        .container {
            max-width: 560px;
            margin: 0 auto;
            padding: 32px 20px 60px 20px;
        }

        .nav-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 24px;
            padding: 8px 16px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .nav-back:hover {
            color: var(--text-main);
            background: var(--glass-hover);
            transform: translateX(-3px);
        }

        .page-header {
            margin-bottom: 28px;
        }

        .eyebrow {
            text-transform: uppercase;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.2px;
            color: var(--accent-green);
            display: block;
            margin-bottom: 6px;
        }

        .page-header h2 {
            font-size: 28px;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 14px 20px;
            border-radius: 14px;
            font-size: 14px;
            margin-bottom: 24px;
        }

        .alert-error p {
            margin: 0 0 4px 0;
        }

        .alert-error p:last-child {
            margin-bottom: 0;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
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

        .field input[type="text"]:focus {
            border-color: var(--accent-green);
            box-shadow: 0 0 0 3px var(--accent-green-glow);
            background: rgba(0, 0, 0, 0.4);
        }

        .btn-submit {
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
            margin-top: 8px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px var(--accent-green-glow);
            filter: brightness(1.05);
        }
    </style>
</head>
<body>
    <div class="field-bg"></div>

    <div class="container">
        <!-- Back Navigation -->
        <a href="{{ route('kelas.show', $kelas->id) }}" class="nav-back">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Kembali ke Detail Kelas</span>
        </a>

        <!-- Header -->
        <div class="page-header">
            <span class="eyebrow">Kelas {{ $kelas->nama_kelas }}</span>
            <h2>Tambah Siswa Baru</h2>
        </div>

        <!-- Errors -->
        @if ($errors->any())
            <div class="alert-error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Form Card -->
        <div class="glass-card">
            <form method="POST" action="{{ route('siswa.store', $kelas->id) }}">
                @csrf

                <div class="field">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Budi Santoso" required autofocus>
                </div>

                <div class="field">
                    <label for="nomor_absen">Nomor Absen</label>
                    <input type="text" id="nomor_absen" name="nomor_absen" value="{{ old('nomor_absen') }}" placeholder="Contoh: 01" required>
                </div>

                <div class="field">
                    <label for="password">Password Siswa</label>
                    <input type="text" id="password" name="password" placeholder="Buat password login siswa" required>
                </div>

                <button type="submit" class="btn-submit">Simpan Data Siswa</button>
            </form>
        </div>
    </div>
</body>
</html>