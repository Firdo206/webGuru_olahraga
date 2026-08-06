<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\HasilTes;
use App\Models\Kelas;
use App\Models\SesiTes;
use Illuminate\Http\Request;

class HasilTesController extends Controller
{
    /**
     * Riwayat semua sesi tes milik guru, bisa difilter per kelas.
     */
    public function index(Request $request)
    {
        SesiTes::syncSemuaStatus(); // auto-update status sesi berdasarkan waktu

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
     * Detail hasil tes untuk satu sesi tertentu, bisa dicari per nama siswa.
     */
    public function show(Request $request, SesiTes $sesiTes)
    {
        abort_if($sesiTes->guru_id !== auth()->id(), 403);

        $sesiTes->syncStatusWaktu(); // pastikan status sesi ini juga up-to-date

        $sesiTes->load(['kelas', 'jenisOlahraga']);

        $hasilQuery = HasilTes::where('sesi_tes_id', $sesiTes->id)
            ->with('siswa');

        if ($request->filled('search')) {
            $search = $request->search;
            $hasilQuery->whereHas('siswa', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }

        $hasil = $hasilQuery
            ->join('siswas', 'hasil_tes.siswa_id', '=', 'siswas.id')
            ->orderByRaw('CAST(siswas.nomor_absen AS UNSIGNED) ASC')
            ->select('hasil_tes.*')
            ->get();

        return view('hasil-tes.show', compact('sesiTes', 'hasil'));
    }
}