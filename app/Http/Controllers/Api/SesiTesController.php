<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SesiTes;
use Illuminate\Http\Request;

class SesiTesController extends Controller
{
    public function index(Request $request)
    {
        $siswa = $request->user()->siswa;

        $sesiList = SesiTes::with('jenisOlahraga')
            ->where('kelas_id', $siswa->kelas_id)
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu_mulai', 'desc')
            ->get();

        $sesiList->each(fn (SesiTes $s) => $s->syncStatusWaktu());

        $data = $sesiList->fresh(['jenisOlahraga'])->map(function (SesiTes $s) use ($siswa) {
            $sudahSubmit = $s->hasilTes()->where('siswa_id', $siswa->id)->exists();

            return [
                'id' => $s->id,
                'nama_olahraga' => $s->jenisOlahraga->nama_olahraga,
                'tipe' => $s->jenisOlahraga->tipe,
                'protokol_tes' => $s->jenisOlahraga->protokol_tes,
                'durasi_detik' => $s->jenisOlahraga->durasi_detik,
                'tanggal' => \Carbon\Carbon::parse($s->tanggal)->format('Y-m-d'),
                'waktu_mulai' => $s->waktu_mulai,
                'waktu_berakhir' => $s->waktu_berakhir,
                'status' => $s->status,
                'sudah_submit' => $sudahSubmit,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function show(Request $request, SesiTes $sesiTes)
    {
        $siswa = $request->user()->siswa;

        if ($sesiTes->kelas_id !== $siswa->kelas_id) {
            return response()->json(['success' => false, 'message' => 'Sesi tes ini bukan untuk kelas Anda.'], 403);
        }

        $sesiTes->syncStatusWaktu();
        $sesiTes->refresh();

        $hasilSaya = $sesiTes->hasilTes()->where('siswa_id', $siswa->id)->first();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $sesiTes->id,
                'nama_olahraga' => $sesiTes->jenisOlahraga->nama_olahraga,
                'tipe' => $sesiTes->jenisOlahraga->tipe,
                'protokol_tes' => $sesiTes->jenisOlahraga->protokol_tes,
                'durasi_detik' => $sesiTes->jenisOlahraga->durasi_detik,
                'status' => $sesiTes->status,
                'hasil_saya' => $hasilSaya ? [
                    'nilai_hasil' => $hasilSaya->nilai_hasil,
                    'grade' => $hasilSaya->grade,
                ] : null,
            ],
        ]);
    }
}