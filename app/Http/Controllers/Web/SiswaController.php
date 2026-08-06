<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil daftar kelas milik guru yang login
        $kelas = Kelas::where('guru_id', auth()->id())->get();

        // 2. Query dasar siswa
        $siswaQuery = Siswa::whereIn('kelas_id', $kelas->pluck('id'))
            ->with('kelas');

        // 3. Filter berdasarkan kelas yang dipilih
        if ($request->filled('kelas_id')) {
            $siswaQuery->where('kelas_id', $request->kelas_id);
        }

        // 4. Urutkan dari nomor absen 1 ke atas & paginasi 10 per halaman
        $siswa = $siswaQuery
            ->orderByRaw('CAST(nomor_absen AS UNSIGNED) ASC')
            ->paginate(10)
            ->withQueryString();

        return view('siswa.index', compact('siswa', 'kelas'));
    }

    public function store(Request $request)
    {
        $kelas = Kelas::where('id', $request->kelas_id)
            ->where('guru_id', auth()->id())
            ->firstOrFail();

        $data = $request->validate([
            'kelas_id'    => 'required|exists:kelas,id',
            'nama'        => 'required|string|max:255',
            'nomor_absen' => 'required|numeric|min:1',
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
        ]);

        Siswa::create([
            'kelas_id'      => $kelas->id,
            'nama'          => $data['nama'],
            'nomor_absen'   => $data['nomor_absen'],
            'jenis_kelamin' => $data['jenis_kelamin'],
        ]);

        return redirect()->route('siswa.index', ['kelas_id' => $request->kelas_id])
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function update(Request $request, Siswa $siswa)
    {
        $this->authorizeSiswaGuru($siswa);

        $kelasTujuan = Kelas::where('id', $request->kelas_id)
            ->where('guru_id', auth()->id())
            ->firstOrFail();

        $data = $request->validate([
            'kelas_id'    => 'required|exists:kelas,id',
            'nama'        => 'required|string|max:255',
            'nomor_absen' => 'required|numeric|min:1',
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
        ]);

        $siswa->update([
            'kelas_id'      => $kelasTujuan->id,
            'nama'          => $data['nama'],
            'nomor_absen'   => $data['nomor_absen'],
            'jenis_kelamin' => $data['jenis_kelamin'],
        ]);

        return redirect()->route('siswa.index', ['kelas_id' => $request->kelas_id])
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        $this->authorizeSiswaGuru($siswa);
        $siswa->delete();

        return redirect()->back()->with('success', 'Siswa berhasil dihapus.');
    }

    private function authorizeSiswaGuru(Siswa $siswa): void
    {
        abort_if($siswa->kelas->guru_id !== auth()->id(), 403);
    }
}