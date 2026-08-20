<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Imports\SiswaImport;
use App\Exports\TemplateSiswaExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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

    /**
     * Hapus banyak siswa sekaligus (dipilih lewat checkbox).
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'exists:siswas,id',
        ]);

        $kelasIds = Kelas::where('guru_id', auth()->id())->pluck('id');

        $jumlah = Siswa::whereIn('id', $request->ids)
            ->whereIn('kelas_id', $kelasIds) // pastikan cuma siswa milik guru ini yang kehapus
            ->count();

        Siswa::whereIn('id', $request->ids)
            ->whereIn('kelas_id', $kelasIds)
            ->delete();

        return redirect()->back()->with('success', "{$jumlah} siswa berhasil dihapus.");
    }

    /**
     * Hapus semua siswa (ikut filter kelas kalau lagi difilter).
     */
    public function destroyAll(Request $request)
    {
        $kelasIds = Kelas::where('guru_id', auth()->id())->pluck('id');

        $query = Siswa::whereIn('kelas_id', $kelasIds);

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        $jumlah = $query->count();
        $query->delete();

        return redirect()->route('siswa.index')->with('success', "{$jumlah} siswa berhasil dihapus.");
    }

    /**
     * Import siswa dari file Excel/CSV ke kelas yang dipilih.
     */
  public function import(Request $request)
{
    $request->validate([
        'kelas_id' => 'required|exists:kelas,id',
        'file' => 'required|mimes:xlsx,xls,csv|max:2048',
    ]);

    $kelas = Kelas::where('id', $request->kelas_id)
        ->where('guru_id', auth()->id())
        ->firstOrFail();

    $import = new SiswaImport($kelas->id);
    Excel::import($import, $request->file('file'));

    $totalGagal = count($import->skipped);

    $pesan = $import->importedCount > 0
        ? "{$import->importedCount} siswa berhasil diimport." . ($totalGagal > 0 ? " {$totalGagal} baris dilewati." : '')
        : 'Tidak ada siswa yang berhasil diimport. Cek detail di bawah.';

    return redirect()->route('siswa.index', ['kelas_id' => $kelas->id])
        ->with($totalGagal > 0 || $import->importedCount === 0 ? 'warning' : 'success', $pesan)
        ->with('import_skipped', $import->skipped);
}

    /**
     * Download template Excel kosong buat diisi guru.
     */
    public function downloadTemplate()
    {
        return Excel::download(new TemplateSiswaExport, 'template_import_siswa.xlsx');
    }

    private function authorizeSiswaGuru(Siswa $siswa): void
    {
        abort_if($siswa->kelas->guru_id !== auth()->id(), 403);
    }
}