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

    <!-- BARIS FILTER & SEARCH -->
    <div style="background: var(--glass-bg); backdrop-filter: blur(16px); border: 1px solid var(--glass-border); border-radius: 16px; padding: 16px 20px; margin-bottom: 24px;">
        <form method="GET" action="{{ route('akun-siswa.index') }}" style="display: flex; gap: 16px; flex-wrap: wrap; align-items: center; justify-content: space-between;">
            <div style="display: flex; gap: 12px; flex: 1; min-width: 280px;">
                <!-- Cari Nama -->
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa..." style="flex: 1; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--glass-border); border-radius: 10px; padding: 10px 16px; color: var(--text-main); font-size: 14px;">
                
                <!-- Filter Kelas -->
                <select name="kelas_id" style="background: #161f33; border: 1px solid var(--glass-border); border-radius: 10px; padding: 10px 16px; color: var(--text-main); font-size: 14px; min-width: 160px;">
                    <option value="">Semua Kelas</option>
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="submit" style="background: rgba(255, 255, 255, 0.1); border: 1px solid var(--glass-border); color: var(--text-main); padding: 10px 18px; border-radius: 10px; font-weight: 600; cursor: pointer; font-size: 14px;">
                    Filter
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
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">NAMA SISWA</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">KELAS</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">USERNAME</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">PASSWORD</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; text-align: right;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($siswa as $s)
                    <tr style="border-bottom: 1px solid var(--glass-border);">
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
                        <td colspan="5" style="padding: 48px 24px; text-align: center; color: var(--text-muted);">
                            Data siswa tidak ditemukan / belum ada data.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

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
                        @foreach ($siswa->where('akun', null) as $s)
                            <option value="{{ $s->id }}">{{ $s->nama }} ({{ $s->kelas->nama_kelas ?? '-' }})</option>
                        @endforeach
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
    </script>
@endsection