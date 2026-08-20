@extends('layouts.app')

@section('title', 'Kelola Siswa')

@section('content')
    <!-- Page Header -->
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <span style="text-transform: uppercase; font-size: 12px; font-weight: 700; letter-spacing: 1.2px; color: var(--accent-green); display: block; margin-bottom: 4px;">Data Master</span>
            <h2 style="font-size: 28px; font-weight: 800; margin: 0; letter-spacing: -0.5px;">Kelola Siswa</h2>
        </div>
       <div style="display: flex; gap: 10px;">
    <button onclick="openImportModal()" style="background: rgba(255, 255, 255, 0.05); border: 1px solid var(--glass-border); color: var(--text-main); padding: 12px 20px; border-radius: 12px; font-weight: 700; font-size: 14px; cursor: pointer;">
        📥 Import Excel
    </button>
    <button onclick="openModal('create')" style="background: var(--accent-green); color: #090d16; border: none; padding: 12px 20px; border-radius: 12px; font-weight: 700; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        <span>+ Tambah Siswa</span>
    </button>
</div>
    </div>

    <!-- Alert Success -->
    @if (session('success'))
    <div style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); color: #6ee7b7; padding: 14px 20px; border-radius: 14px; font-size: 14px; margin-bottom: 24px;">
        {{ session('success') }}
    </div>
@endif

@if (session('warning'))
    <div style="background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.3); color: #fbbf24; padding: 14px 20px; border-radius: 14px; font-size: 14px; margin-bottom: 12px;">
        {{ session('warning') }}
    </div>
@endif

@if (session('import_skipped') && count(session('import_skipped')) > 0)
    <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--glass-border); border-radius: 14px; padding: 16px 20px; margin-bottom: 24px; font-size: 13px;">
        <strong style="color: var(--text-main); display:block; margin-bottom: 8px;">Detail baris yang dilewati:</strong>
        <ul style="margin: 0; padding-left: 18px; color: var(--text-muted);">
            @foreach (session('import_skipped') as $msg)
                <li>{{ $msg }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <!-- BAR FILTER KELAS -->
    <div style="background: var(--glass-bg); backdrop-filter: blur(16px); border: 1px solid var(--glass-border); border-radius: 16px; padding: 16px 20px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
        <form method="GET" action="{{ route('siswa.index') }}" id="filterForm" style="display: flex; align-items: center; gap: 12px; margin: 0; flex: 1;">
            <label style="font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Filter Kelas:</label>
            <select name="kelas_id" onchange="document.getElementById('filterForm').submit()" style="background: #161f33; border: 1px solid var(--glass-border); border-radius: 10px; padding: 10px 16px; color: var(--text-main); font-size: 14px; outline: none; min-width: 200px; cursor: pointer;">
                <option value="">-- Semua Kelas --</option>
                @foreach ($kelas as $k)
                    <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_kelas }}
                    </option>
                @endforeach
            </select>

            @if(request('kelas_id'))
                <a href="{{ route('siswa.index') }}" style="color: #f87171; font-size: 13px; text-decoration: none; font-weight: 600; padding: 8px 12px; background: rgba(248, 113, 113, 0.1); border-radius: 8px; border: 1px solid rgba(248, 113, 113, 0.2);">
                    ✕ Reset Filter
                </a>
            @endif
        </form>

        <div style="font-size: 13px; color: var(--text-muted);">
            Total Data: <strong style="color: var(--accent-green);">{{ $siswa->total() }}</strong> Siswa
        </div>
    </div>

    <!-- BAR AKSI BULK -->
    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 12px; flex-wrap: wrap;">
        <div style="font-size: 13px; color: var(--text-muted);">
            <span id="selectedCount">0</span> siswa dipilih
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="button" onclick="submitBulkDestroy()" id="btnHapusTerpilih" disabled style="background: rgba(248, 113, 113, 0.1); border: 1px solid rgba(248, 113, 113, 0.2); color: #f87171; padding: 9px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; opacity: 0.5;">
                Hapus Terpilih
            </button>
            <button type="button" onclick="submitDestroyAll()" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; padding: 9px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                Hapus Semua{{ request('kelas_id') ? ' (Kelas Ini)' : '' }}
            </button>
        </div>
    </div>

    <!-- Table Container -->
    <div style="background: var(--glass-bg); backdrop-filter: blur(16px); border: 1px solid var(--glass-border); border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: rgba(255, 255, 255, 0.03); border-bottom: 1px solid var(--glass-border);">
                    <th style="padding: 18px 16px; width: 40px;">
                        <input type="checkbox" id="checkAll" onclick="toggleAll(this)" style="width: 16px; height: 16px; cursor: pointer;">
                    </th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; width: 100px;">NO ABSEN</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">NAMA LENGKAP</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">JENIS KELAMIN</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">KELAS</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; text-align: right; width: 180px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($siswa as $s)
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 18px 16px;">
                            <input type="checkbox" class="rowCheck" value="{{ $s->id }}" onclick="updateSelectedCount()" style="width: 16px; height: 16px; cursor: pointer;">
                        </td>
                        <td style="padding: 18px 24px; font-weight: 700; color: var(--accent-green);">
                            #{{ $s->nomor_absen }}
                        </td>
                        <td style="padding: 18px 24px; font-weight: 600; color: var(--text-main);">
                            {{ $s->nama }}
                        </td>
                        <td style="padding: 18px 24px; color: var(--text-muted);">
                            <span style="background: {{ $s->jenis_kelamin === 'Laki-Laki' ? 'rgba(56, 189, 248, 0.1)' : 'rgba(244, 114, 182, 0.1)' }}; color: {{ $s->jenis_kelamin === 'Laki-Laki' ? '#38bdf8' : '#f472b6' }}; padding: 4px 10px; border-radius: 6px; font-size: 13px; font-weight: 600;">
                                {{ $s->jenis_kelamin ?? '—' }}
                            </span>
                        </td>
                        <td style="padding: 18px 24px; color: var(--text-muted);">
                            <span style="background: rgba(255, 255, 255, 0.05); border: 1px solid var(--glass-border); padding: 4px 10px; border-radius: 6px; font-size: 13px;">
                                {{ $s->kelas->nama_kelas ?? 'Tanpa Kelas' }}
                            </span>
                        </td>
                        <td style="padding: 18px 24px; text-align: right;">
                            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px;">
                                <button type="button" onclick="openEditModal({{ $s->id }}, {{ Js::from($s->nama) }}, {{ Js::from($s->nomor_absen) }}, {{ Js::from($s->kelas_id) }}, {{ Js::from($s->jenis_kelamin) }})" style="color: #38bdf8; background: rgba(56, 189, 248, 0.1); border: 1px solid rgba(56, 189, 248, 0.2); padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                                    Edit
                                </button>

                                <form method="POST" action="{{ route('siswa.destroy', $s->id) }}" style="margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini?')">
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
                        <td colspan="6" style="padding: 48px 24px; text-align: center; color: var(--text-muted);">
                            <p style="margin: 0; font-size: 15px;">Data siswa tidak ditemukan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- PAGINATION NEXT/PREV -->
        @if ($siswa->hasPages())
            <div style="padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--glass-border); background: rgba(0, 0, 0, 0.1);">
                <div style="font-size: 13px; color: var(--text-muted);">
                    Halaman {{ $siswa->currentPage() }} dari {{ $siswa->lastPage() }}
                </div>
                <div style="display: flex; gap: 8px;">
                    @if ($siswa->onFirstPage())
                        <span style="padding: 8px 14px; background: rgba(255, 255, 255, 0.02); color: var(--text-muted); border-radius: 8px; font-size: 13px; border: 1px solid var(--glass-border); opacity: 0.5; cursor: not-allowed;">&laquo; Prev</span>
                    @else
                        <a href="{{ $siswa->previousPageUrl() }}" style="padding: 8px 14px; background: rgba(255, 255, 255, 0.05); color: var(--text-main); border-radius: 8px; font-size: 13px; border: 1px solid var(--glass-border); text-decoration: none;">&laquo; Prev</a>
                    @endif

                    @if ($siswa->hasMorePages())
                        <a href="{{ $siswa->nextPageUrl() }}" style="padding: 8px 14px; background: rgba(255, 255, 255, 0.05); color: var(--text-main); border-radius: 8px; font-size: 13px; border: 1px solid var(--glass-border); text-decoration: none;">Next &raquo;</a>
                    @else
                        <span style="padding: 8px 14px; background: rgba(255, 255, 255, 0.02); color: var(--text-muted); border-radius: 8px; font-size: 13px; border: 1px solid var(--glass-border); opacity: 0.5; cursor: not-allowed;">Next &raquo;</span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- FORM TERSEMBUNYI BUAT BULK DESTROY -->
    <form id="bulkDestroyForm" method="POST" action="{{ route('siswa.bulk-destroy') }}" style="display: none;">
        @csrf
        <div id="bulkDestroyIds"></div>
    </form>

    <!-- FORM TERSEMBUNYI BUAT DESTROY ALL -->
    <form id="destroyAllForm" method="POST" action="{{ route('siswa.destroy-all') }}" style="display: none;">
        @csrf
        <input type="hidden" name="kelas_id" value="{{ request('kelas_id') }}">
    </form>

    <!-- POPUP MODAL (TAMBAH / EDIT SISWA) -->
    <div id="siswaModal" style="display: none; position: fixed; inset: 0; z-index: 999; background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(6px); align-items: center; justify-content: center;">
        <div style="background: #0d1322; border: 1px solid var(--glass-border); border-radius: 20px; width: 100%; max-width: 480px; padding: 28px; position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 id="modalTitle" style="margin: 0; font-size: 20px; font-weight: 800; color: var(--text-main);">Tambah Siswa</h3>
                <button type="button" onclick="closeModal()" style="background: none; border: none; color: var(--text-muted); font-size: 20px; cursor: pointer;">&times;</button>
            </div>

            <form id="siswaForm" method="POST" action="{{ route('siswa.store') }}">
                @csrf
                <input type="hidden" id="methodField" name="_method" value="POST">

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 8px;">KELAS</label>
                    <select id="kelas_id" name="kelas_id" required style="width: 100%; background: #161f33; border: 1px solid var(--glass-border); border-radius: 10px; padding: 12px 16px; color: var(--text-main); font-size: 14px; outline: none; box-sizing: border-box;">
                        <option value="" disabled selected>-- Pilih Kelas --</option>
                        @foreach ($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 8px;">NAMA LENGKAP</label>
                    <input type="text" id="nama" name="nama" placeholder="Contoh: Budi Santoso" required style="width: 100%; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--glass-border); border-radius: 10px; padding: 12px 16px; color: var(--text-main); font-size: 14px; outline: none; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 8px;">JENIS KELAMIN</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" required style="width: 100%; background: #161f33; border: 1px solid var(--glass-border); border-radius: 10px; padding: 12px 16px; color: var(--text-main); font-size: 14px; outline: none; box-sizing: border-box;">
                        <option value="Laki-Laki">Laki-Laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 8px;">NOMOR ABSEN</label>
                    <input type="number" id="nomor_absen" name="nomor_absen" min="1" placeholder="Contoh: 1" required style="width: 100%; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--glass-border); border-radius: 10px; padding: 12px 16px; color: var(--text-main); font-size: 14px; outline: none; box-sizing: border-box;">
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button type="button" onclick="closeModal()" style="background: rgba(255, 255, 255, 0.05); border: 1px solid var(--glass-border); color: var(--text-muted); padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: 14px; cursor: pointer;">Batal</button>
                    <button type="submit" style="background: var(--accent-green); border: none; color: #090d16; padding: 10px 20px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer;">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- POPUP MODAL IMPORT EXCEL -->
    <div id="importModal" style="display: none; position: fixed; inset: 0; z-index: 999; background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(6px); align-items: center; justify-content: center;">
        <div style="background: #0d1322; border: 1px solid var(--glass-border); border-radius: 20px; width: 100%; max-width: 480px; padding: 28px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0; font-size: 20px; font-weight: 800; color: var(--text-main);">Import Siswa dari Excel</h3>
                <button type="button" onclick="closeImportModal()" style="background: none; border: none; color: var(--text-muted); font-size: 20px; cursor: pointer;">&times;</button>
            </div>

            <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">
                File harus punya kolom: <strong>nomor_absen</strong>, <strong>nama</strong>, <strong>jenis_kelamin</strong>.
                <a href="{{ route('siswa.template') }}" style="color: var(--accent-green);">Download template →</a>
            </p>

            <form method="POST" action="{{ route('siswa.import') }}" enctype="multipart/form-data">
                @csrf

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 8px;">KELAS TUJUAN</label>
                    <select name="kelas_id" required style="width: 100%; background: #161f33; border: 1px solid var(--glass-border); border-radius: 10px; padding: 12px 16px; color: var(--text-main); font-size: 14px; outline: none; box-sizing: border-box;">
                        <option value="" disabled selected>-- Pilih Kelas --</option>
                        @foreach ($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 8px;">FILE EXCEL</label>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required style="width: 100%; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--glass-border); border-radius: 10px; padding: 12px 16px; color: var(--text-main); font-size: 13px; outline: none; box-sizing: border-box;">
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button type="button" onclick="closeImportModal()" style="background: rgba(255, 255, 255, 0.05); border: 1px solid var(--glass-border); color: var(--text-muted); padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: 14px; cursor: pointer;">Batal</button>
                    <button type="submit" style="background: var(--accent-green); border: none; color: #090d16; padding: 10px 20px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer;">Import</button>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPT POPUP MODAL -->
    <script>
        (function () {
            const modal = document.getElementById('siswaModal');
            const form = document.getElementById('siswaForm');
            const modalTitle = document.getElementById('modalTitle');
            const methodField = document.getElementById('methodField');
            const namaInput = document.getElementById('nama');
            const jenisKelaminInput = document.getElementById('jenis_kelamin');
            const nomorAbsenInput = document.getElementById('nomor_absen');
            const kelasSelect = document.getElementById('kelas_id');
            const importModal = document.getElementById('importModal');

            window.openModal = function (mode) {
                modal.style.display = 'flex';
                if (mode === 'create') {
                    modalTitle.innerText = 'Tambah Siswa Baru';
                    form.action = "{{ route('siswa.store') }}";
                    methodField.value = 'POST';
                    namaInput.value = '';
                    jenisKelaminInput.value = 'Laki-Laki';
                    nomorAbsenInput.value = '';
                    if (kelasSelect) kelasSelect.selectedIndex = 0;
                }
            };

            window.openEditModal = function (id, nama, nomorAbsen, kelasId, jenisKelamin) {
                modal.style.display = 'flex';
                modalTitle.innerText = 'Edit Data Siswa';
                form.action = `/siswa/${id}`;
                methodField.value = 'PUT';
                namaInput.value = nama;
                jenisKelaminInput.value = jenisKelamin ?? 'Laki-Laki';
                nomorAbsenInput.value = nomorAbsen;
                if (kelasSelect) kelasSelect.value = kelasId;
            };

            window.closeModal = function () {
                modal.style.display = 'none';
            };

            window.openImportModal = function () {
                importModal.style.display = 'flex';
            };

            window.closeImportModal = function () {
                importModal.style.display = 'none';
            };

            window.addEventListener('click', function (event) {
                if (event.target === modal) {
                    closeModal();
                }
                if (event.target === importModal) {
                    closeImportModal();
                }
            });
        })();

        // ==== BULK SELECT & DELETE ====
        function toggleAll(source) {
            document.querySelectorAll('.rowCheck').forEach(cb => cb.checked = source.checked);
            updateSelectedCount();
        }

        function updateSelectedCount() {
            const checked = document.querySelectorAll('.rowCheck:checked');
            document.getElementById('selectedCount').innerText = checked.length;

            const btn = document.getElementById('btnHapusTerpilih');
            if (checked.length > 0) {
                btn.disabled = false;
                btn.style.opacity = '1';
            } else {
                btn.disabled = true;
                btn.style.opacity = '0.5';
            }

            const checkAll = document.getElementById('checkAll');
            const allRows = document.querySelectorAll('.rowCheck');
            checkAll.checked = allRows.length > 0 && checked.length === allRows.length;
        }

        function submitBulkDestroy() {
            const checked = document.querySelectorAll('.rowCheck:checked');
            if (checked.length === 0) return;

            if (!confirm(`Hapus ${checked.length} siswa terpilih? Akun login mereka juga ikut terhapus.`)) return;

            const container = document.getElementById('bulkDestroyIds');
            container.innerHTML = '';
            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                container.appendChild(input);
            });

            document.getElementById('bulkDestroyForm').submit();
        }

        function submitDestroyAll() {
            const totalText = {{ $siswa->total() }};
            if (!confirm(`Yakin mau hapus SEMUA ${totalText} siswa {{ request('kelas_id') ? 'di kelas ini' : '' }}? Aksi ini tidak bisa dibatalkan, dan akun login mereka juga ikut terhapus.`)) return;
            document.getElementById('destroyAllForm').submit();
        }
    </script>
@endsection