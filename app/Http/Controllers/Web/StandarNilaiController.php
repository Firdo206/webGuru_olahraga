<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\StandarNilai;
use App\Models\DetailStandarNilai;
use App\Models\JenisOlahraga;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class StandarNilaiController extends Controller
{
    public function index(Request $request)
    {
        $query = StandarNilai::where('guru_id', auth()->id())
            ->with(['jenisOlahraga', 'details']);

        // Filter cari nama olahraga
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('jenisOlahraga', function ($q) use ($search) {
                $q->where('nama_olahraga', 'like', "%{$search}%");
            });
        }

        $all = $query->get();

        // Kelompokkan per jenis_olahraga_id, supaya Laki-Laki & Perempuan
        // untuk olahraga yang sama jadi satu baris di tabel.
        $grouped = $all->groupBy('jenis_olahraga_id')->values();

        // Pagination manual karena data sudah di-groupBy (bukan query builder lagi)
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = $grouped
            ->slice(($currentPage - 1) * $perPage, $perPage)
            ->values();

        $standars = new LengthAwarePaginator(
            $currentPageItems,
            $grouped->count(),
            $perPage,
            $currentPage,
            [
                'path'  => $request->url(),
                'query' => $request->query(),
            ]
        );

        $olahragas = JenisOlahraga::where('guru_id', auth()->id())->get();

        return view('standar-nilai.index', compact('standars', 'olahragas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_olahraga_id' => 'required',
            'jenis_kelamin'     => 'required',
            'waktu'             => 'nullable|string',
            'jarak'             => 'nullable|string',
            'grades'            => 'required|array',
        ]);

        DB::transaction(function () use ($request) {
            $standar = StandarNilai::create([
                'guru_id'           => auth()->id(),
                'jenis_olahraga_id' => $request->jenis_olahraga_id,
                'jenis_kelamin'     => $request->jenis_kelamin,
                'waktu'             => $request->waktu,
                'jarak'             => $request->jarak,
            ]);

            foreach ($request->grades as $item) {
                $ada_min = isset($item['minimal']) && $item['minimal'] !== '';
                $ada_max = isset($item['maksimal']) && $item['maksimal'] !== '';

                if (!isset($item['grade']) || (!$ada_min && !$ada_max)) {
                    continue;
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
        // pastikan standar nilai ini punya guru yang login
        $standar = StandarNilai::where('guru_id', auth()->id())->findOrFail($id);
        $standar->details()->delete();
        $standar->delete();

        return redirect()->back()->with('success', 'Standar Nilai berhasil dihapus!');
    }
}