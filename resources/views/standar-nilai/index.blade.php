@extends('layouts.app')

@section('title', 'Standar Penilaian')

@section('content')
<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <span style="text-transform: uppercase; font-size: 12px; font-weight: 700; color: var(--accent-green);">Panduan Penilaian Guru</span>
        <h2 style="font-size: 28px; font-weight: 800; margin: 0;">Aturan Standar Nilai</h2>
    </div>
    <button onclick="document.getElementById('modalForm').style.display='flex'" style="background: var(--accent-green); color: #090d16; border: none; padding: 12px 20px; border-radius: 12px; font-weight: 700; cursor: pointer;">
        + Buat Aturan Baru
    </button>
</div>

@if (session('success'))
    <div style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); color: #6ee7b7; padding: 14px; border-radius: 12px; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; padding: 14px; border-radius: 12px; margin-bottom: 20px;">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 20px;">
    @foreach ($standars as $s)
        @php
            $isWaktu = $s->jenisOlahraga && strtolower($s->jenisOlahraga->tipe) == 'waktu';
        @endphp
        <div style="background: var(--glass-bg); border: 1px solid var(--glass-border); border-radius: 16px; padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
                <div>
                    <h3 style="margin: 0; font-size: 18px; color: var(--text-main);">{{ $s->jenisOlahraga->nama_olahraga ?? 'Olahraga' }}</h3>
                    <div style="display: flex; gap: 8px; margin-top: 4px; align-items: center; flex-wrap: wrap;">
                        <span style="font-size: 11px; background: rgba(255,255,255,0.08); padding: 2px 8px; border-radius: 4px; color: var(--text-muted);">Khusus: <b>{{ ucfirst($s->jenis_kelamin) }}</b></span>
                        <span style="font-size: 11px; background: {{ $isWaktu ? 'rgba(59, 130, 246, 0.15)' : 'rgba(16,185,129,0.15)' }}; color: {{ $isWaktu ? '#60a5fa' : 'var(--accent-green)' }}; padding: 2px 8px; border-radius: 4px; font-weight: 600;">
                            {{ $isWaktu ? '⏱️ Tipe: Waktu' : '🎯 Tipe: Poin/Repetisi' }}
                        </span>
                        @if(!empty($s->jenisOlahraga->protokol_tes))
                            <span style="font-size: 11px; background: rgba(255,255,255,0.05); color: var(--text-muted); padding: 2px 8px; border-radius: 4px;">📏 {{ $s->jenisOlahraga->protokol_tes }}</span>
                        @endif
                    </div>
                </div>
                <form action="{{ route('standar-nilai.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus aturan nilai ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" style="background: rgba(239, 68, 68, 0.2); border: none; color: #fca5a5; padding: 6px 10px; border-radius: 6px; cursor: pointer; font-size: 12px;">Hapus</button>
                </form>
            </div>

            <table style="width: 100%; border-collapse: collapse; font-size: 13px; margin-top: 10px;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--glass-border); text-align: left; color: var(--text-muted);">
                        <th style="padding: 6px 0; width: 25%;">Predikat</th>
                        <th style="padding: 6px 0;">Ketentuan Hasil Siswa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($s->details as $d)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                            <td style="padding: 8px 0; font-weight: 700; color: var(--accent-green);">
                                Grade {{ $d->grade }}
                            </td>
                            <td style="padding: 8px 0; color: var(--text-muted);">
                                @if($isWaktu)
                                    @php
                                        $formatWaktu = function ($detik) {
                                            if ($detik === null) return null;
                                            $menit = floor($detik / 60);
                                            $sisaDetik = $detik - ($menit * 60);
                                            $sisaDetik = rtrim(rtrim(number_format($sisaDetik, 2, '.', ''), '0'), '.');
                                            return $menit > 0 ? "{$menit} menit {$sisaDetik} detik" : "{$sisaDetik} detik";
                                        };
                                    @endphp
                                    @if($d->minimal !== null && $d->maksimal !== null)
                                        Waktu <b style="color: white;">{{ $formatWaktu($d->minimal) }}</b> s.d <b style="color: white;">{{ $formatWaktu($d->maksimal) }}</b> <span style="font-size: 10px; color: #60a5fa;">(Cepat)</span>
                                    @elseif($d->minimal !== null)
                                        Waktu di atas <b style="color: white;">{{ $formatWaktu($d->minimal) }}</b>
                                    @else
                                        Waktu maksimal <b style="color: white;">{{ $formatWaktu($d->maksimal) }}</b>
                                    @endif
                                @else
                                    @if($d->minimal !== null && $d->maksimal !== null)
                                        Hasil antara <b style="color: white;">{{ rtrim(rtrim($d->minimal, '0'), '.') }}</b> s.d <b style="color: white;">{{ rtrim(rtrim($d->maksimal, '0'), '.') }}</b> poin
                                    @elseif($d->minimal !== null)
                                        Minimal <b style="color: white;">{{ rtrim(rtrim($d->minimal, '0'), '.') }}</b> poin ke atas
                                    @else
                                        Maksimal sampai <b style="color: white;">{{ rtrim(rtrim($d->maksimal, '0'), '.') }}</b> poin
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</div>

<!-- Modal Tambah Data Dinamis -->
<div id="modalForm" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); backdrop-filter: blur(6px); align-items: center; justify-content: center; z-index: 999;">
    <div style="background: #0d1322; border: 1px solid var(--glass-border); border-radius: 20px; width: 100%; max-width: 580px; padding: 24px; max-height: 90vh; overflow-y: auto;">
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

            <div id="infoPanduan" style="font-size: 13px; color: var(--accent-green); margin-bottom: 12px; font-weight: 600;">
                ℹ️ Silakan pilih cabang olahraga terlebih dahulu di atas.
            </div>

            <div id="formRentangNilai" style="display: none;">
                <h4 id="judulRentang" style="margin: 0 0 4px 0; font-size: 14px;">Aturan Penilaian</h4>
                <p id="subJudulRentang" style="font-size: 12px; color: var(--text-muted); margin-bottom: 12px;"></p>

                @php $grades = ['A', 'AB', 'B', 'BC', 'C', 'D']; @endphp
                @foreach ($grades as $index => $g)
                    <div class="grade-row" style="margin-bottom: 12px; padding: 10px; background: rgba(255,255,255,0.02); border-radius: 8px;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                            <span style="width: 35px; font-weight: 700; color: var(--accent-green);">{{ $g }}</span>
                            <span style="font-size: 11px; color: var(--text-muted);">Batas Bawah &amp; Batas Atas</span>
                        </div>

                        <input type="hidden" name="grades[{{ $index }}][grade]" value="{{ $g }}">
                        <input type="hidden" name="grades[{{ $index }}][minimal]" id="inputMin_{{ $index }}" class="hidden-min">
                        <input type="hidden" name="grades[{{ $index }}][maksimal]" id="inputMax_{{ $index }}" class="hidden-max">

                        <!-- INPUT MODE: POIN -->
                        <div class="poin-mode" data-index="{{ $index }}" style="display: none; flex-direction: column; gap: 6px;">
                            <div style="display: flex; gap: 8px; align-items: center;">
                                <input type="number" step="any" placeholder="Batas Bawah" class="poin-min" data-index="{{ $index }}" style="width: 45%; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 8px; border-radius: 6px; color: white;">
                                <label style="display:flex; align-items:center; gap:4px; font-size:11px; color:var(--text-muted); white-space:nowrap;">
                                    <input type="checkbox" class="poin-min-unlimited" data-index="{{ $index }}"> Tanpa batas bawah
                                </label>
                            </div>
                            <div style="display: flex; gap: 8px; align-items: center;">
                                <input type="number" step="any" placeholder="Batas Atas" class="poin-max" data-index="{{ $index }}" style="width: 45%; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 8px; border-radius: 6px; color: white;">
                                <label style="display:flex; align-items:center; gap:4px; font-size:11px; color:var(--text-muted); white-space:nowrap;">
                                    <input type="checkbox" class="poin-max-unlimited" data-index="{{ $index }}"> Tanpa batas atas
                                </label>
                            </div>
                        </div>

                        <!-- INPUT MODE: WAKTU -->
                        <div class="waktu-mode" data-index="{{ $index }}" style="display: none; flex-direction: column; gap: 8px;">
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <span style="font-size: 11px; color: var(--text-muted); width: 55px;">Dari</span>
                                <input type="number" min="0" placeholder="Menit" class="waktu-menit-min" data-index="{{ $index }}" style="width: 60px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 8px; border-radius: 6px; color: white;">
                                <span style="color: var(--text-muted);">:</span>
                                <input type="number" min="0" max="59" step="0.01" placeholder="Detik" class="waktu-detik-min" data-index="{{ $index }}" style="width: 70px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 8px; border-radius: 6px; color: white;">
                                <label style="display:flex; align-items:center; gap:4px; font-size:11px; color:var(--text-muted); white-space:nowrap;">
                                    <input type="checkbox" class="waktu-min-unlimited" data-index="{{ $index }}"> Tanpa batas bawah
                                </label>
                            </div>
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <span style="font-size: 11px; color: var(--text-muted); width: 55px;">Sampai</span>
                                <input type="number" min="0" placeholder="Menit" class="waktu-menit-max" data-index="{{ $index }}" style="width: 60px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 8px; border-radius: 6px; color: white;">
                                <span style="color: var(--text-muted);">:</span>
                                <input type="number" min="0" max="59" step="0.01" placeholder="Detik" class="waktu-detik-max" data-index="{{ $index }}" style="width: 70px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 8px; border-radius: 6px; color: white;">
                                <label style="display:flex; align-items:center; gap:4px; font-size:11px; color:var(--text-muted); white-space:nowrap;">
                                    <input type="checkbox" class="waktu-max-unlimited" data-index="{{ $index }}"> Tanpa batas atas
                                </label>
                            </div>
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

    const poinModes = document.querySelectorAll('.poin-mode');
    const waktuModes = document.querySelectorAll('.waktu-mode');

    if (!tipe) {
        infoPanduan.style.display = 'block';
        formRentang.style.display = 'none';
        return;
    }

    infoPanduan.style.display = 'none';
    formRentang.style.display = 'block';

    if (tipe === 'waktu') {
        judulRentang.innerText = "⏱️ Aturan Berbasis Waktu";
        subJudulRentang.innerText = "Isi waktu pakai format menit:detik. Contoh: 0:07 sampai 0:08.5 (waktu lebih cepat = nilai lebih bagus). Centang 'Tanpa batas' kalau grade ini terbuka (misal ≤ atau ≥ saja).";
        poinModes.forEach(el => el.style.display = 'none');
        waktuModes.forEach(el => el.style.display = 'flex');
    } else {
        judulRentang.innerText = "🎯 Aturan Berbasis Poin / Jumlah";
        subJudulRentang.innerText = "Masukkan jumlah perolehan hasil. Contoh: 10 sampai 20 (angka lebih besar = nilai lebih bagus). Centang 'Tanpa batas' kalau grade ini terbuka (misal ≤ atau ≥ saja).";
        poinModes.forEach(el => el.style.display = 'flex');
        waktuModes.forEach(el => el.style.display = 'none');
    }

    syncAllHiddenFields();
}

// Salin nilai poin ke hidden field, atau kosongkan kalau "Tanpa batas" dicentang
function syncPoinField(index, suffix) {
    const poinEl = document.querySelector(`.poin-${suffix}[data-index="${index}"]`);
    const unlimitedEl = document.querySelector(`.poin-${suffix}-unlimited[data-index="${index}"]`);
    const hiddenEl = document.getElementById(suffix === 'min' ? `inputMin_${index}` : `inputMax_${index}`);

    if (unlimitedEl.checked) {
        poinEl.value = '';
        poinEl.disabled = true;
        hiddenEl.value = '';
        return;
    }

    poinEl.disabled = false;
    hiddenEl.value = poinEl.value;
}

// Gabungkan menit + detik jadi total detik desimal, atau kosongkan kalau "Tanpa batas" dicentang
function syncWaktuField(index, suffix) {
    const menitEl = document.querySelector(`.waktu-menit-${suffix}[data-index="${index}"]`);
    const detikEl = document.querySelector(`.waktu-detik-${suffix}[data-index="${index}"]`);
    const unlimitedEl = document.querySelector(`.waktu-${suffix}-unlimited[data-index="${index}"]`);
    const hiddenEl = document.getElementById(suffix === 'min' ? `inputMin_${index}` : `inputMax_${index}`);

    if (unlimitedEl.checked) {
        menitEl.value = '';
        detikEl.value = '';
        menitEl.disabled = true;
        detikEl.disabled = true;
        hiddenEl.value = '';
        return;
    }

    menitEl.disabled = false;
    detikEl.disabled = false;

    const menit = parseFloat(menitEl.value) || 0;
    const detik = parseFloat(detikEl.value) || 0;

    if (menitEl.value === '' && detikEl.value === '') {
        hiddenEl.value = '';
        return;
    }

    hiddenEl.value = (menit * 60 + detik).toString();
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

    document.querySelectorAll('.poin-min-unlimited, .poin-max-unlimited').forEach(el => {
        el.addEventListener('change', () => syncPoinField(el.dataset.index, el.classList.contains('poin-min-unlimited') ? 'min' : 'max'));
    });

    document.querySelectorAll('.waktu-menit-min, .waktu-detik-min').forEach(el => {
        el.addEventListener('input', () => syncWaktuField(el.dataset.index, 'min'));
    });

    document.querySelectorAll('.waktu-menit-max, .waktu-detik-max').forEach(el => {
        el.addEventListener('input', () => syncWaktuField(el.dataset.index, 'max'));
    });

    document.querySelectorAll('.waktu-min-unlimited, .waktu-max-unlimited').forEach(el => {
        el.addEventListener('change', () => syncWaktuField(el.dataset.index, el.classList.contains('waktu-min-unlimited') ? 'min' : 'max'));
    });

    document.getElementById('standarNilaiForm').addEventListener('submit', function () {
        syncAllHiddenFields();
    });
});
</script>
@endsection