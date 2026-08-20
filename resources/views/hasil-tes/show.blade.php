@extends('layouts.app')

@section('title', 'Detail Hasil Tes')

@section('content')
    <style>
        .ht-table-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .ht-table-wrap table {
            min-width: 620px;
        }

        @media (max-width: 640px) {
            .page-header h2 {
                font-size: 20px !important;
            }

            .page-header a[href*="export"] {
                flex: 1;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .search-form-wrap {
                flex-wrap: wrap;
            }

            .search-form-wrap input[type="text"] {
                flex-basis: 100%;
            }
        }
    </style>

    <div class="page-header" style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 12px;">
        <div>
            <a href="{{ route('hasil-tes.index') }}" style="color: var(--text-muted); font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">&larr; Kembali ke Riwayat</a>
            <h2 style="font-size: 28px; font-weight: 800; margin: 0 0 6px 0; letter-spacing: -0.5px;">
                {{ $sesiTes->jenisOlahraga->nama_olahraga ?? '—' }} — {{ $sesiTes->kelas->nama_kelas ?? '—' }}
            </h2>
            <p style="margin: 0; color: var(--text-muted); font-size: 14px;">
                {{ \Carbon\Carbon::parse($sesiTes->tanggal)->format('d M Y') }} • {{ \Carbon\Carbon::parse($sesiTes->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($sesiTes->waktu_berakhir)->format('H:i') }}
            </p>
        </div>

        <div style="display: flex; align-items: center; gap: 10px;">
            <a href="{{ route('hasil-tes.export', $sesiTes->id) }}" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: var(--accent-green); color: #090d16; border: none; padding: 12px 20px; border-radius: 12px; font-weight: 700; font-size: 14px; text-decoration: none;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                <span>Download Excel</span>
            </a>
            <a href="{{ route('hasil-tes.export-pdf', $sesiTes->id) }}" style="color: #f87171; background: rgba(248, 113, 113, 0.1); border: 1px solid rgba(248, 113, 113, 0.2); padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none;">
                Download PDF
            </a>
        </div>
    </div>

    <div style="background: rgba(255,255,255,0.03); border: 1px solid var(--glass-border); border-radius: 14px; padding: 12px 20px; margin-bottom: 20px; font-size: 13px; color: var(--text-muted);">
        <b style="color: var(--accent-green);">{{ $sudahTes }}</b> dari <b style="color: var(--text-main);">{{ $totalSiswaKelas }}</b> siswa sudah mengirim hasil tes.
    </div>

    <!-- SEARCH -->
    <div style="background: var(--glass-bg); backdrop-filter: blur(16px); border: 1px solid var(--glass-border); border-radius: 16px; padding: 16px 20px; margin-bottom: 20px;">
        <form method="GET" action="{{ route('hasil-tes.show', $sesiTes->id) }}" class="search-form-wrap" style="display: flex; align-items: center; gap: 12px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa..." style="flex: 1; background: #161f33; border: 1px solid var(--glass-border); border-radius: 10px; padding: 10px 16px; color: var(--text-main); font-size: 14px; outline: none; min-width: 140px;">
            <button type="submit" style="background: var(--accent-green); color: #090d16; border: none; padding: 10px 18px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer; white-space: nowrap;">Cari</button>
            @if(request('search'))
                <a href="{{ route('hasil-tes.show', $sesiTes->id) }}" style="color: #f87171; font-size: 13px; text-decoration: none; font-weight: 600; padding: 8px 12px; background: rgba(248, 113, 113, 0.1); border-radius: 8px; border: 1px solid rgba(248, 113, 113, 0.2); white-space: nowrap;">✕ Reset</a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div style="background: var(--glass-bg); backdrop-filter: blur(16px); border: 1px solid var(--glass-border); border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);">
        <div class="ht-table-wrap">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: rgba(255, 255, 255, 0.03); border-bottom: 1px solid var(--glass-border);">
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; width: 100px;">No Absen</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Nama</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Jenis Kelamin</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Nilai Hasil</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Grade</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($siswaList as $siswa)
                    @php $h = $hasilMap->get($siswa->id); @endphp
                    <tr style="border-bottom: 1px solid var(--glass-border); {{ !$h ? 'opacity: 0.55;' : '' }}">
                        <td style="padding: 18px 24px; font-weight: 700; color: var(--accent-green);">#{{ $siswa->nomor_absen ?? '-' }}</td>
                        <td style="padding: 18px 24px; font-weight: 600; color: var(--text-main);">{{ $siswa->nama }}</td>
                        <td style="padding: 18px 24px; color: var(--text-muted);">{{ $siswa->jenis_kelamin ?? '—' }}</td>
                        <td style="padding: 18px 24px; color: var(--text-main);">
                            {{ $h ? $h->nilai_hasil : '-' }}
                        </td>
                        <td style="padding: 18px 24px;">
                            @if($h)
                                <span style="background: rgba(16, 185, 129, 0.12); color: #6ee7b7; padding: 4px 12px; border-radius: 6px; font-size: 13px; font-weight: 700;">{{ $h->grade }}</span>
                            @else
                                <span style="background: rgba(255,255,255,0.05); color: var(--text-faint, #64748b); padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 600;">Belum Tes</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 48px 24px; text-align: center; color: var(--text-muted);">Tidak ada siswa ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>

        @if ($siswaList->hasPages())
            <div style="padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; border-top: 1px solid var(--glass-border); background: rgba(0, 0, 0, 0.1);">
                <div style="font-size: 13px; color: var(--text-muted);">Halaman {{ $siswaList->currentPage() }} dari {{ $siswaList->lastPage() }}</div>
                <div style="display: flex; gap: 8px;">
                    @if ($siswaList->onFirstPage())
                        <span style="padding: 8px 14px; background: rgba(255,255,255,0.02); color: var(--text-muted); border-radius: 8px; font-size: 13px; border: 1px solid var(--glass-border); opacity: 0.5;">&laquo; Prev</span>
                    @else
                        <a href="{{ $siswaList->previousPageUrl() }}" style="padding: 8px 14px; background: rgba(255,255,255,0.05); color: var(--text-main); border-radius: 8px; font-size: 13px; border: 1px solid var(--glass-border); text-decoration: none;">&laquo; Prev</a>
                    @endif
                    @if ($siswaList->hasMorePages())
                        <a href="{{ $siswaList->nextPageUrl() }}" style="padding: 8px 14px; background: rgba(255,255,255,0.05); color: var(--text-main); border-radius: 8px; font-size: 13px; border: 1px solid var(--glass-border); text-decoration: none;">Next &raquo;</a>
                    @else
                        <span style="padding: 8px 14px; background: rgba(255,255,255,0.02); color: var(--text-muted); border-radius: 8px; font-size: 13px; border: 1px solid var(--glass-border); opacity: 0.5;">Next &raquo;</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection