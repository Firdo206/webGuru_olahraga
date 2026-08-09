@extends('layouts.app')

@section('title', 'Sesi Tes')

@section('content')
    <style>
        .st-table-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .st-table-wrap table {
            min-width: 680px;
        }

        .modal-overlay {
            padding: 20px;
            box-sizing: border-box;
        }

        @media (max-width: 640px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 14px;
            }

            .page-header h2 {
                font-size: 22px !important;
            }

            .page-header > button {
                width: 100%;
                justify-content: center;
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
                overflow-y: auto !important;
            }
        }

        @media (max-width: 420px) {
            .waktu-row {
                flex-direction: column !important;
            }
        }
    </style>

    <!-- Page Header -->
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <span style="text-transform: uppercase; font-size: 12px; font-weight: 700; letter-spacing: 1.2px; color: var(--accent-green); display: block; margin-bottom: 4px;">Kegiatan</span>
            <h2 style="font-size: 28px; font-weight: 800; margin: 0; letter-spacing: -0.5px;">Sesi Tes</h2>
        </div>
        <button onclick="openModal()" style="background: var(--accent-green); color: #090d16; border: none; padding: 12px 20px; border-radius: 12px; font-weight: 700; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>+ Buat Sesi Tes</span>
        </button>
    </div>

    @if (session('success'))
        <div style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); color: #6ee7b7; padding: 14px 20px; border-radius: 14px; font-size: 14px; margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('warning'))
        <div style="background: rgba(251, 191, 36, 0.12); border: 1px solid rgba(251, 191, 36, 0.3); color: #fbbf24; padding: 14px 20px; border-radius: 14px; font-size: 14px; margin-bottom: 24px;">
            ⚠️ {{ session('warning') }}
        </div>
    @endif

    <!-- FILTER KELAS -->
    <div style="background: var(--glass-bg); backdrop-filter: blur(16px); border: 1px solid var(--glass-border); border-radius: 16px; padding: 16px 20px; margin-bottom: 20px; display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
        <form method="GET" action="{{ route('sesi-tes.index') }}" id="filterForm" style="display: flex; align-items: center; gap: 12px; margin: 0; flex-wrap: wrap;">
            <label style="font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Filter Kelas:</label>
            <select name="kelas_id" onchange="document.getElementById('filterForm').submit()" style="background: #161f33; border: 1px solid var(--glass-border); border-radius: 10px; padding: 10px 16px; color: var(--text-main); font-size: 14px; outline: none; min-width: 200px; cursor: pointer;">
                <option value="">-- Semua Kelas --</option>
                @foreach ($kelas as $k)
                    <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                @endforeach
            </select>
            @if(request('kelas_id'))
                <a href="{{ route('sesi-tes.index') }}" style="color: #f87171; font-size: 13px; text-decoration: none; font-weight: 600; padding: 8px 12px; background: rgba(248, 113, 113, 0.1); border-radius: 8px; border: 1px solid rgba(248, 113, 113, 0.2);">✕ Reset Filter</a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div style="background: var(--glass-bg); backdrop-filter: blur(16px); border: 1px solid var(--glass-border); border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);">
        <div class="st-table-wrap">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: rgba(255, 255, 255, 0.03); border-bottom: 1px solid var(--glass-border);">
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Tanggal &amp; Waktu</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Kelas</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Jenis Olahraga</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Status</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sesiTes as $s)
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
                            @if ($s->peringatan_standar)
                                <div style="margin-top: 6px;">
                                    <span title="{{ $s->peringatan_standar }}" style="background: rgba(251, 191, 36, 0.12); color: #fbbf24; padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; display: inline-block; cursor: help; white-space: nowrap;">
                                        ⚠️ Standar belum lengkap
                                    </span>
                                </div>
                            @endif
                        </td>
                        <td style="padding: 18px 24px; text-align: right;">
                            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px;">
                                @if ($s->status === 'aktif')
                                    <form method="POST" action="{{ route('sesi-tes.update-status', $s->id) }}" style="margin: 0;">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="selesai">
                                        <button type="submit" style="color: var(--text-muted); background: rgba(255, 255, 255, 0.06); border: 1px solid var(--glass-border); padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; white-space: nowrap;">Selesaikan</button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('sesi-tes.destroy', $s->id) }}" style="margin: 0;" onsubmit="return confirm('Hapus sesi tes ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="color: #f87171; background: rgba(248, 113, 113, 0.1); border: 1px solid rgba(248, 113, 113, 0.2); padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; white-space: nowrap;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 48px 24px; text-align: center; color: var(--text-muted);">Belum ada sesi tes.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>

        @if ($sesiTes->hasPages())
            <div style="padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; border-top: 1px solid var(--glass-border); background: rgba(0, 0, 0, 0.1);">
                <div style="font-size: 13px; color: var(--text-muted);">Halaman {{ $sesiTes->currentPage() }} dari {{ $sesiTes->lastPage() }}</div>
                <div style="display: flex; gap: 8px;">
                    @if ($sesiTes->onFirstPage())
                        <span style="padding: 8px 14px; background: rgba(255,255,255,0.02); color: var(--text-muted); border-radius: 8px; font-size: 13px; border: 1px solid var(--glass-border); opacity: 0.5;">&laquo; Prev</span>
                    @else
                        <a href="{{ $sesiTes->previousPageUrl() }}" style="padding: 8px 14px; background: rgba(255,255,255,0.05); color: var(--text-main); border-radius: 8px; font-size: 13px; border: 1px solid var(--glass-border); text-decoration: none;">&laquo; Prev</a>
                    @endif
                    @if ($sesiTes->hasMorePages())
                        <a href="{{ $sesiTes->nextPageUrl() }}" style="padding: 8px 14px; background: rgba(255,255,255,0.05); color: var(--text-main); border-radius: 8px; font-size: 13px; border: 1px solid var(--glass-border); text-decoration: none;">Next &raquo;</a>
                    @else
                        <span style="padding: 8px 14px; background: rgba(255,255,255,0.02); color: var(--text-muted); border-radius: 8px; font-size: 13px; border: 1px solid var(--glass-border); opacity: 0.5;">Next &raquo;</span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- MODAL TAMBAH SESI TES -->
    <div id="sesiModal" class="modal-overlay" style="display: none; position: fixed; inset: 0; z-index: 999; background: rgba(0,0,0,0.7); backdrop-filter: blur(6px); align-items: center; justify-content: center;">
        <div class="modal-box" style="background: #0d1322; border: 1px solid var(--glass-border); border-radius: 20px; width: 100%; max-width: 480px; padding: 28px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0; font-size: 20px; font-weight: 800; color: var(--text-main);">Buat Sesi Tes</h3>
                <button type="button" onclick="closeModal()" style="background: none; border: none; color: var(--text-muted); font-size: 20px; cursor: pointer;">&times;</button>
            </div>

            <form method="POST" action="{{ route('sesi-tes.store') }}">
                @csrf

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 8px;">KELAS</label>
                    <select name="kelas_id" id="modalKelas" required style="width: 100%; background: #161f33; border: 1px solid var(--glass-border); border-radius: 10px; padding: 12px 16px; color: var(--text-main); font-size: 14px; outline: none; box-sizing: border-box;">
                        <option value="" disabled selected>-- Pilih Kelas --</option>
                        @foreach ($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 8px;">JENIS OLAHRAGA</label>
                    <select name="jenis_olahraga_id" id="modalJenisOlahraga" required style="width: 100%; background: #161f33; border: 1px solid var(--glass-border); border-radius: 10px; padding: 12px 16px; color: var(--text-main); font-size: 14px; outline: none; box-sizing: border-box;">
                        <option value="" disabled selected>-- Pilih Jenis Olahraga --</option>
                        @foreach ($olahragas as $o)
                            <option value="{{ $o->id }}">{{ $o->nama_olahraga }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="peringatanStandar" style="display: none; background: rgba(251, 191, 36, 0.1); border: 1px solid rgba(251, 191, 36, 0.25); color: #fbbf24; padding: 12px 14px; border-radius: 10px; font-size: 12.5px; margin-bottom: 18px; line-height: 1.5;"></div>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 8px;">TANGGAL</label>
                    <input type="date" name="tanggal" min="{{ date('Y-m-d') }}" required style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 10px; padding: 12px 16px; color: var(--text-main); font-size: 14px; outline: none; box-sizing: border-box;">
                </div>

                <div class="waktu-row" style="display: flex; gap: 12px; margin-bottom: 24px;">
                    <div style="flex: 1;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 8px;">WAKTU MULAI</label>
                        <input type="time" name="waktu_mulai" required style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 10px; padding: 12px 16px; color: var(--text-main); font-size: 14px; outline: none; box-sizing: border-box;">
                    </div>
                    <div style="flex: 1;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 8px;">WAKTU BERAKHIR</label>
                        <input type="time" name="waktu_berakhir" required style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 10px; padding: 12px 16px; color: var(--text-main); font-size: 14px; outline: none; box-sizing: border-box;">
                    </div>
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button type="button" onclick="closeModal()" style="background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); color: var(--text-muted); padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: 14px; cursor: pointer;">Batal</button>
                    <button type="submit" style="background: var(--accent-green); border: none; color: #090d16; padding: 10px 20px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer;">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const sesiModal = document.getElementById('sesiModal');
        function openModal() { sesiModal.style.display = 'flex'; }
        function closeModal() { sesiModal.style.display = 'none'; }
        window.addEventListener('click', (e) => { if (e.target === sesiModal) closeModal(); });

        // Cek kelengkapan standar nilai secara real-time saat kelas/olahraga dipilih
        const modalKelas = document.getElementById('modalKelas');
        const modalJenisOlahraga = document.getElementById('modalJenisOlahraga');
        const peringatanStandar = document.getElementById('peringatanStandar');

        async function cekKelengkapanStandar() {
            const kelasId = modalKelas.value;
            const olahragaId = modalJenisOlahraga.value;

            if (!kelasId || !olahragaId) {
                peringatanStandar.style.display = 'none';
                return;
            }

            try {
                const url = `{{ route('sesi-tes.cek-standar') }}?kelas_id=${kelasId}&jenis_olahraga_id=${olahragaId}`;
                const res = await fetch(url, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();

                if (!data.lengkap) {
                    peringatanStandar.innerText = '⚠️ ' + data.pesan;
                    peringatanStandar.style.display = 'block';
                } else {
                    peringatanStandar.style.display = 'none';
                }
            } catch (e) {
                peringatanStandar.style.display = 'none';
            }
        }

        modalKelas.addEventListener('change', cekKelengkapanStandar);
        modalJenisOlahraga.addEventListener('change', cekKelengkapanStandar);
    </script>
@endsection