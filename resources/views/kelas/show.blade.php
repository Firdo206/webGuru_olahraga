<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Kelas — {{ $kelas->nama_kelas }}</title>
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

        /* Dynamic background glow effect */
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
            max-width: 1000px;
            margin: 0 auto;
            padding: 32px 20px 60px 20px;
        }

        /* Navigation Header */
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

        /* Header Bar */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .eyebrow {
            text-transform: uppercase;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.2px;
            color: var(--accent-green);
            display: block;
            margin-bottom: 4px;
        }

        .page-header h2 {
            font-size: 28px;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.5px;
        }

        /* Primary Add Button */
        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--accent-green);
            color: #090d16;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 16px var(--accent-green-glow);
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px var(--accent-green-glow);
            filter: brightness(1.05);
        }

        /* Alert Success */
        .alert-success {
            background: rgba(16, 185, 129, 0.12);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #6ee7b7;
            padding: 14px 20px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Glass Table Container */
        .table-card {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background: rgba(255, 255, 255, 0.03);
            padding: 18px 24px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            border-bottom: 1px solid var(--glass-border);
        }

        td {
            padding: 18px 24px;
            border-bottom: 1px solid var(--glass-border);
            font-size: 15px;
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: var(--glass-hover);
        }

        /* Password Tag */
        .password-badge {
            display: inline-block;
            font-family: monospace;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid var(--glass-border);
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 13px;
            color: #cbd5e1;
        }

        /* Action Buttons */
        .actions-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-action-edit {
            color: #38bdf8;
            background: rgba(56, 189, 248, 0.1);
            border: 1px solid rgba(56, 189, 248, 0.2);
            padding: 8px 14px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-action-edit:hover {
            background: #38bdf8;
            color: #090d16;
        }

        .btn-action-delete {
            color: #f87171;
            background: rgba(248, 113, 113, 0.1);
            border: 1px solid rgba(248, 113, 113, 0.2);
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.2s ease;
        }

        .btn-action-delete:hover {
            background: #f87171;
            color: #ffffff;
        }

        /* Empty State */
        .empty-state {
            padding: 48px 24px;
            text-align: center;
            color: var(--text-muted);
        }

        .empty-state svg {
            margin-bottom: 12px;
            opacity: 0.5;
        }

        .empty-state p {
            margin: 0;
            font-size: 15px;
        }
    </style>
</head>
<body>
    <div class="field-bg"></div>

    <div class="container">
        <!-- Back Navigation -->
        <a href="{{ route('kelas.index') }}" class="nav-back">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Kelola Kelas</span>
        </a>

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <span class="eyebrow">Daftar Siswa</span>
                <h2>Kelas {{ $kelas->nama_kelas }}</h2>
            </div>
            <a href="{{ route('siswa.create', $kelas->id) }}" class="btn-add">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Tambah Siswa</span>
            </a>
        </div>

        <!-- Success Alert -->
        @if (session('success'))
            <div class="alert-success">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Table Card -->
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th style="width: 120px;">No. Absen</th>
                        <th>Nama Siswa</th>
                        <th style="width: 180px;">Password</th>
                        <th style="width: 180px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($siswa as $s)
                        <tr>
                            <td style="font-weight: 700; color: var(--accent-green);">
                                {{ sprintf('%02d', $s->nomor_absen) }}
                            </td>
                            <td style="font-weight: 600;">
                                {{ $s->nama }}
                            </td>
                            <td>
                                <span class="password-badge">{{ $s->password_plain }}</span>
                            </td>
                            <td>
                                <div class="actions-group" style="justify-content: flex-end;">
                                    <a href="{{ route('siswa.edit', [$kelas->id, $s->id]) }}" class="btn-action-edit">Edit</a>
                                    
                                    <form method="POST" action="{{ route('siswa.destroy', [$kelas->id, $s->id]) }}" style="margin:0;" onsubmit="return confirm('Yakin hapus siswa ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action-delete">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                    <p>Belum ada siswa di kelas ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>