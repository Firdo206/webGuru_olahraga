<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HasilTes;
use App\Models\SesiTes;
use Illuminate\Http\Request;

class HasilTesController extends Controller
{
    public function store(Request $request, SesiTes $sesiTes)
    {
        $siswa = $request->user()->siswa;

        if ($sesiTes->kelas_id !== $siswa->kelas_id) {
            return response()->json(['success' => false, 'message' => 'Sesi tes ini bukan untuk kelas Anda.'], 403);
        }

        $sesiTes->syncStatusWaktu();
        $sesiTes->refresh();

        if ($sesiTes->status !== 'aktif') {
            return response()->json([
                'success' => false,
                'message' => $sesiTes->status === 'belum_mulai'
                    ? 'Sesi tes belum dimulai.'
                    : 'Sesi tes sudah berakhir.',
            ], 422);
        }

        $sudahAda = HasilTes::where('sesi_tes_id', $sesiTes->id)
            ->where('siswa_id', $siswa->id)
            ->exists();

        if ($sudahAda) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah mengirim hasil untuk sesi tes ini.',
            ], 422);
        }

        $request->validate([
            'nilai_hasil' => 'required|numeric|min:0',
        ]);

        $hasil = HasilTes::simpanHasil($sesiTes, $siswa, (float) $request->nilai_hasil);

        return response()->json([
            'success' => true,
            'message' => 'Hasil tes berhasil dikirim.',
            'data' => [
                'nilai_hasil' => $hasil->nilai_hasil,
                'grade' => $hasil->grade,
            ],
        ], 201);
    }

    public function riwayat(Request $request)
    {
        $siswa = $request->user()->siswa;

        $riwayat = HasilTes::with(['sesiTes.jenisOlahraga'])
            ->where('siswa_id', $siswa->id)
            ->latest()
            ->get()
            ->map(function (HasilTes $h) {
                return [
                    'sesi_tes_id' => $h->sesi_tes_id,
                    'nama_olahraga' => $h->sesiTes->jenisOlahraga->nama_olahraga,
                    'tipe' => $h->sesiTes->jenisOlahraga->tipe,
                    'tanggal' => $h->sesiTes->tanggal->format('Y-m-d'),
                    'nilai_hasil' => $h->nilai_hasil,
                    'grade' => $h->grade,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $riwayat,
        ]);
    }
}