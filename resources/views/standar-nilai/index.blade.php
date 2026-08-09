@extends('layouts.app')

@section('title', 'Standar Penilaian')

@section('content')
<style>
    .sn-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 24px;
    }

    .sn-table-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .sn-table-wrap table {
        min-width: 640px;
    }

    .modal-overlay {
        padding: 20px;
        box-sizing: border-box;
    }

    @media (max-width: 640px) {
        .sn-page-header h2 {
            font-size: 22px !important;
        }

        .sn-page-header > button {
            width: 100%;
            text-align: center;
        }

        .modal-overlay {
            padding: 0 !important;
            align-items: flex-end !important;
        }

        .modal-box {
            max-width: 100% !important;
            width: 100% !important;
            border-radius: 20px 20px 0 0 !important;
            max-height: 92vh !important;
        }

        .modal-box-padded {
            padding: 18px !important;
        }
    }
</style>

<div class="sn-page-header">
    <div>
        <span style="text-transform: uppercase; font-size: 12px; font-weight: 700; letter-spacing: 1.2px; color: var(--accent-green); display: block; margin-bottom: 4px;">Panduan Penilaian Guru</span>
        <h2 style="font-size: 28px; font-weight: 800; margin: 0; letter-spacing: -0.5px;">Aturan Standar Nilai</h2>
    </div>
    <button onclick="document.getElementById('modalForm').style.display='flex'" style="background: var(--accent-green); color: #090d16; border: none; padding: 12px 20px; border-radius: 12px; font-weight: 700; cursor: pointer;">
        + Buat Aturan Baru
    </button>
</div>

@if (session('success'))
    <div style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); color: #6ee7b7; padding: 14px 20px; border-radius: 14px; font-size: 14px; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; padding: 14px 20px; border-radius: 14px; font-size: 14px; margin-bottom: 20px;">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

@php
    $formatWaktu = function ($detik) {
        if ($detik === null) return null;
        $menit = floor($detik / 60);
        $sisaDetik = $detik - ($menit * 60);
        $sisaDetik = rtrim(rtrim(number_format($sisaDetik, 2, '.', ''), '0'), '.');
        return $menit > 0 ? "{$menit}:" . str_pad($sisaDetik, 2, '0', STR_PAD_LEFT) : "{$sisaDetik} dtk";
    };
    $formatPoin = fn ($v) => rtrim(rtrim((string) $v, '0'), '.');
@endphp

<!-- FILTER / SEARCH -->
<div style="background: var(--glass-bg); backdrop-filter: blur(16px); border: 1px solid var(--glass-border); border-radius: 16px; padding: 16px 20px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
    <form method="GET" action="{{ route('standar-nilai.index') }}" style="display: flex; align-items: center; gap: 12px; margin: 0; flex: 1; min-width: 240px; flex-wrap: wrap;">
        <label style="font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">Cari Olahraga:</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama cabang olahraga..." style="flex: 1; background: #161f33; border: 1px solid var(--glass-border); border-radius: 10px; padding: 10px 16px; color: var(--text-main); font-size: 14px; outline: none; min-width: 160px;">
        <button type="submit" style="background: var(--accent-green); color: #090d16; border: none; padding: 10px 18px; border-radius: 10px; font-weight: 700; font-size: 13px; cursor: pointer; white-space: nowrap;">Cari</button>
        @if(request('search'))
            <a href="{{ route('standar-nilai.index') }}" style="color: #f87171; font-size: 13px; text-decoration: none; font-weight: 600; padding: 8px 12px; background: rgba(248, 113, 113, 0.1); border-radius: 8px; border: 1px solid rgba(248, 113, 113, 0.2); white-space: nowrap;">Reset</a>
        @endif
    </form>
</div>

@if ($standars->isEmpty())
    <!-- EMPTY STATE -->
    <div style="border: 1px dashed var(--glass-border); border-radius: 16px; padding: 64px 24px; text-align: center; color: var(--text-muted);">
        <div style="display: flex; justify-content: center; margin-bottom: 14px; color: var(--text-muted); opacity: 0.6;">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1"></rect><line x1="8" y1="12" x2="16" y2="12"></line><line x1="8" y1="16" x2="16" y2="16"></line></svg>
        </div>
        <h3 style="color: var(--text-main); margin: 0 0 6px; font-size: 16px;">
            {{ request('search') ? 'Tidak ada olahraga yang cocok' : 'Belum ada aturan nilai' }}
        </h3>
        <p style="font-size: 13px; margin: 0 0 18px;">
            {{ request('search') ? "Coba kata kunci lain, atau reset pencarian." : 'Buat standar penilaian pertama untuk mulai menilai hasil tes siswa.' }}
        </p>
        @unless(request('search'))
            <button onclick="document.getElementById('modalForm').style.display='flex'" style="background: var(--accent-green); color:#090d16; border:none; padding: 10px 18px; border-radius: 10px; font-weight:700; cursor:pointer;">+ Buat Aturan Baru</button>
        @endunless
    </div>
@else
    <!-- TABLE -->
    <div style="background: var(--glass-bg); backdrop-filter: blur(16px); border: 1px solid var(--glass-border); border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);">
        <div class="sn-table-wrap">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: rgba(255, 255, 255, 0.03); border-bottom: 1px solid var(--glass-border);">
                    <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Cabang Olahraga</th>
                    <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Tipe Penilaian</th>
                    <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Kelengkapan Standar</th>
                    <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($standars as $group)
                    @php
                        $olahraga = $group->first()->jenisOlahraga;
                        $olahragaId = $group->first()->jenis_olahraga_id;
                        $isWaktu = $olahraga && strtolower($olahraga->tipe) == 'waktu';
                        $lakiLaki = $group->firstWhere('jenis_kelamin', 'Laki-Laki');
                        $perempuan = $group->firstWhere('jenis_kelamin', 'Perempuan');
                    @endphp
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 14px 20px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span style="width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: {{ $isWaktu ? 'rgba(59, 130, 246, 0.15)' : 'rgba(16,185,129,0.15)' }}; color: {{ $isWaktu ? '#60a5fa' : 'var(--accent-green)' }};">
                                    @if($isWaktu)
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                    @else
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>
                                    @endif
                                </span>
                                <div>
                                    <div style="color: var(--text-main); font-weight: 700; font-size: 14px;">{{ $olahraga->nama_olahraga ?? 'Olahraga' }}</div>
                                    @if(!empty($olahraga->protokol_tes))
                                        <div style="color: var(--text-muted); font-size: 11.5px;">{{ $olahraga->protokol_tes }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td style="padding: 14px 20px;">
                            <span style="font-size: 11px; background: {{ $isWaktu ? 'rgba(59, 130, 246, 0.15)' : 'rgba(16,185,129,0.15)' }}; color: {{ $isWaktu ? '#60a5fa' : 'var(--accent-green)' }}; padding: 3px 9px; border-radius: 5px; font-weight: 600;">
                                {{ $isWaktu ? 'Waktu' : 'Poin/Repetisi' }}
                            </span>
                        </td>
                        <td style="padding: 14px 20px;">
                            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                <span style="font-size: 11px; padding: 3px 9px; border-radius: 5px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; background: {{ $lakiLaki ? 'rgba(56,189,248,0.15)' : 'rgba(255,255,255,0.05)' }}; color: {{ $lakiLaki ? '#38bdf8' : 'var(--text-faint, #64748b)' }};">
                                    @if($lakiLaki)
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    @else
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    @endif
                                    Laki-Laki
                                </span>
                                <span style="font-size: 11px; padding: 3px 9px; border-radius: 5px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; background: {{ $perempuan ? 'rgba(244,114,182,0.15)' : 'rgba(255,255,255,0.05)' }}; color: {{ $perempuan ? '#f472b6' : 'var(--text-faint, #64748b)' }};">
                                    @if($perempuan)
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    @else
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    @endif
                                    Perempuan
                                </span>
                            </div>
                        </td>
                        <td style="padding: 14px 20px; text-align: right;">
                            <button onclick="document.getElementById('detailModal_{{ $olahragaId }}').style.display='flex'" style="background: rgba(255,255,255,0.06); border: 1px solid var(--glass-border); color: var(--text-main); padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">Detail</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        @if ($standars->hasPages())
            <div style="padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; border-top: 1px solid var(--glass-border); background: rgba(0, 0, 0, 0.1);">
                <div style="font-size: 13px; color: var(--text-muted);">Halaman {{ $standars->currentPage() }} dari {{ $standars->lastPage() }}</div>
                <div style="display: flex; gap: 8px;">
                    @if ($standars->onFirstPage())
                        <span style="padding: 8px 14px; background: rgba(255,255,255,0.02); color: var(--text-muted); border-radius: 8px; font-size: 13px; border: 1px solid var(--glass-border); opacity: 0.5;">&laquo; Prev</span>
                    @else
                        <a href="{{ $standars->previousPageUrl() }}" style="padding: 8px 14px; background: rgba(255,255,255,0.05); color: var(--text-main); border-radius: 8px; font-size: 13px; border: 1px solid var(--glass-border); text-decoration: none;">&laquo; Prev</a>
                    @endif
                    @if ($standars->hasMorePages())
                        <a href="{{ $standars->nextPageUrl() }}" style="padding: 8px 14px; background: rgba(255,255,255,0.05); color: var(--text-main); border-radius: 8px; font-size: 13px; border: 1px solid var(--glass-border); text-decoration: none;">Next &raquo;</a>
                    @else
                        <span style="padding: 8px 14px; background: rgba(255,255,255,0.02); color: var(--text-muted); border-radius: 8px; font-size: 13px; border: 1px solid var(--glass-border); opacity: 0.5;">Next &raquo;</span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- MODAL DETAIL per cabang olahraga -->
    @foreach ($standars as $group)
        @php
            $olahraga = $group->first()->jenisOlahraga;
            $olahragaId = $group->first()->jenis_olahraga_id;
            $isWaktu = $olahraga && strtolower($olahraga->tipe) == 'waktu';
            $columns = array_filter([
                $group->firstWhere('jenis_kelamin', 'Laki-Laki') ? ['label' => 'Laki-Laki', 'data' => $group->firstWhere('jenis_kelamin', 'Laki-Laki'), 'color' => '#38bdf8'] : null,
                $group->firstWhere('jenis_kelamin', 'Perempuan') ? ['label' => 'Perempuan', 'data' => $group->firstWhere('jenis_kelamin', 'Perempuan'), 'color' => '#f472b6'] : null,
            ]);
        @endphp
        <div id="detailModal_{{ $olahragaId }}" class="modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); backdrop-filter: blur(6px); align-items: center; justify-content: center; z-index: 999;">
            <div class="modal-box" style="background: #0d1322; border: 1px solid var(--glass-border); border-radius: 20px; width: 100%; max-width: 560px; padding: 0; max-height: 85vh; overflow: hidden; display: flex; flex-direction: column;">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid var(--glass-border);">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="width: 32px; height: 32px; border-radius: 9px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: {{ $isWaktu ? 'rgba(59, 130, 246, 0.15)' : 'rgba(16,185,129,0.15)' }}; color: {{ $isWaktu ? '#60a5fa' : 'var(--accent-green)' }};">
                            @if($isWaktu)
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            @else
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>
                            @endif
                        </span>
                        <div>
                            <h3 style="margin: 0; font-size: 17px; font-weight: 800; color: var(--text-main);">{{ $olahraga->nama_olahraga ?? 'Olahraga' }}</h3>
                            <p style="margin: 2px 0 0; font-size: 12px; color: var(--text-muted);">{{ $isWaktu ? 'Dinilai dari waktu tempuh' : 'Dinilai dari poin / repetisi' }}</p>
                        </div>
                    </div>
                    <button type="button" onclick="document.getElementById('detailModal_{{ $olahragaId }}').style.display='none'" style="background: none; border: none; color: var(--text-muted); font-size: 20px; cursor: pointer;">&times;</button>
                </div>

                <div style="overflow-y: auto; padding: 0;">
                    <div class="standar-genders {{ count($columns) > 1 ? '' : 'single' }}">
                        @foreach ($columns as $i => $col)
                            <div style="padding: 18px 24px; {{ $i === 0 && count($columns) > 1 ? 'border-right: 1px solid var(--glass-border);' : '' }}">
                                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
                                    <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; color: {{ $col['color'] }};">{{ $col['label'] }}</span>
                                    <form action="{{ route('standar-nilai.destroy', $col['data']->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus aturan nilai {{ $col['label'] }}?')" style="margin:0;">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Hapus aturan {{ $col['label'] }}" style="background: none; border: none; color: var(--text-muted); cursor: pointer; opacity: 0.6; padding: 3px; display: flex; align-items: center;" onmouseover="this.style.opacity=1;this.style.color='#fca5a5'" onmouseout="this.style.opacity=0.6;this.style.color='var(--text-muted)'">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </form>
                                </div>

                                <div>
                                    @foreach ($col['data']->details as $d)
                                        <div style="display:flex; align-items:baseline; gap:10px; padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,0.03); font-size:13px;">
                                            <span style="width: 26px; flex-shrink:0; font-weight:800; font-size:12.5px; color: {{ $col['color'] }}; font-variant-numeric: tabular-nums;">{{ $d->grade }}</span>
                                            <span style="color: var(--text-muted);">
                                                @if($isWaktu)
                                                    @if($d->minimal !== null && $d->maksimal !== null)
                                                        <span style="color:white;">{{ $formatWaktu($d->minimal) }}</span> – <span style="color:white;">{{ $formatWaktu($d->maksimal) }}</span>
                                                    @elseif($d->minimal !== null)
                                                        &gt; <span style="color:white;">{{ $formatWaktu($d->minimal) }}</span>
                                                    @else
                                                        &le; <span style="color:white;">{{ $formatWaktu($d->maksimal) }}</span>
                                                    @endif
                                                @else
                                                    @if($d->minimal !== null && $d->maksimal !== null)
                                                        <span style="color:white;">{{ $formatPoin($d->minimal) }}</span> – <span style="color:white;">{{ $formatPoin($d->maksimal) }}</span> poin
                                                    @elseif($d->minimal !== null)
                                                        &ge; <span style="color:white;">{{ $formatPoin($d->minimal) }}</span> poin
                                                    @else
                                                        &le; <span style="color:white;">{{ $formatPoin($d->maksimal) }}</span> poin
                                                    @endif
                                                @endif
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif

<style>
    .standar-genders { display: grid; grid-template-columns: 1fr 1fr; }
    .standar-genders.single { grid-template-columns: 1fr; }
    @media (max-width: 560px) {
        .standar-genders { grid-template-columns: 1fr !important; }
        .standar-genders > div { border-right: none !important; border-bottom: 1px solid var(--glass-border); }
        .standar-genders > div:last-child { border-bottom: none; }
    }
</style>

<!-- Modal Tambah Data Dinamis -->
<div id="modalForm" class="modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); backdrop-filter: blur(6px); align-items: center; justify-content: center; z-index: 999;">
    <div class="modal-box modal-box-padded" style="background: #0d1322; border: 1px solid var(--glass-border); border-radius: 20px; width: 100%; max-width: 620px; padding: 24px; max-height: 90vh; overflow-y: auto;">
        <h3 style="margin-top: 0; margin-bottom: 4px;">Buat Standar Nilai Baru</h3>
        <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">Sesuaikan kategori penilaian berdasarkan karakteristik olahraga.</p>

        <form method="POST" action="{{ route('standar-nilai.store') }}" id="standarNilaiForm">
            @csrf
            <div style="margin-bottom: 12px;">
                <label style="font-size: 12px; font-weight: 700; color: var(--text-main);">PILIH CABANG OLAHRAGA</label>
                <select name="jenis_olahraga_id" id="pilihOlahraga" onchange="ubahTipeInput()" style="width: 100%; background: #161f33; border: 1px solid var(--glass-border); padding: 10px; border-radius: 8px; color: white; margin-top: 4px;" required>
                    <option value="">-- Pilih Olahraga --</option>
                    @foreach ($olahragas as $o)
                        <option value="{{ $o->id }}" data-tipe="{{ strtolower($o->tipe) }}">
                            {{ $o->nama_olahraga }} ({{ ucfirst($o->tipe) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="font-size: 12px; font-weight: 700; color: var(--text-main);">TARGET KELAMIN SISWA</label>
                <select name="jenis_kelamin" style="width: 100%; background: #161f33; border: 1px solid var(--glass-border); padding: 10px; border-radius: 8px; color: white; margin-top: 4px;" required>
                    <option value="Laki-Laki">Laki-Laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>

            <hr style="border: 0; border-top: 1px solid var(--glass-border); margin: 16px 0;">

            <div id="infoPanduan" style="font-size: 13px; color: var(--accent-green); margin-bottom: 12px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                Silakan pilih cabang olahraga terlebih dahulu di atas.
            </div>

            <div id="hintArahPoin" style="display: none; font-size: 12px; color: #93c5fd; background: rgba(59,130,246,0.08); border: 1px solid rgba(59,130,246,0.2); padding: 10px 12px; border-radius: 8px; margin-bottom: 14px;">
                <b>Cara isi untuk olahraga poin (makin banyak makin bagus):</b> Grade <b>A</b> (terbaik) isi kolom <b>"Dari"</b> saja dengan angka minimal, kosongkan <b>"Sampai"</b> (artinya "angka ini ke atas"). Grade <b>D</b> (terendah) sebaliknya — isi <b>"Sampai"</b> saja, kosongkan <b>"Dari"</b>.
            </div>

            <div id="formRentangNilai" style="display: none;">
                <h4 id="judulRentang" style="margin: 0 0 4px 0; font-size: 14px;">Aturan Penilaian</h4>
                <p id="subJudulRentang" style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;"></p>
                <p style="font-size: 11px; color: var(--text-faint, #64748b); margin-bottom: 14px;">Kosongkan kolom kalau grade itu tidak punya batas (misalnya grade tertinggi tidak perlu "batas atas").</p>

                @php $grades = ['A', 'AB', 'B', 'BC', 'C', 'D']; @endphp
                @foreach ($grades as $index => $g)
                    <div class="grade-row" style="margin-bottom: 10px; padding: 12px 14px; border-bottom: 1px solid var(--glass-border);">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                            <span style="font-weight: 800; font-size: 14px; color: var(--accent-green); min-width: 26px; font-variant-numeric: tabular-nums;">{{ $g }}</span>
                            <span style="font-size: 12px; color: var(--text-muted);">Grade {{ $g }}</span>
                        </div>

                        <input type="hidden" name="grades[{{ $index }}][grade]" value="{{ $g }}">
                        <input type="hidden" name="grades[{{ $index }}][minimal]" id="inputMin_{{ $index }}" class="hidden-min">
                        <input type="hidden" name="grades[{{ $index }}][maksimal]" id="inputMax_{{ $index }}" class="hidden-max">

                        <!-- INPUT MODE: POIN -->
                        <div class="poin-mode" data-index="{{ $index }}" style="display: none; align-items: center; gap: 10px; flex-wrap: wrap;">
                            <div style="display:flex; flex-direction:column; gap:3px;">
                                <span style="font-size: 10px; color: var(--text-muted);">Dari</span>
                                <input type="number" step="any" min="0" placeholder="kosongkan" class="poin-min" data-index="{{ $index }}" style="width: 100px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 8px; border-radius: 6px; color: white; text-align:center;">
                            </div>
                            <span style="color: var(--text-muted); margin-top: 14px;">→</span>
                            <div style="display:flex; flex-direction:column; gap:3px;">
                                <span style="font-size: 10px; color: var(--text-muted);">Sampai</span>
                                <input type="number" step="any" min="0" placeholder="kosongkan" class="poin-max" data-index="{{ $index }}" style="width: 100px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 8px; border-radius: 6px; color: white; text-align:center;">
                            </div>
                            <span style="font-size: 11px; color: var(--text-muted); margin-top: 14px;">poin</span>
                        </div>

                        <!-- INPUT MODE: WAKTU (menit + detik, tanpa jam) -->
                        <div class="waktu-mode" data-index="{{ $index }}" style="display: none; align-items: center; gap: 14px; flex-wrap: wrap;">
                            <div style="display:flex; flex-direction:column; gap:3px;">
                                <span style="font-size: 10px; color: var(--text-muted);">Dari</span>
                                <div style="display:flex; align-items:center; gap:2px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; padding: 4px 6px;">
                                    <input type="number" min="0" placeholder="0" class="waktu-menit-min" data-index="{{ $index }}" style="width: 40px; background: transparent; border: none; color: white; text-align:center; font-variant-numeric: tabular-nums; font-size:14px; padding: 0 2px;">
                                    <span style="color: var(--text-muted); font-size:10px;">mnt</span>
                                    <span style="color: var(--text-muted); font-weight:700; margin: 0 2px;">:</span>
                                    <input type="number" min="0" max="59" step="0.01" placeholder="0" class="waktu-detik-min" data-index="{{ $index }}" style="width: 68px; background: transparent; border: none; color: white; text-align:center; font-variant-numeric: tabular-nums; font-size:14px; padding: 0 2px;">
                                    <span style="color: var(--text-muted); font-size:10px;">dtk</span>
                                </div>
                            </div>
                            <span style="color: var(--text-muted); margin-top: 14px;">→</span>
                            <div style="display:flex; flex-direction:column; gap:3px;">
                                <span style="font-size: 10px; color: var(--text-muted);">Sampai</span>
                                <div style="display:flex; align-items:center; gap:2px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; padding: 4px 6px;">
                                    <input type="number" min="0" placeholder="0" class="waktu-menit-max" data-index="{{ $index }}" style="width: 40px; background: transparent; border: none; color: white; text-align:center; font-variant-numeric: tabular-nums; font-size:14px; padding: 0 2px;">
                                    <span style="color: var(--text-muted); font-size:10px;">mnt</span>
                                    <span style="color: var(--text-muted); font-weight:700; margin: 0 2px;">:</span>
                                    <input type="number" min="0" max="59" step="0.01" placeholder="0" class="waktu-detik-max" data-index="{{ $index }}" style="width: 68px; background: transparent; border: none; color: white; text-align:center; font-variant-numeric: tabular-nums; font-size:14px; padding: 0 2px;">
                                    <span style="color: var(--text-muted); font-size:10px;">dtk</span>
                                </div>
                            </div>
                        </div>
                        <div style="font-size: 10px; color: var(--text-faint, #64748b); margin-top: 4px;" class="waktu-mode" data-index="{{ $index }}">Kalau di bawah 1 menit, kolom menit boleh dikosongkan — cukup isi detiknya saja.</div>

                        <!-- LIVE PREVIEW -->
                        <div class="preview-text" id="preview_{{ $index }}" style="margin-top: 8px; font-size: 12px; color: #60a5fa; font-style: italic;"></div>

                        <!-- WARNING NILAI MINUS -->
                        <div class="warning-text" id="warning_{{ $index }}" style="display: none; margin-top: 6px; font-size: 12px; color: #fca5a5; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.25); padding: 6px 10px; border-radius: 6px; align-items: center; gap: 6px;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            <span>Nilai tidak boleh minus.</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button type="button" onclick="document.getElementById('modalForm').style.display='none'" style="background: transparent; border: 1px solid var(--glass-border); color: white; padding: 8px 16px; border-radius: 8px; cursor: pointer;">Batal</button>
                <button type="submit" style="background: var(--accent-green); border: none; padding: 8px 16px; border-radius: 8px; font-weight: 700; cursor: pointer; color: #090d16;">Simpan Aturan</button>
            </div>
        </form>
    </div>
</div>

<script>
function ubahTipeInput() {
    const select = document.getElementById('pilihOlahraga');
    const selectedOption = select.options[select.selectedIndex];
    const tipe = selectedOption.getAttribute('data-tipe');

    const infoPanduan = document.getElementById('infoPanduan');
    const formRentang = document.getElementById('formRentangNilai');
    const judulRentang = document.getElementById('judulRentang');
    const subJudulRentang = document.getElementById('subJudulRentang');
    const hintArahPoin = document.getElementById('hintArahPoin');

    const poinModes = document.querySelectorAll('.poin-mode');
    const waktuModes = document.querySelectorAll('.waktu-mode');

    if (!tipe) {
        infoPanduan.style.display = 'flex';
        formRentang.style.display = 'none';
        hintArahPoin.style.display = 'none';
        return;
    }

    infoPanduan.style.display = 'none';
    formRentang.style.display = 'block';

    if (tipe === 'waktu') {
        judulRentang.innerText = "Aturan Berbasis Waktu";
        subJudulRentang.innerText = "Isi menit & detik. Kalau di bawah 1 menit, kolom menit boleh dikosongkan. Waktu lebih cepat = nilai lebih bagus.";
        poinModes.forEach(el => el.style.display = 'none');
        waktuModes.forEach(el => el.style.display = 'flex');
        hintArahPoin.style.display = 'none';
    } else {
        judulRentang.innerText = "Aturan Berbasis Poin / Jumlah";
        subJudulRentang.innerText = "Masukkan jumlah perolehan hasil. Angka lebih besar = nilai lebih bagus.";
        poinModes.forEach(el => el.style.display = 'flex');
        waktuModes.forEach(el => el.style.display = 'none');
        hintArahPoin.style.display = 'block';
    }

    syncAllHiddenFields();
}

function formatDetikSingkat(detik) {
    const menit = Math.floor(detik / 60);
    let sisa = detik - (menit * 60);
    sisa = Math.round(sisa * 100) / 100;
    const sisaStr = Number.isInteger(sisa) ? sisa : sisa.toFixed(2).replace(/0+$/, '').replace(/\.$/, '');
    return menit > 0 ? `${menit} menit ${sisaStr} detik` : `${sisaStr} detik`;
}

function updatePreview(index) {
    const previewEl = document.getElementById(`preview_${index}`);
    const min = document.getElementById(`inputMin_${index}`).value;
    const max = document.getElementById(`inputMax_${index}`).value;
    const select = document.getElementById('pilihOlahraga');
    const tipe = select.options[select.selectedIndex]?.getAttribute('data-tipe');

    if (min === '' && max === '') {
        previewEl.innerText = '';
        return;
    }

    if (tipe === 'waktu') {
        if (min !== '' && max !== '') {
            previewEl.innerText = `→ Waktu ${formatDetikSingkat(parseFloat(min))} s.d ${formatDetikSingkat(parseFloat(max))}`;
        } else if (min !== '') {
            previewEl.innerText = `→ Waktu di atas ${formatDetikSingkat(parseFloat(min))}`;
        } else {
            previewEl.innerText = `→ Waktu maksimal ${formatDetikSingkat(parseFloat(max))}`;
        }
    } else {
        if (min !== '' && max !== '') {
            previewEl.innerText = `→ Hasil antara ${min} s.d ${max} poin`;
        } else if (min !== '') {
            previewEl.innerText = `→ Minimal ${min} poin ke atas`;
        } else {
            previewEl.innerText = `→ Maksimal sampai ${max} poin`;
        }
    }
}

function cekNilaiMinus(index) {
    const select = document.getElementById('pilihOlahraga');
    const tipe = select.options[select.selectedIndex]?.getAttribute('data-tipe');

    let adaMinus = false;

    if (tipe === 'waktu') {
        const nilai = [
            document.querySelector(`.waktu-menit-min[data-index="${index}"]`).value,
            document.querySelector(`.waktu-detik-min[data-index="${index}"]`).value,
            document.querySelector(`.waktu-menit-max[data-index="${index}"]`).value,
            document.querySelector(`.waktu-detik-max[data-index="${index}"]`).value,
        ];
        adaMinus = nilai.some(v => v !== '' && parseFloat(v) < 0);
    } else {
        const nilai = [
            document.querySelector(`.poin-min[data-index="${index}"]`).value,
            document.querySelector(`.poin-max[data-index="${index}"]`).value,
        ];
        adaMinus = nilai.some(v => v !== '' && parseFloat(v) < 0);
    }

    const warningEl = document.getElementById(`warning_${index}`);
    warningEl.style.display = adaMinus ? 'flex' : 'none';

    updateTombolSimpan();

    return adaMinus;
}

function updateTombolSimpan() {
    const adaMinusDiManapun = Array.from(document.querySelectorAll('.warning-text'))
        .some(el => el.style.display === 'flex');

    const tombolSimpan = document.querySelector('#standarNilaiForm button[type="submit"]');
    tombolSimpan.disabled = adaMinusDiManapun;
    tombolSimpan.style.opacity = adaMinusDiManapun ? '0.5' : '1';
    tombolSimpan.style.cursor = adaMinusDiManapun ? 'not-allowed' : 'pointer';
}

function syncPoinField(index, suffix) {
    const poinEl = document.querySelector(`.poin-${suffix}[data-index="${index}"]`);
    const hiddenEl = document.getElementById(suffix === 'min' ? `inputMin_${index}` : `inputMax_${index}`);
    hiddenEl.value = poinEl.value.trim();
    cekNilaiMinus(index);
    updatePreview(index);
}

function syncWaktuField(index, suffix) {
    const menitEl = document.querySelector(`.waktu-menit-${suffix}[data-index="${index}"]`);
    const detikEl = document.querySelector(`.waktu-detik-${suffix}[data-index="${index}"]`);
    const hiddenEl = document.getElementById(suffix === 'min' ? `inputMin_${index}` : `inputMax_${index}`);

    if (menitEl.value === '' && detikEl.value === '') {
        hiddenEl.value = '';
        cekNilaiMinus(index);
        updatePreview(index);
        return;
    }

    const menit = parseFloat(menitEl.value) || 0;
    const detik = parseFloat(detikEl.value) || 0;
    hiddenEl.value = (menit * 60 + detik).toString();
    cekNilaiMinus(index);
    updatePreview(index);
}

function syncAllHiddenFields() {
    const select = document.getElementById('pilihOlahraga');
    const tipe = select.options[select.selectedIndex]?.getAttribute('data-tipe');

    document.querySelectorAll('.grade-row').forEach((row, idx) => {
        if (tipe === 'waktu') {
            syncWaktuField(idx, 'min');
            syncWaktuField(idx, 'max');
        } else {
            syncPoinField(idx, 'min');
            syncPoinField(idx, 'max');
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.poin-min, .poin-max').forEach(el => {
        el.addEventListener('input', () => syncPoinField(el.dataset.index, el.classList.contains('poin-min') ? 'min' : 'max'));
    });

    document.querySelectorAll('.waktu-menit-min, .waktu-detik-min').forEach(el => {
        el.addEventListener('input', () => syncWaktuField(el.dataset.index, 'min'));
    });

    document.querySelectorAll('.waktu-menit-max, .waktu-detik-max').forEach(el => {
        el.addEventListener('input', () => syncWaktuField(el.dataset.index, 'max'));
    });

    document.getElementById('standarNilaiForm').addEventListener('submit', function (e) {
        syncAllHiddenFields();

        const adaMinus = Array.from(document.querySelectorAll('.grade-row')).some((row, idx) => cekNilaiMinus(idx));

        if (adaMinus) {
            e.preventDefault();
            alert('Masih ada nilai minus di form. Perbaiki dulu sebelum menyimpan.');
        }
    });

    // Klik di luar modal detail = tutup
    document.querySelectorAll('[id^="detailModal_"]').forEach(modal => {
        modal.addEventListener('click', (e) => { if (e.target === modal) modal.style.display = 'none'; });
    });
});
</script>
@endsection