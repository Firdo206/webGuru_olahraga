<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\JenisOlahraga;
use App\Models\SesiTes;
use App\Models\StandarNilai;
use Illuminate\Http\Request;

class SesiTesController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::where('guru_id', auth()->id())->get();
        $olahragas = JenisOlahraga::where('guru_id', auth()->id())->get();

        $sesiQuery = SesiTes::where('guru_id', auth()->id())
            ->with(['kelas', 'jenisOlahraga']);

        if ($request->filled('kelas_id')) {
            $sesiQuery->where('kelas_id', $request->kelas_id);
        }

        $sesiTes = $sesiQuery
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu_mulai', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Sinkronkan status tiap sesi sesuai waktu sekarang, sebelum ditampilkan
        // Sekaligus tempel peringatan kalau standar nilai kelas itu belum lengkap
        $sesiTes->getCollection()->each(function (SesiTes $s) {
            $s->syncStatusWaktu();
            $s->peringatan_standar = StandarNilai::cekKelengkapan(auth()->id(), $s->kelas_id, $s->jenis_olahraga_id);
        });

        return view('sesi-tes.index', compact('sesiTes', 'kelas', 'olahragas'));
    }

    public function cekStandar(Request $request)
    {
        $request->validate([
            'kelas_id'          => 'required|exists:kelas,id',
            'jenis_olahraga_id' => 'required|exists:jenis_olahraga,id',
        ]);

        $kelas = Kelas::where('id', $request->kelas_id)
            ->where('guru_id', auth()->id())
            ->first();

        if (!$kelas) {
            return response()->json(['lengkap' => true]);
        }

        $peringatan = StandarNilai::cekKelengkapan(auth()->id(), $kelas->id, $request->jenis_olahraga_id);

        return response()->json([
            'lengkap' => $peringatan === null,
            'pesan'   => $peringatan,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kelas_id'          => 'required|exists:kelas,id',
            'jenis_olahraga_id' => 'required|exists:jenis_olahraga,id',
            'tanggal'           => 'required|date|after_or_equal:today',
            'waktu_mulai'       => 'required',
            'waktu_berakhir'    => 'required|after:waktu_mulai',
        ]);

        // pastikan kelas itu benar-benar milik guru yang login
        $kelas = Kelas::where('id', $data['kelas_id'])
            ->where('guru_id', auth()->id())
            ->firstOrFail();

        SesiTes::create([
            'guru_id'           => auth()->id(),
            'kelas_id'          => $kelas->id,
            'jenis_olahraga_id' => $data['jenis_olahraga_id'],
            'tanggal'           => $data['tanggal'],
            'waktu_mulai'       => $data['waktu_mulai'],
            'waktu_berakhir'    => $data['waktu_berakhir'],
            'status'            => 'belum_mulai',
        ]);

        $peringatan = StandarNilai::cekKelengkapan(auth()->id(), $kelas->id, $data['jenis_olahraga_id']);

        if ($peringatan) {
            return redirect()->route('sesi-tes.index')
                ->with('warning', "Sesi tes berhasil dibuat, tapi: {$peringatan}");
        }

        return redirect()->route('sesi-tes.index')
            ->with('success', 'Sesi tes berhasil dibuat.');
    }

    /**
     * Ubah status sesi: belum_mulai -> aktif -> selesai
     * Sesi yang statusnya 'aktif' yang akan muncul di app siswa.
     */
    public function updateStatus(Request $request, SesiTes $sesiTes)
    {
        $this->authorizeSesiGuru($sesiTes);

        $request->validate([
            'status' => 'required|in:belum_mulai,aktif,selesai',
        ]);

        $sesiTes->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status sesi tes diperbarui.');
    }

    public function destroy(SesiTes $sesiTes)
    {
        $this->authorizeSesiGuru($sesiTes);
        $sesiTes->delete();

        return redirect()->back()->with('success', 'Sesi tes berhasil dihapus.');
    }

    private function authorizeSesiGuru(SesiTes $sesiTes): void
    {
        abort_if($sesiTes->guru_id !== auth()->id(), 403);
    }
}