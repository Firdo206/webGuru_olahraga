@extends('layouts.app')

@section('title', 'Kelola Kelas')

@section('content')
    <!-- Page Header -->
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <span style="text-transform: uppercase; font-size: 12px; font-weight: 700; letter-spacing: 1.2px; color: var(--accent-green); display: block; margin-bottom: 4px;">Data Master</span>
            <h2 style="font-size: 28px; font-weight: 800; margin: 0; letter-spacing: -0.5px;">Kelola Kelas</h2>
        </div>
        <button onclick="openModal('create')" style="background: var(--accent-green); color: #090d16; border: none; padding: 12px 20px; border-radius: 12px; font-weight: 700; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>+ Tambah Kelas</span>
        </button>
    </div>

    <!-- Alert Success -->
    @if (session('success'))
        <div style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); color: #6ee7b7; padding: 14px 20px; border-radius: 14px; font-size: 14px; margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Table Container -->
    <div style="background: var(--glass-bg); backdrop-filter: blur(16px); border: 1px solid var(--glass-border); border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: rgba(255, 255, 255, 0.03); border-bottom: 1px solid var(--glass-border);">
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; width: 80px;">NO</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">NAMA KELAS</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; text-align: right; width: 180px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kelas as $index => $k)
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 18px 24px; font-weight: 700; color: var(--accent-green);">
                            #{{ $index + 1 }}
                        </td>
                        <td style="padding: 18px 24px; font-weight: 600; color: var(--text-main);">
                            {{ $k->nama_kelas }}
                        </td>
                        <td style="padding: 18px 24px; text-align: right;">
                            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px;">
                                <button type="button" onclick="openEditModal({{ $k->id }}, '{{ $k->nama_kelas }}')" style="color: #38bdf8; background: rgba(56, 189, 248, 0.1); border: 1px solid rgba(56, 189, 248, 0.2); padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                                    Edit
                                </button>
                                
                                <form method="POST" action="{{ route('kelas.destroy', $k->id) }}" style="margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')">
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
                        <td colspan="3" style="padding: 48px 24px; text-align: center; color: var(--text-muted);">
                            <p style="margin: 0; font-size: 15px;">Data kelas tidak ditemukan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- POPUP MODAL (TAMBAH / EDIT KELAS) -->
    <div id="kelasModal" style="display: none; position: fixed; inset: 0; z-index: 999; background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(6px); align-items: center; justify-content: center;">
        <div style="background: #0d1322; border: 1px solid var(--glass-border); border-radius: 20px; width: 100%; max-width: 440px; padding: 28px; position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 id="modalTitle" style="margin: 0; font-size: 20px; font-weight: 800; color: var(--text-main);">Tambah Kelas</h3>
                <button type="button" onclick="closeModal()" style="background: none; border: none; color: var(--text-muted); font-size: 20px; cursor: pointer;">&times;</button>
            </div>

            <form id="kelasForm" method="POST" action="{{ route('kelas.store') }}">
                @csrf
                <input type="hidden" id="methodField" name="_method" value="POST">

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 8px;">NAMA KELAS</label>
                    <input type="text" id="nama_kelas" name="nama_kelas" placeholder="Contoh: XII RPL 1" required style="width: 100%; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--glass-border); border-radius: 10px; padding: 12px 16px; color: var(--text-main); font-size: 14px; outline: none; box-sizing: border-box;">
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button type="button" onclick="closeModal()" style="background: rgba(255, 255, 255, 0.05); border: 1px solid var(--glass-border); color: var(--text-muted); padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: 14px; cursor: pointer;">Batal</button>
                    <button type="submit" style="background: var(--accent-green); border: none; color: #090d16; padding: 10px 20px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer;">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPT POPUP MODAL -->
    <script>
        const modal = document.getElementById('kelasModal');
        const form = document.getElementById('kelasForm');
        const modalTitle = document.getElementById('modalTitle');
        const methodField = document.getElementById('methodField');
        const namaKelasInput = document.getElementById('nama_kelas');

        function openModal(mode) {
            modal.style.display = 'flex';
            if (mode === 'create') {
                modalTitle.innerText = 'Tambah Kelas Baru';
                form.action = "{{ route('kelas.store') }}";
                methodField.value = 'POST';
                namaKelasInput.value = '';
            }
        }

        function openEditModal(id, namaKelas) {
            modal.style.display = 'flex';
            modalTitle.innerText = 'Edit Data Kelas';
            form.action = `/kelas/${id}`;
            methodField.value = 'PUT';
            namaKelasInput.value = namaKelas;
        }

        function closeModal() {
            modal.style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
@endsection