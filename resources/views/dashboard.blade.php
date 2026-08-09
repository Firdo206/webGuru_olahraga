@extends('layouts.app')

@section('title', 'Dashboard — Tes Olahraga Siswa')

@section('content')
    <style>
        .page-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 28px;
        }

        .page-head-title {
            font-size: 30px;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .page-head-badges {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-card-value {
            font-size: 28px;
            font-weight: 800;
            color: var(--text-main);
        }

        .main-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            align-items: start;
        }

        .info-panel,
        .quickaction-panel {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
        }

        .info-panel {
            padding: 28px;
        }

        .quickaction-panel {
            padding: 24px;
        }

        /* ===== Tablet ===== */
        @media (max-width: 900px) {
            .main-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ===== Mobile ===== */
        @media (max-width: 640px) {
            .page-head-title {
                font-size: 22px;
            }

            .page-head-badges {
                width: 100%;
                justify-content: flex-start;
            }

            .stat-grid {
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
                gap: 12px;
                margin-bottom: 24px;
            }

            .stat-card {
                padding: 16px;
                border-radius: 14px;
            }

            .stat-card-value {
                font-size: 22px;
            }

            .info-panel {
                padding: 20px;
            }

            .quickaction-panel {
                padding: 18px;
            }
        }

        @media (max-width: 400px) {
            .page-head-badges > div {
                font-size: 12px;
                padding: 6px 12px;
            }
        }
    </style>

    <!-- Header Dashboard -->
    <div class="page-head">
        <div>
            <span style="text-transform: uppercase; font-size: 12px; font-weight: 700; letter-spacing: 1.2px; color: var(--accent-green); display: block; margin-bottom: 6px;">Ringkasan Sistem</span>
            <h1 class="page-head-title">Selamat Datang, {{ explode(' ', auth()->user()->name ?? 'Guru')[0] }} 👋</h1>
        </div>

        <div class="page-head-badges">
            <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--glass-border); padding: 8px 16px; border-radius: 12px; font-size: 13px; color: var(--text-muted); display: flex; align-items: center; gap: 8px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                <span>{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>

            <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.25); padding: 8px 16px; border-radius: 12px; font-size: 14px; color: var(--accent-green); display: flex; align-items: center; gap: 8px; font-weight: 700; font-variant-numeric: tabular-nums;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                <span id="jamSekarang">--:--:--</span>
            </div>
        </div>
    </div>

    <!-- 1. Row Statistik Utama (Stat Cards) -->
    <div class="stat-grid">
        <!-- Card 1: Total Kelas -->
        <div class="stat-card">
            <div>
                <span style="font-size: 12px; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 4px;">TOTAL KELAS</span>
                <span class="stat-card-value">{{ $totalKelas ?? 0 }}</span>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(16, 185, 129, 0.15); color: var(--accent-green); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
            </div>
        </div>

        <!-- Card 2: Total Siswa -->
        <div class="stat-card">
            <div>
                <span style="font-size: 12px; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 4px;">TOTAL SISWA</span>
                <span class="stat-card-value">{{ $totalSiswa ?? 0 }}</span>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(59, 130, 246, 0.15); color: #60a5fa; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
            </div>
        </div>

        <!-- Card 3: Sesi Tes Selesai -->
        <div class="stat-card">
            <div>
                <span style="font-size: 12px; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 4px;">TES TERLAKSANA</span>
                <span class="stat-card-value">{{ $tesTerlaksana ?? 0 }}</span>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(168, 85, 247, 0.15); color: #c084fc; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
            </div>
        </div>
    </div>

    <!-- 2. Grid Informasi Utama & Akses Cepat -->
    <div class="main-grid">

        <!-- Kolom Kiri: Pengumuman / Info Sesi -->
        <div class="info-panel">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; margin-bottom: 20px;">
                <h3 style="margin: 0; font-size: 18px; font-weight: 700;">Aktivitas &amp; Panduan</h3>
                <span style="font-size: 12px; color: var(--accent-green); background: rgba(16, 185, 129, 0.1); padding: 4px 10px; border-radius: 6px; font-weight: 600;">Sistem Aktif</span>
            </div>

            <p style="color: var(--text-muted); font-size: 14px; line-height: 1.6; margin: 0 0 20px 0;">
                Selamat datang di platform penilaian Tes Kebugaran Olahraga Siswa. Anda dapat mengelola data kelas, memantau daftar siswa, serta mencatat hasil tes fisik berkala secara langsung melalui menu yang tersedia.
            </p>

            <div style="background: rgba(255, 255, 255, 0.02); border: 1px dashed var(--glass-border); border-radius: 12px; padding: 16px; display: flex; gap: 14px; align-items: flex-start;">
                <div style="color: var(--accent-green); font-size: 20px;">💡</div>
                <div style="font-size: 13px; color: var(--text-muted); line-height: 1.5;">
                    <strong style="color: var(--text-main); display: block; margin-bottom: 2px;">Langkah Pertama:</strong>
                    Mulai dengan membuat data kelas di menu <strong>Data → Kelas</strong> pada sidebar kiri, lalu tambahkan daftar siswa di dalamnya.
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Shortcut Akses Cepat Ringkas -->
        <div class="quickaction-panel">
            <h3 style="margin: 0 0 16px 0; font-size: 16px; font-weight: 700;">Tindakan Cepat</h3>

            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('kelas.index') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px; background: rgba(255, 255, 255, 0.03); border: 1px solid var(--glass-border); border-radius: 12px; color: var(--text-main); text-decoration: none; font-size: 14px; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='var(--glass-hover)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.03)'">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--accent-green); flex-shrink: 0;"></span>
                    <span>Kelola Data Kelas</span>
                </a>

                <a href="{{ route('sesi-tes.index') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px; background: rgba(255, 255, 255, 0.03); border: 1px solid var(--glass-border); border-radius: 12px; color: var(--text-main); text-decoration: none; font-size: 14px; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='var(--glass-hover)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.03)'">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--accent-green); flex-shrink: 0;"></span>
                    <span>Mulai Tes Fisik</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        function updateJamSekarang() {
            const el = document.getElementById('jamSekarang');
            if (!el) return;
            const now = new Date();
            const jam = String(now.getHours()).padStart(2, '0');
            const menit = String(now.getMinutes()).padStart(2, '0');
            const detik = String(now.getSeconds()).padStart(2, '0');
            el.textContent = `${jam}:${menit}:${detik}`;
        }

        updateJamSekarang();
        setInterval(updateJamSekarang, 1000);
    </script>
@endsection