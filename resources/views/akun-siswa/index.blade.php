@extends('layouts.app')

@section('title', 'Kelola Akun Siswa')

@section('content')
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <span style="text-transform: uppercase; font-size: 12px; font-weight: 700; letter-spacing: 1.2px; color: var(--accent-green); display: block; margin-bottom: 4px;">Kelola Akses</span>
            <h2 style="font-size: 28px; font-weight: 800; margin: 0; letter-spacing: -0.5px;">Akun Siswa</h2>
        </div>
        <button onclick="openModal('create')" style="background: var(--accent-green); color: #090d16; border: none; padding: 12px 20px; border-radius: 12px; font-weight: 700; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>+ Buat Akun Siswa</span>
        </button>
    </div>

    @if (session('success'))
        <div style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); color: #6ee7b7; padding: 14px 20px; border-radius: 14px; font-size: 14px; margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- KARTU: BUAT AKUN SEKELAS -->
    <div style="background: var(--glass-bg); backdrop-filter: blur(16px); border: 1px solid var(--glass-border); border-radius: 16px; padding: 20px; margin-bottom: 20px;">
        <div style="font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px;">Buat Akun Sekelas</div>
        <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
            <select id="kelasAksiSelect" style="background: #161f33; border: 1px solid var(--glass-border); border-radius: 10px; padding: 10px 16px; color: var(--text-main); font-size: 14px; min-width: 200px;">
                <option value="" disabled selected>-- Pilih Kelas --</option>
                @foreach ($kelas as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                @endforeach
            </select>

            <button type="button" onclick="submitBulkCreate()" style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); color: #6ee7b7; padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                Buat Akun Sekelas
            </button>
        </div>
        <p style="margin: 10px 0 0 0; font-size: 12px; color: var(--text-muted);">
            Cuma bikin akun buat siswa yang belum punya akun di kelas itu — yang sudah punya akun tidak akan ditimpa.
        </p>
    </div>

    <!-- BARIS FILTER & SEARCH -->
    <div style="background: var(--glass-bg); backdrop-filter: blur(16px); border: 1px solid var(--glass-border); border-radius: 16px; padding: 16px 20px; margin-bottom: 24px;">
        <form method="GET" action="{{ route('akun-siswa.index') }}" style="display: flex; gap: 16px; flex-wrap: wrap; align-items: center; justify-content: space-between;">
            <div style="display: flex; gap: 12px; flex: 1; min-width: 280px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa..." style="flex: 1; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--glass-border); border-radius: 10px; padding: 10px 16px; color: var(--text-main); font-size: 14px;">

                <select name="kelas_id" id="filterKelasSelect" onchange="this.form.submit()" style="background: #161f33; border: 1px solid var(--glass-border); border-radius: 10px; padding: 10px 16px; color: var(--text-main); font-size: 14px; min-width: 160px;">
                    <option value="">Semua Kelas</option>
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="button" onclick="downloadExcel()" style="background: rgba(56, 189, 248, 0.1); border: 1px solid rgba(56, 189, 248, 0.2); color: #38bdf8; padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    Download Excel
                </button>
                @if (request('search') || request('kelas_id'))
                    <a href="{{ route('akun-siswa.index') }}" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; padding: 10px 16px; border-radius: 10px; font-weight: 600; text-decoration: none; font-size: 14px; display: inline-flex; align-items: center;">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- TABEL AKUN SISWA -->
    <div style="background: var(--glass-bg); backdrop-filter: blur(16px); border: 1px solid var(--glass-border); border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: rgba(255, 255, 255, 0.03); border-bottom: 1px solid var(--glass-border);">
                    <th style="padding: 18px 16px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; width: 60px;">NO</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">NAMA SISWA</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">KELAS</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">USERNAME</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">PASSWORD</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; text-align: right;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($siswa as $index => $s)
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 18px 16px; color: var(--text-muted); font-weight: 600;">
                            {{ $siswa->firstItem() + $index }}
                        </td>
                        <td style="padding: 18px 24px; font-weight: 600; color: var(--text-main);">{{ $s->nama }}</td>
                        <td style="padding: 18px 24px; color: var(--text-muted);">{{ $s->kelas->nama_kelas ?? '-' }}</td>
                        <td style="padding: 18px 24px; font-weight: 700; color: var(--accent-green);">
                            {{ $s->akun ? $s->akun->username : 'Belum Ada Akun' }}
                        </td>
                        <td style="padding: 18px 24px; color: var(--text-main);">
                            {{ $s->akun ? $s->akun->password_plain : '-' }}
                        </td>
                        <td style="padding: 18px 24px; text-align: right;">
                            @if ($s->akun)
                                <button type="button" onclick="openResetModal({{ $s->akun->id }}, '{{ $s->nama }}')" style="color: #38bdf8; background: rgba(56, 189, 248, 0.1); border: 1px solid rgba(56, 189, 248, 0.2); padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                                    Reset Password
                                </button>
                            @else
                                <button type="button" onclick="openCreateModalFor({{ $s->id }})" style="color: var(--accent-green); background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                                    Buat Akun
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 48px 24px; text-align: center; color: var(--text-muted);">
                            Data siswa tidak ditemukan / belum ada data.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- PAGINATION NEXT/PREV -->
        @if ($siswa->hasPages())
            <div style="padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--glass-border); background: rgba(0, 0, 0, 0.1);">
                <div style="font-size: 13px; color: var(--text-muted);">
                    Halaman {{ $siswa->currentPage() }} dari {{ $siswa->lastPage() }} ({{ $siswa->total() }} siswa)
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

    <!-- FORM TERSEMBUNYI BUAT BULK CREATE -->
    <form id="bulkCreateForm" method="POST" action="{{ route('akun-siswa.bulk-create') }}" style="display: none;">
        @csrf
        <input type="hidden" name="kelas_id" id="bulkCreateKelasId">
    </form>

    <!-- POPUP MODAL -->
    <div id="akunModal" style="display: none; position: fixed; inset: 0; z-index: 999; background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(6px); align-items: center; justify-content: center;">
        <div style="background: #0d1322; border: 1px solid var(--glass-border); border-radius: 20px; width: 100%; max-width: 440px; padding: 28px;">
            <h3 id="modalTitle" style="margin: 0 0 20px 0; font-size: 20px; font-weight: 800;">Buat Akun Siswa</h3>

            <form id="akunForm" method="POST" action="{{ route('akun-siswa.store') }}">
                @csrf
                <input type="hidden" id="methodField" name="_method" value="POST">

                <div id="siswaSelectGroup" style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 13px; color: var(--text-muted); margin-bottom: 8px;">PILIH SISWA</label>
                    <select id="siswa_id" name="siswa_id" style="width: 100%; background: #161f33; border: 1px solid var(--glass-border); border-radius: 10px; padding: 12px; color: var(--text-main);">
                        @forelse ($siswaTanpaAkun as $s)
                            <option value="{{ $s->id }}">{{ $s->nama }} ({{ $s->kelas->nama_kelas ?? '-' }})</option>
                        @empty
                            <option value="" disabled selected>Semua siswa sudah punya akun</option>
                        @endforelse
                    </select>
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 13px; color: var(--text-muted); margin-bottom: 8px;">PASSWORD</label>
                    <input type="text" id="password" name="password" placeholder="Masukkan password siswa" required style="width: 100%; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--glass-border); border-radius: 10px; padding: 12px; color: var(--text-main); box-sizing: border-box;">
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button type="button" onclick="closeModal()" style="background: rgba(255, 255, 255, 0.05); border: 1px solid var(--glass-border); color: var(--text-muted); padding: 10px 18px; border-radius: 10px; cursor: pointer;">Batal</button>
                    <button type="submit" style="background: var(--accent-green); border: none; color: #090d16; padding: 10px 20px; border-radius: 10px; font-weight: 700; cursor: pointer;">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('akunModal');
        const form = document.getElementById('akunForm');
        const modalTitle = document.getElementById('modalTitle');
        const methodField = document.getElementById('methodField');
        const siswaSelectGroup = document.getElementById('siswaSelectGroup');
        const siswaSelect = document.getElementById('siswa_id');

        function openModal(mode) {
            modal.style.display = 'flex';
            modalTitle.innerText = 'Buat Akun Siswa Baru';
            form.action = "{{ route('akun-siswa.store') }}";
            methodField.value = 'POST';
            siswaSelectGroup.style.display = 'block';
        }

        function openCreateModalFor(siswaId) {
            openModal('create');
            if(siswaSelect) siswaSelect.value = siswaId;
        }

        function openResetModal(akunId, namaSiswa) {
            modal.style.display = 'flex';
            modalTitle.innerText = 'Reset Password - ' + namaSiswa;
            form.action = `/akun-siswa/${akunId}`;
            methodField.value = 'PUT';
            siswaSelectGroup.style.display = 'none';
        }

        function closeModal() {
            modal.style.display = 'none';
        }

        function submitBulkCreate() {
            const kelasId = document.getElementById('kelasAksiSelect').value;
            if (!kelasId) {
                alert('Pilih kelas dulu.');
                return;
            }
            if (!confirm('Buat akun otomatis untuk semua siswa di kelas ini yang belum punya akun?')) return;

            document.getElementById('bulkCreateKelasId').value = kelasId;
            document.getElementById('bulkCreateForm').submit();
        }

        function downloadExcel() {
            const kelasId = document.getElementById('filterKelasSelect').value;
            if (!kelasId) {
                alert('Pilih kelas dulu di filter, baru bisa download.');
                return;
            }
            window.location.href = "{{ route('akun-siswa.export') }}?kelas_id=" + kelasId;
        }
    </script>
@endsection