@extends('layouts.app')

@section('title', 'Detail Hasil Tes')

@section('content')
    <div class="page-header" style="margin-bottom: 24px;">
        <a href="{{ route('hasil-tes.index') }}" style="color: var(--text-muted); font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">&larr; Kembali ke Riwayat</a>
        <h2 style="font-size: 28px; font-weight: 800; margin: 0 0 6px 0; letter-spacing: -0.5px;">
            {{ $sesiTes->jenisOlahraga->nama_olahraga ?? '—' }} — {{ $sesiTes->kelas->nama_kelas ?? '—' }}
        </h2>
        <p style="margin: 0; color: var(--text-muted); font-size: 14px;">
            {{ \Carbon\Carbon::parse($sesiTes->tanggal)->format('d M Y') }} • {{ \Carbon\Carbon::parse($sesiTes->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($sesiTes->waktu_berakhir)->format('H:i') }}
        </p>
    </div>

    <!-- SEARCH -->
    <div style="background: var(--glass-bg); backdrop-filter: blur(16px); border: 1px solid var(--glass-border); border-radius: 16px; padding: 16px 20px; margin-bottom: 20px;">
        <form method="GET" action="{{ route('hasil-tes.show', $sesiTes->id) }}" style="display: flex; align-items: center; gap: 12px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa..." style="flex: 1; background: #161f33; border: 1px solid var(--glass-border); border-radius: 10px; padding: 10px 16px; color: var(--text-main); font-size: 14px; outline: none;">
            <button type="submit" style="background: var(--accent-green); color: #090d16; border: none; padding: 10px 18px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer;">Cari</button>
            @if(request('search'))
                <a href="{{ route('hasil-tes.show', $sesiTes->id) }}" style="color: #f87171; font-size: 13px; text-decoration: none; font-weight: 600; padding: 8px 12px; background: rgba(248, 113, 113, 0.1); border-radius: 8px; border: 1px solid rgba(248, 113, 113, 0.2);">✕ Reset</a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div style="background: var(--glass-bg); backdrop-filter: blur(16px); border: 1px solid var(--glass-border); border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);">
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
                @forelse ($hasil as $h)
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 18px 24px; font-weight: 700; color: var(--accent-green);">#{{ $h->siswa->nomor_absen ?? '-' }}</td>
                        <td style="padding: 18px 24px; font-weight: 600; color: var(--text-main);">{{ $h->siswa->nama ?? '—' }}</td>
                        <td style="padding: 18px 24px; color: var(--text-muted);">{{ $h->siswa->jenis_kelamin ?? '—' }}</td>
                        <td style="padding: 18px 24px; color: var(--text-main);">{{ $h->nilai_hasil }}</td>
                        <td style="padding: 18px 24px;">
                            <span style="background: rgba(16, 185, 129, 0.12); color: #6ee7b7; padding: 4px 12px; border-radius: 6px; font-size: 13px; font-weight: 700;">{{ $h->grade }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 48px 24px; text-align: center; color: var(--text-muted);">Belum ada siswa yang mengirim hasil tes untuk sesi ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection