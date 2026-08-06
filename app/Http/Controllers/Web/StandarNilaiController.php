<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\StandarNilai;
use App\Models\DetailStandarNilai;
use App\Models\JenisOlahraga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StandarNilaiController extends Controller
{
    public function index()
    {
        $standars = StandarNilai::with(['jenisOlahraga', 'details'])->get();
        $olahragas = JenisOlahraga::all();
        return view('standar-nilai.index', compact('standars', 'olahragas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_olahraga_id' => 'required',
            'jenis_kelamin'     => 'required',
            'waktu'             => 'nullable|string', // <-- Validasi waktu
            'jarak'             => 'nullable|string',
            'grades'            => 'required|array',
        ]);

        DB::transaction(function () use ($request) {
            $standar = StandarNilai::create([
                'jenis_olahraga_id' => $request->jenis_olahraga_id,
                'jenis_kelamin'     => $request->jenis_kelamin,
                'waktu'             => $request->waktu,       // <-- Simpan waktu
                'jarak'             => $request->jarak,
            ]);

          foreach ($request->grades as $item) {
    $ada_min = isset($item['minimal']) && $item['minimal'] !== '';
    $ada_max = isset($item['maksimal']) && $item['maksimal'] !== '';

    if (!isset($item['grade']) || (!$ada_min && !$ada_max)) {
        continue; // skip baris yang kosong total
    }

    if ($ada_min && $ada_max && (float) $item['minimal'] > (float) $item['maksimal']) {
        throw \Illuminate\Validation\ValidationException::withMessages([
            'grades' => "Rentang nilai grade {$item['grade']} tidak valid: batas bawah lebih besar dari batas atas.",
        ]);
    }

    DetailStandarNilai::create([
        'standar_nilai_id' => $standar->id,
        'grade'            => $item['grade'],
        'minimal'          => $ada_min ? $item['minimal'] : null,
        'maksimal'         => $ada_max ? $item['maksimal'] : null,
    ]);
}
        });

        return redirect()->back()->with('success', 'Standar Nilai berhasil disimpan!');
    }

    public function destroy($id)
    {
        $standar = StandarNilai::findOrFail($id);
        $standar->details()->delete();
        $standar->delete();

        return redirect()->back()->with('success', 'Standar Nilai berhasil dihapus!');
    }
}