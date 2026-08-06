@extends('layouts.app')

@section('title', 'Jenis Olahraga')

@section('content')
    <!-- Page Header -->
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <span style="text-transform: uppercase; font-size: 12px; font-weight: 700; letter-spacing: 1.2px; color: var(--accent-green); display: block; margin-bottom: 4px;">Data Master</span>
            <h2 style="font-size: 28px; font-weight: 800; margin: 0; letter-spacing: -0.5px;">Jenis Olahraga</h2>
        </div>
        <button onclick="openJenisOlahragaModal('create')" style="background: var(--accent-green); color: #090d16; border: none; padding: 12px 20px; border-radius: 12px; font-weight: 700; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>+ Tambah Jenis Olahraga</span>
        </button>
    </div>

    <!-- Alert Success -->
    @if (session('success'))
        <div style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); color: #6ee7b7; padding: 14px 20px; border-radius: 14px; font-size: 14px; margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; padding: 14px 20px; border-radius: 14px; font-size: 14px; margin-bottom: 24px;">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <!-- Table Container -->
    <div style="background: var(--glass-bg); backdrop-filter: blur(16px); border: 1px solid var(--glass-border); border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: rgba(255, 255, 255, 0.03); border-bottom: 1px solid var(--glass-border);">
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">NAMA OLAHRAGA</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">TIPE PENILAIAN</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">PROTOKOL TES</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">DESKRIPSI</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; text-align: right; width: 180px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jenisOlahraga as $j)
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 18px 24px; font-weight: 700; color: var(--text-main);">
                            {{ $j->nama_olahraga }}
                        </td>
                        <td style="padding: 18px 24px;">
                            @if(($j->tipe ?? 'poin') === 'waktu')
                                <span style="font-size: 11px; background: rgba(56, 189, 248, 0.15); color: #38bdf8; padding: 4px 10px; border-radius: 6px; font-weight: 600;">⏱️ Waktu / Kecepatan</span>
                            @else
                                <span style="font-size: 11px; background: rgba(16, 185, 129, 0.15); color: var(--accent-green); padding: 4px 10px; border-radius: 6px; font-weight: 600;">🔢 Poin / Repetisi</span>
                            @endif
                        </td>
                        <td style="padding: 18px 24px; color: var(--text-muted);">
                            {{ $j->protokol_tes ?: '—' }}
                        </td>
                        <td style="padding: 18px 24px; color: var(--text-muted);">
                            {{ $j->deskripsi ?: '—' }}
                        </td>
                        <td style="padding: 18px 24px; text-align: right;">
                            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px;">
                                <button type="button" onclick="openJenisOlahragaEditModal({{ $j->id }}, {{ Js::from($j->nama_olahraga) }}, {{ Js::from($j->tipe ?? 'poin') }}, {{ Js::from($j->protokol_tes) }}, {{ Js::from($j->deskripsi) }})" style="color: #38bdf8; background: rgba(56, 189, 248, 0.1); border: 1px solid rgba(56, 189, 248, 0.2); padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                                    Edit
                                </button>

                                <form method="POST" action="{{ route('jenis-olahraga.destroy', $j->id) }}" style="margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis olahraga ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="color: #f87171; background: rgba(248, 113, 113, 0.1); border: 1px solid rgba(248, 113, 113, 0.2); padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 48px 24px; text-align: center; color: var(--text-muted);">
                            <p style="margin: 0; font-size: 15px;">Belum ada jenis olahraga.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- POPUP MODAL (TAMBAH / EDIT JENIS OLAHRAGA) -->
    <div id="jenisOlahragaModal" style="display: none; position: fixed; inset: 0; z-index: 999; background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(6px); align-items: center; justify-content: center;">
        <div style="background: #0d1322; border: 1px solid var(--glass-border); border-radius: 20px; width: 100%; max-width: 480px; padding: 28px; position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 id="modalTitle" style="margin: 0; font-size: 20px; font-weight: 800; color: var(--text-main);">Tambah Jenis Olahraga</h3>
                <button type="button" onclick="closeJenisOlahragaModal()" style="background: none; border: none; color: var(--text-muted); font-size: 20px; cursor: pointer;">&times;</button>
            </div>

            <form id="jenisOlahragaForm" method="POST" action="{{ route('jenis-olahraga.store') }}">
                @csrf
                <input type="hidden" id="methodField" name="_method" value="POST">

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 8px;">NAMA OLAHRAGA</label>
                    <input type="text" id="nama_olahraga" name="nama_olahraga" placeholder="Contoh: Lari 10 KM / Push Up" required style="width: 100%; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--glass-border); border-radius: 10px; padding: 12px 16px; color: var(--text-main); font-size: 14px; outline: none; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 8px;">TIPE PENILAIAN</label>
                    <select id="tipe" name="tipe" onchange="ubahLabelProtokol()" required style="width: 100%; background: #161f33; border: 1px solid var(--glass-border); border-radius: 10px; padding: 12px 16px; color: var(--text-main); font-size: 14px; outline: none; box-sizing: border-box;">
                        <option value="poin">🔢 Berbasis Poin / Repetisi (Contoh: Push Up, Sit Up)</option>
                        <option value="waktu">⏱️ Berbasis Waktu / Kecepatan (Contoh: Lari, Renang)</option>
                    </select>
                </div>

                <div style="margin-bottom: 18px;">
                    <label id="labelProtokol" style="display: block; font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 8px;">JARAK TETAP (KARENA TIPE WAKTU)</label>
                    <input type="text" id="protokol_tes" name="protokol_tes" placeholder="Contoh: Jarak tempuh 10 km" style="width: 100%; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--glass-border); border-radius: 10px; padding: 12px 16px; color: var(--text-main); font-size: 14px; outline: none; box-sizing: border-box;">
                    <p id="hintProtokol" style="font-size: 12px; color: var(--text-muted); margin-top: 6px; margin-bottom: 0;">Ini aturan tetap tes (bukan yang dinilai) — jarak yang sama buat semua siswa.</p>
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 8px;">DESKRIPSI (OPSIONAL)</label>
                    <input type="text" id="deskripsi" name="deskripsi" placeholder="Keterangan singkat" style="width: 100%; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--glass-border); border-radius: 10px; padding: 12px 16px; color: var(--text-main); font-size: 14px; outline: none; box-sizing: border-box;">
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button type="button" onclick="closeJenisOlahragaModal()" style="background: rgba(255, 255, 255, 0.05); border: 1px solid var(--glass-border); color: var(--text-muted); padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: 14px; cursor: pointer;">Batal</button>
                    <button type="submit" style="background: var(--accent-green); border: none; color: #090d16; padding: 10px 20px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer;">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPT POPUP MODAL -->
    <script>
        (function () {
            const jenisOlahragaModal = document.getElementById('jenisOlahragaModal');
            const jenisOlahragaForm = document.getElementById('jenisOlahragaForm');
            const jenisOlahragaModalTitle = document.getElementById('modalTitle');
            const jenisOlahragaMethodField = document.getElementById('methodField');
            const jenisOlahragaNamaInput = document.getElementById('nama_olahraga');
            const jenisOlahragaTipeInput = document.getElementById('tipe');
            const jenisOlahragaProtokolInput = document.getElementById('protokol_tes');
            const jenisOlahragaDeskripsiInput = document.getElementById('deskripsi');
            const jenisOlahragaLabelProtokol = document.getElementById('labelProtokol');
            const jenisOlahragaHintProtokol = document.getElementById('hintProtokol');

            window.ubahLabelProtokol = function () {
                if (jenisOlahragaTipeInput.value === 'waktu') {
                    jenisOlahragaLabelProtokol.innerText = 'JARAK TETAP (KARENA TIPE WAKTU)';
                    jenisOlahragaProtokolInput.placeholder = 'Contoh: Jarak tempuh 10 km';
                    jenisOlahragaHintProtokol.innerText = 'Ini aturan tetap tes (bukan yang dinilai) — jarak yang sama buat semua siswa.';
                } else {
                    jenisOlahragaLabelProtokol.innerText = 'DURASI TETAP (KARENA TIPE POIN)';
                    jenisOlahragaProtokolInput.placeholder = 'Contoh: Durasi tes 60 detik';
                    jenisOlahragaHintProtokol.innerText = 'Ini aturan tetap tes (bukan yang dinilai) — durasi yang sama buat semua siswa.';
                }
            };

            window.openJenisOlahragaModal = function (mode) {
                jenisOlahragaModal.style.display = 'flex';
                if (mode === 'create') {
                    jenisOlahragaModalTitle.innerText = 'Tambah Jenis Olahraga';
                    jenisOlahragaForm.action = "{{ route('jenis-olahraga.store') }}";
                    jenisOlahragaMethodField.value = 'POST';
                    jenisOlahragaNamaInput.value = '';
                    jenisOlahragaTipeInput.value = 'poin';
                    jenisOlahragaProtokolInput.value = '';
                    jenisOlahragaDeskripsiInput.value = '';
                    ubahLabelProtokol();
                }
            };

            window.openJenisOlahragaEditModal = function (id, nama, tipe, protokol, deskripsi) {
                jenisOlahragaModal.style.display = 'flex';
                jenisOlahragaModalTitle.innerText = 'Edit Jenis Olahraga';
                jenisOlahragaForm.action = `/jenis-olahraga/${id}`;
                jenisOlahragaMethodField.value = 'PUT';
                jenisOlahragaNamaInput.value = nama;
                jenisOlahragaTipeInput.value = tipe ?? 'poin';
                jenisOlahragaProtokolInput.value = protokol ?? '';
                jenisOlahragaDeskripsiInput.value = deskripsi ?? '';
                ubahLabelProtokol();
            };

            window.closeJenisOlahragaModal = function () {
                jenisOlahragaModal.style.display = 'none';
            };

            window.addEventListener('click', function (event) {
                if (event.target === jenisOlahragaModal) {
                    closeJenisOlahragaModal();
                }
            });
        })();
    </script>
@endsection