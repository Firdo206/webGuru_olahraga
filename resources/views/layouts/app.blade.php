<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Tes Olahraga Siswa')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --font-main: 'Plus Jakarta Sans', sans-serif;
            --glass-bg: rgba(255, 255, 255, 0.04);
            --glass-border: rgba(255, 255, 255, 0.08);
            --glass-hover: rgba(255, 255, 255, 0.08);
            --accent-green: #10b981;
            --accent-green-glow: rgba(16, 185, 129, 0.25);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --sidebar-width: 260px;
        }

        body {
            margin: 0;
            font-family: var(--font-main);
            background: #090d16;
            color: var(--text-main);
            min-height: 100vh;
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

        /* App Layout Container (Sidebar Samping & Content) */
        .app-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Samping Kiri */
        .sidebar {
            width: var(--sidebar-width);
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-right: 1px solid var(--glass-border);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 24px 16px;
            box-sizing: border-box;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-main);
            font-weight: 800;
            font-size: 15px;
            padding: 8px 12px;
            margin-bottom: 24px;
        }

        .brand-badge {
            width: 10px;
            height: 10px;
            background: var(--accent-green);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--accent-green);
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .nav-link-item {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            padding: 10px 14px;
            border-radius: 12px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            border: none;
            background: transparent;
            font-family: inherit;
            cursor: pointer;
            box-sizing: border-box;
        }

        .nav-link-item:hover, .nav-link-item.active {
            color: var(--text-main);
            background: var(--glass-hover);
        }

        /* Dropdown Samping */
        .dropdown-container {
            display: flex;
            flex-direction: column;
        }

        .submenu {
            display: none;
            flex-direction: column;
            gap: 4px;
            padding-left: 16px;
            margin-top: 4px;
        }

        .submenu.show {
            display: flex;
        }

        .submenu-item {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .submenu-item:hover {
            color: var(--accent-green);
            background: var(--glass-hover);
        }

        /* User Profile & Logout Bottom */
        .sidebar-footer {
            border-top: 1px solid var(--glass-border);
            padding-top: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 8px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(16, 185, 129, 0.2);
            color: var(--accent-green);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        .btn-logout {
            background: rgba(239, 68, 68, 0.12);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.25);
            padding: 10px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            transition: all 0.2s ease;
        }

        .btn-logout:hover {
            background: #ef4444;
            color: #fff;
        }

        /* Area Main Content (Offset dari Sidebar) */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 40px;
            max-width: 1100px;
        }

        /* Component Cards */
        .page-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 32px;
            text-decoration: none;
            color: inherit;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card.active:hover {
            transform: translateY(-4px);
            background: var(--glass-hover);
            border-color: rgba(16, 185, 129, 0.4);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3), 0 0 20px rgba(16, 185, 129, 0.1);
        }

        .glass-card.disabled {
            opacity: 0.55;
            cursor: not-allowed;
            background: rgba(255, 255, 255, 0.02);
        }

        .icon-wrapper {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
        }

        .glass-card.active .icon-wrapper {
            background: var(--accent-green);
            color: #090d16;
            box-shadow: 0 4px 16px var(--accent-green-glow);
        }

        .glass-card.disabled .icon-wrapper {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-muted);
            border: 1px solid var(--glass-border);
        }

        .glass-card h3 {
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 8px 0;
            letter-spacing: -0.3px;
        }

        .glass-card p {
            color: var(--text-muted);
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
        }

        .badge-coming-soon {
            position: absolute;
            top: 24px;
            right: 24px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 4px 10px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.06);
            color: var(--text-muted);
            border: 1px solid var(--glass-border);
        }
    </style>
</head>
<body>
    <div class="field-bg"></div>

    <div class="app-layout">
        <!-- Sidebar Samping -->
       <aside class="sidebar">
    <div>
        <a href="{{ Auth::user()->role === 'superadmin' ? route('admin.guru.index') : route('dashboard') }}" class="sidebar-brand">
            <span class="brand-badge"></span>
            <span>TES OLAHRAGA SISWA</span>
        </a>

        @if (Auth::user()->role === 'superadmin')
            <ul class="sidebar-nav">
                <li>
                    <a href="{{ route('admin.guru.index') }}" class="nav-link-item {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}">👤 Kelola Akun Guru</a>
                </li>
            </ul>
        @else
            <ul class="sidebar-nav">
                <li>
                    <a href="{{ route('dashboard') }}" class="nav-link-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                </li>
                <li class="dropdown-container">
                    <button class="nav-link-item" id="dropdownBtn" onclick="toggleDropdown()">
                        <span>Data</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div class="submenu" id="dropdownMenu">
                        <a href="{{ route('kelas.index') }}" class="submenu-item">📁 Kelas</a>
                        <a href="{{ route('siswa.index') }}" class="submenu-item {{ request()->routeIs('siswa.*') ? 'active' : '' }}">👨‍🎓 Siswa</a>
                       <a href="{{ route('jenis-kelamin.index') }}" class="submenu-item {{ request()->routeIs('jenis-kelamin.*') ? 'active' : '' }}"> ⚧ Jenis Kelamin</a>
                       <a href="{{ route('jenis-olahraga.index') }}" class="submenu-item {{ request()->routeIs('olahraga.*') ? 'active' : '' }}">⚽ Jenis Olahraga</a>
                    </div>
                </li>
                <li>
                    <a href="{{ route('standar-nilai.index') }}"class="nav-link-item {{ request()->routeIs('standar-nilai.*') ? 'active' : '' }}">📐 Standar Nilai</a></li>
               <li><a href="{{ route('sesi-tes.index') }}" class="nav-link-item {{ request()->routeIs('sesi-tes.*') ? 'active' : '' }}">🏃 Tes</a></li>
<li><a href="{{ route('hasil-tes.index') }}" class="nav-link-item {{ request()->routeIs('hasil-tes.*') ? 'active' : '' }}">📊 Hasil Tes</a></li>
              <li><a href="{{ route('akun-siswa.index') }}" class="nav-link-item {{ request()->routeIs('akun-siswa.*') ? 'active' : '' }}">Akun Siswa</a></li>
            </ul>
        @endif
    </div>

    <!-- Footer User Sidebar -->
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">
                {{ strtoupper(substr(Auth::user()->name ?? 'G', 0, 1)) }}
            </div>
            <div>
                <div style="font-size: 13px; font-weight: 700;">{{ Auth::user()->name ?? 'Guru' }}</div>
                <div style="font-size: 11px; color: var(--text-muted);">{{ Auth::user()->role === 'superadmin' ? 'Superadmin' : 'Pengajar' }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
</aside>

        <!-- Main Content Samping Kanan Sidebar -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

   <script>
    function toggleDropdown() {
        document.getElementById("dropdownMenu").classList.toggle("show");
    }
    document.addEventListener("DOMContentLoaded", function() {
        if ("{{ request()->routeIs('kelas.*') || request()->routeIs('siswa.*') || request()->routeIs('jenis-olahraga.*') }}" === "1") {
            document.getElementById("dropdownMenu").classList.add("show");
        }
    });
</script>
</body>
</html>