@extends('layouts.app')

@section('title', 'Dashboard — Tes Olahraga Siswa')

@section('content')
    <!-- Header Dashboard -->
    <div class="page-head">
        <div>
            <span style="text-transform: uppercase; font-size: 12px; font-weight: 700; letter-spacing: 1.2px; color: var(--accent-green); display: block; margin-bottom: 6px;">Ringkasan Sistem</span>
            <h1 style="font-size: 30px; font-weight: 800; margin: 0; letter-spacing: -0.5px;">Selamat Datang, {{ explode(' ', auth()->user()->name ?? 'Guru')[0] }} 👋</h1>
        </div>
        
        <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--glass-border); padding: 8px 16px; border-radius: 12px; font-size: 13px; color: var(--text-muted); display: flex; align-items: center; gap: 8px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            <span>{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    <!-- 1. Row Statistik Utama (Stat Cards) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 32px;">
        <!-- Card 1: Total Kelas -->
        <div style="background: var(--glass-bg); border: 1px solid var(--glass-border); border-radius: 16px; padding: 20px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <span style="font-size: 12px; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 4px;">TOTAL KELAS</span>
                <span style="font-size: 28px; font-weight: 800; color: var(--text-main);">{{ $totalKelas ?? 0 }}</span>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(16, 185, 129, 0.15); color: var(--accent-green); display: flex; align-items: center; justify-content: center;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
            </div>
        </div>

        <!-- Card 2: Total Siswa -->
        <div style="background: var(--glass-bg); border: 1px solid var(--glass-border); border-radius: 16px; padding: 20px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <span style="font-size: 12px; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 4px;">TOTAL SISWA</span>
                <span style="font-size: 28px; font-weight: 800; color: var(--text-main);">{{ $totalSiswa ?? 0 }}</span>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(59, 130, 246, 0.15); color: #60a5fa; display: flex; align-items: center; justify-content: center;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
            </div>
        </div>

        <!-- Card 3: Sesi Tes Selesai -->
        <div style="background: var(--glass-bg); border: 1px solid var(--glass-border); border-radius: 16px; padding: 20px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <span style="font-size: 12px; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 4px;">TES TERLAKSANA</span>
                <span style="font-size: 28px; font-weight: 800; color: var(--text-main);">0</span>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(168, 85, 247, 0.15); color: #c084fc; display: flex; align-items: center; justify-content: center;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
            </div>
        </div>
    </div>

    <!-- 2. Grid Informasi Utama & Akses Cepat -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start;">
        
        <!-- Kolom Kiri: Pengumuman / Info Sesi -->
        <div style="background: var(--glass-bg); border: 1px solid var(--glass-border); border-radius: 20px; padding: 28px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0; font-size: 18px; font-weight: 700;">Aktivitas & Panduan</h3>
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
        <div style="background: var(--glass-bg); border: 1px solid var(--glass-border); border-radius: 20px; padding: 24px;">
            <h3 style="margin: 0 0 16px 0; font-size: 16px; font-weight: 700;">Tindakan Cepat</h3>
            
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('kelas.index') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px; background: rgba(255, 255, 255, 0.03); border: 1px solid var(--glass-border); border-radius: 12px; color: var(--text-main); text-decoration: none; font-size: 14px; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='var(--glass-hover)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.03)'">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--accent-green);"></span>
                    <span>Kelola Data Kelas</span>
                </a>

                <a href="#" style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: rgba(255, 255, 255, 0.01); border: 1px solid var(--glass-border); border-radius: 12px; color: var(--text-muted); text-decoration: none; font-size: 14px; font-weight: 500; opacity: 0.6; cursor: not-allowed;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--text-muted);"></span>
                        <span>Mulai Tes Fisik</span>
                    </div>
                    <span style="font-size: 10px; font-weight: 700; text-transform: uppercase; background: rgba(255, 255, 255, 0.08); padding: 2px 6px; border-radius: 4px;">Soon</span>
                </a>
            </div>
        </div>
    </div>
@endsection