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

        .app-layout {
            display: flex;
            min-height: 100vh;
        }

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
            margin-bottom: 28px;
            letter-spacing: -0.2px;
        }

        .brand-badge {
            width: 10px;
            height: 10px;
            background: var(--accent-green);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--accent-green);
            flex-shrink: 0;
        }

        .sidebar-section-label {
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(148, 163, 184, 0.55);
            padding: 0 14px;
            margin: 18px 0 8px 0;
        }

        .sidebar-section-label:first-of-type {
            margin-top: 0;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .nav-icon {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .nav-link-item {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 600;
            padding: 10px 14px;
            border-radius: 12px;
            transition: background 0.18s ease, color 0.18s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            border: none;
            background: transparent;
            font-family: inherit;
            cursor: pointer;
            box-sizing: border-box;
            position: relative;
        }

        .nav-link-item .nav-label {
            flex: 1;
            text-align: left;
        }

        .nav-link-item:hover {
            color: var(--text-main);
            background: var(--glass-hover);
        }

        .nav-link-item.active {
            color: var(--text-main);
            background: rgba(16, 185, 129, 0.12);
        }

        .nav-link-item.active .nav-icon {
            color: var(--accent-green);
        }

        .nav-link-item.active::before {
            content: '';
            position: absolute;
            left: -16px;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 18px;
            background: var(--accent-green);
            border-radius: 0 4px 4px 0;
        }

        .dropdown-container {
            display: flex;
            flex-direction: column;
        }

        .dropdown-chevron {
            transition: transform 0.22s ease;
            flex-shrink: 0;
        }

        .dropdown-container.open .dropdown-chevron {
            transform: rotate(180deg);
        }

        .submenu {
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows 0.22s ease;
            margin-left: 14px;
            border-left: 1px solid var(--glass-border);
        }

        .dropdown-container.open .submenu {
            grid-template-rows: 1fr;
        }

        .submenu-inner {
            overflow: hidden;
            display: flex;
            flex-direction: column;
            gap: 1px;
            padding-left: 12px;
        }

        .submenu-item {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 12.5px;
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 9px;
            transition: all 0.18s ease;
            display: flex;
            align-items: center;
            gap: 9px;
            margin-top: 4px;
        }

        .submenu-item:first-child {
            margin-top: 6px;
        }

        .submenu-item:last-child {
            margin-bottom: 4px;
        }

        .submenu-item .nav-icon {
            width: 15px;
            height: 15px;
        }

        .submenu-item:hover {
            color: var(--text-main);
            background: var(--glass-hover);
        }

        .submenu-item.active {
            color: var(--accent-green);
            background: rgba(16, 185, 129, 0.08);
            font-weight: 700;
        }

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
            flex-shrink: 0;
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-logout:hover {
            background: #ef4444;
            color: #fff;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 40px;
            max-width: 1100px;
        }

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

        /* ===== RESPONSIVE MOBILE ===== */
        .sidebar-toggle-btn {
            display: none;
            position: fixed;
            top: 16px;
            left: 16px;
            z-index: 200;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            color: var(--text-main);
            width: 42px;
            height: 42px;
            border-radius: 12px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 99;
        }

        @media (max-width: 900px) {
            .sidebar-toggle-btn {
                display: flex;
            }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.25s ease;
                z-index: 150;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .sidebar-overlay.mobile-open {
                display: block;
            }

            .main-content {
                margin-left: 0;
                padding: 24px 16px 80px;
                max-width: 100%;
                padding-top: 72px;
            }
        }

        @media (max-width: 560px) {
            .page-head {
                flex-direction: column;
                align-items: flex-start;
            }

            .glass-card {
                padding: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="field-bg"></div>

    <button type="button" class="sidebar-toggle-btn" onclick="toggleSidebar()" aria-label="Buka menu">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
    </button>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <div class="app-layout">
        <!-- Sidebar Samping -->
        <aside class="sidebar">
            <div>
                <a href="{{ Auth::user()->role === 'superadmin' ? route('admin.guru.index') : route('dashboard') }}" class="sidebar-brand">
                    <span class="brand-badge"></span>
                    <span>TES OLAHRAGA SISWA</span>
                </a>

                @if (Auth::user()->role === 'superadmin')
                    <div class="sidebar-section-label">Menu</div>
                    <ul class="sidebar-nav">
                        <li>
                            <a href="{{ route('admin.guru.index') }}" class="nav-link-item {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}">
                                <span class="nav-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                </span>
                                <span class="nav-label">Kelola Akun Guru</span>
                            </a>
                        </li>
                    </ul>
                @else
                    <div class="sidebar-section-label">Menu</div>
                    <ul class="sidebar-nav">
                        <li>
                            <a href="{{ route('dashboard') }}" class="nav-link-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <span class="nav-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1.5"></rect><rect x="14" y="3" width="7" height="5" rx="1.5"></rect><rect x="14" y="12" width="7" height="9" rx="1.5"></rect><rect x="3" y="16" width="7" height="5" rx="1.5"></rect></svg>
                                </span>
                                <span class="nav-label">Dashboard</span>
                            </a>
                        </li>

                        @php
                            $dataActive = request()->routeIs('kelas.*') || request()->routeIs('siswa.*') || request()->routeIs('jenis-kelamin.*') || request()->routeIs('jenis-olahraga.*');
                        @endphp
                        <li class="dropdown-container {{ $dataActive ? 'open' : '' }}" id="dataDropdown">
                            <button class="nav-link-item" id="dropdownBtn" onclick="toggleDropdown()">
                                <span class="nav-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M3 5v14a9 3 0 0 0 18 0V5"></path><path d="M3 12a9 3 0 0 0 18 0"></path></svg>
                                </span>
                                <span class="nav-label">Data</span>
                                <svg class="dropdown-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </button>
                            <div class="submenu">
                                <div class="submenu-inner">
                                    <a href="{{ route('kelas.index') }}" class="submenu-item {{ request()->routeIs('kelas.*') ? 'active' : '' }}">
                                        <span class="nav-icon">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                                        </span>
                                        Kelas
                                    </a>
                                    <a href="{{ route('siswa.index') }}" class="submenu-item {{ request()->routeIs('siswa.*') ? 'active' : '' }}">
                                        <span class="nav-icon">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                        </span>
                                        Siswa
                                    </a>
                                    <a href="{{ route('jenis-kelamin.index') }}" class="submenu-item {{ request()->routeIs('jenis-kelamin.*') ? 'active' : '' }}">
                                        <span class="nav-icon">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="5"></circle><path d="M12 13v8"></path><path d="M9 18h6"></path></svg>
                                        </span>
                                        Jenis Kelamin
                                    </a>
                                    <a href="{{ route('jenis-olahraga.index') }}" class="submenu-item {{ request()->routeIs('jenis-olahraga.*') ? 'active' : '' }}">
                                        <span class="nav-icon">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a15 15 0 0 1 0 20"></path><path d="M12 2a15 15 0 0 0 0 20"></path><path d="M2 12h20"></path></svg>
                                        </span>
                                        Jenis Olahraga
                                    </a>
                                </div>
                            </div>
                        </li>

                        <li>
                            <a href="{{ route('standar-nilai.index') }}" class="nav-link-item {{ request()->routeIs('standar-nilai.*') ? 'active' : '' }}">
                                <span class="nav-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"></circle><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"></path></svg>
                                </span>
                                <span class="nav-label">Standar Nilai</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('sesi-tes.index') }}" class="nav-link-item {{ request()->routeIs('sesi-tes.*') ? 'active' : '' }}">
                                <span class="nav-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                </span>
                                <span class="nav-label">Tes</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('hasil-tes.index') }}" class="nav-link-item {{ request()->routeIs('hasil-tes.*') ? 'active' : '' }}">
                                <span class="nav-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="3" width="16" height="18" rx="2"></rect><path d="M9 3h6a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1z"></path><line x1="8" y1="11" x2="16" y2="11"></line><line x1="8" y1="15" x2="16" y2="15"></line><line x1="8" y1="19" x2="12" y2="19"></line></svg>
                                </span>
                                <span class="nav-label">Hasil Tes</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('akun-siswa.index') }}" class="nav-link-item {{ request()->routeIs('akun-siswa.*') ? 'active' : '' }}">
                                <span class="nav-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                </span>
                                <span class="nav-label">Akun Siswa</span>
                            </a>
                        </li>
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
                    <button type="submit" class="btn-logout">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                        Logout
                    </button>
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
            document.getElementById("dataDropdown").classList.toggle("open");
        }

        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('mobile-open');
            document.getElementById('sidebarOverlay').classList.toggle('mobile-open');
        }
    </script>
</body>
</html>