@extends('layouts.app')

@section('title', 'Hasil Tes')

@section('content')
    <style>
        .ht-table-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .ht-table-wrap table {
            min-width: 700px;
        }

        @media (max-width: 640px) {
            .page-header h2 {
                font-size: 22px !important;
            }
        }
    </style>

    <!-- Page Header -->
    <div class="page-header" style="margin-bottom: 24px;">
        <span style="text-transform: uppercase; font-size: 12px; font-weight: 700; letter-spacing: 1.2px; color: var(--accent-green); display: block; margin-bottom: 4px;">Kegiatan</span>
        <h2 style="font-size: 28px; font-weight: 800; margin: 0; letter-spacing: -0.5px;">Hasil Tes</h2>
    </div>

    @if (session('success'))
        <div style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); color: #6ee7b7; padding: 14px 20px; border-radius: 14px; font-size: 14px; margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- FILTER KELAS -->
    <div style="background: var(--glass-bg); backdrop-filter: blur(16px); border: 1px solid var(--glass-border); border-radius: 16px; padding: 16px 20px; margin-bottom: 20px; display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
        <form method="GET" action="{{ route('hasil-tes.index') }}" id="filterForm" style="display: flex; align-items: center; gap: 12px; margin: 0; flex-wrap: wrap;">
            <label style="font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Filter Kelas:</label>
            <select name="kelas_id" onchange="document.getElementById('filterForm').submit()" style="background: #161f33; border: 1px solid var(--glass-border); border-radius: 10px; padding: 10px 16px; color: var(--text-main); font-size: 14px; outline: none; min-width: 200px; cursor: pointer;">
                <option value="">-- Semua Kelas --</option>
                @foreach ($kelas as $k)
                    <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                @endforeach
            </select>
            @if(request('kelas_id'))
                <a href="{{ route('hasil-tes.index') }}" style="color: #f87171; font-size: 13px; text-decoration: none; font-weight: 600; padding: 8px 12px; background: rgba(248, 113, 113, 0.1); border-radius: 8px; border: 1px solid rgba(248, 113, 113, 0.2);">✕ Reset Filter</a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div style="background: var(--glass-bg); backdrop-filter: blur(16px); border: 1px solid var(--glass-border); border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);">
        <div class="ht-table-wrap">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: rgba(255, 255, 255, 0.03); border-bottom: 1px solid var(--glass-border);">
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Tanggal &amp; Waktu</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Kelas</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Jenis Olahraga</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Status</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Siswa Mengisi</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sesiList as $s)
                    @php
                        $badge = match ($s->status) {
                            'aktif' => ['bg' => 'rgba(16, 185, 129, 0.12)', 'color' => '#6ee7b7', 'label' => 'Aktif'],
                            'selesai' => ['bg' => 'rgba(255, 255, 255, 0.06)', 'color' => 'var(--text-muted)', 'label' => 'Selesai'],
                            default => ['bg' => 'rgba(251, 191, 36, 0.12)', 'color' => '#fbbf24', 'label' => 'Belum Mulai'],
                        };
                    @endphp
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 18px 24px; color: var(--text-main); font-weight: 600;">
                            {{ \Carbon\Carbon::parse($s->tanggal)->format('d M Y') }}
                            <div style="color: var(--text-muted); font-size: 13px; font-weight: 400;">
                                {{ \Carbon\Carbon::parse($s->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($s->waktu_berakhir)->format('H:i') }}
                            </div>
                        </td>
                        <td style="padding: 18px 24px; color: var(--text-muted);">{{ $s->kelas->nama_kelas ?? '—' }}</td>
                        <td style="padding: 18px 24px; color: var(--text-muted);">{{ $s->jenisOlahraga->nama_olahraga ?? '—' }}</td>
                        <td style="padding: 18px 24px;">
                            <span style="background: {{ $badge['bg'] }}; color: {{ $badge['color'] }}; padding: 4px 12px; border-radius: 6px; font-size: 13px; font-weight: 600;">{{ $badge['label'] }}</span>
                        </td>
                        <td style="padding: 18px 24px; color: var(--text-main);">{{ $s->hasil_tes_count }} siswa</td>
                        <td style="padding: 18px 24px; text-align: right;">
                            <a href="{{ route('hasil-tes.show', $s->id) }}" style="color: #6ee7b7; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; white-space: nowrap;">Lihat Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 48px 24px; text-align: center; color: var(--text-muted);">Belum ada riwayat sesi tes.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>

        @if ($sesiList->hasPages())
            <div style="padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; border-top: 1px solid var(--glass-border); background: rgba(0, 0, 0, 0.1);">
                <div style="font-size: 13px; color: var(--text-muted);">Halaman {{ $sesiList->currentPage() }} dari {{ $sesiList->lastPage() }}</div>
                <div style="display: flex; gap: 8px;">
                    @if ($sesiList->onFirstPage())
                        <span style="padding: 8px 14px; background: rgba(255,255,255,0.02); color: var(--text-muted); border-radius: 8px; font-size: 13px; border: 1px solid var(--glass-border); opacity: 0.5;">&laquo; Prev</span>
                    @else
                        <a href="{{ $sesiList->previousPageUrl() }}" style="padding: 8px 14px; background: rgba(255,255,255,0.05); color: var(--text-main); border-radius: 8px; font-size: 13px; border: 1px solid var(--glass-border); text-decoration: none;">&laquo; Prev</a>
                    @endif
                    @if ($sesiList->hasMorePages())
                        <a href="{{ $sesiList->nextPageUrl() }}" style="padding: 8px 14px; background: rgba(255,255,255,0.05); color: var(--text-main); border-radius: 8px; font-size: 13px; border: 1px solid var(--glass-border); text-decoration: none;">Next &raquo;</a>
                    @else
                        <span style="padding: 8px 14px; background: rgba(255,255,255,0.02); color: var(--text-muted); border-radius: 8px; font-size: 13px; border: 1px solid var(--glass-border); opacity: 0.5;">Next &raquo;</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection