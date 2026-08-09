<?php

namespace App\Http\Controllers\Web;

use App\Exports\HasilTesExport;
use App\Http\Controllers\Controller;
use App\Models\HasilTes;
use App\Models\Kelas;
use App\Models\SesiTes;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HasilTesController extends Controller
{
    /**
     * Riwayat semua sesi tes milik guru, bisa difilter per kelas.
     */
    public function index(Request $request)
    {
        SesiTes::syncSemuaStatus();

        $kelas = Kelas::where('guru_id', auth()->id())->get();

        $sesiQuery = SesiTes::where('guru_id', auth()->id())
            ->with(['kelas', 'jenisOlahraga'])
            ->withCount('hasilTes');

        if ($request->filled('kelas_id')) {
            $sesiQuery->where('kelas_id', $request->kelas_id);
        }

        $sesiList = $sesiQuery
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu_mulai', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('hasil-tes.index', compact('sesiList', 'kelas'));
    }

    /**
     * Detail hasil tes untuk satu sesi, dipaginate 10 siswa per halaman.
     * Menampilkan SEMUA siswa di kelas (bukan cuma yang sudah kirim hasil).
     */
    public function show(Request $request, SesiTes $sesiTes)
    {
        abort_if($sesiTes->guru_id !== auth()->id(), 403);

        $sesiTes->syncStatusWaktu();
        $sesiTes->load(['kelas', 'jenisOlahraga']);

        $siswaQuery = Siswa::where('kelas_id', $sesiTes->kelas_id);

        if ($request->filled('search')) {
            $siswaQuery->where('nama', 'like', '%' . $request->search . '%');
        }

        // Total siswa (sesuai filter search) dihitung sebelum paginate,
        // supaya ringkasan "X dari Y siswa" tetap benar walau lagi di halaman 2/3/dst.
        $totalSiswaKelas = (clone $siswaQuery)->count();

        $siswaList = $siswaQuery
            ->orderByRaw('CAST(nomor_absen AS UNSIGNED) ASC')
            ->paginate(10)
            ->withQueryString();

        // Hasil tes yang sudah masuk untuk sesi ini (semua, tidak dipaginate,
        // supaya hitungan "sudah tes" akurat untuk seluruh kelas).
        $hasilMap = HasilTes::where('sesi_tes_id', $sesiTes->id)
            ->get()
            ->keyBy('siswa_id');

        $sudahTes = $hasilMap->count();

        return view('hasil-tes.show', compact('sesiTes', 'siswaList', 'hasilMap', 'totalSiswaKelas', 'sudahTes'));
    }

    /**
     * Download hasil tes satu sesi sebagai file Excel (.xlsx).
     */
    public function export(SesiTes $sesiTes)
    {
        abort_if($sesiTes->guru_id !== auth()->id(), 403);

        $sesiTes->load(['kelas', 'jenisOlahraga']);

        $namaOlahraga = str_replace(' ', '-', $sesiTes->jenisOlahraga->nama_olahraga ?? 'Tes');
        $namaKelas = str_replace(' ', '-', $sesiTes->kelas->nama_kelas ?? 'Kelas');
        $tanggal = \Carbon\Carbon::parse($sesiTes->tanggal)->format('d-m-Y');

        $namaFile = "Hasil-Tes-{$namaOlahraga}-{$namaKelas}-{$tanggal}.xlsx";

        return Excel::download(new HasilTesExport($sesiTes), $namaFile);
    }
}