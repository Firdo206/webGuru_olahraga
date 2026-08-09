<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\JenisOlahraga;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JenisOlahragaController extends Controller
{
    public function index()
    {
        $jenisOlahraga = JenisOlahraga::where('guru_id', auth()->id())
            ->latest()
            ->get();

        return view('jenis-olahraga.index', compact('jenisOlahraga'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_olahraga' => [
                'required', 'string', 'max:255',
                Rule::unique('jenis_olahraga')->where(fn ($q) => $q->where('guru_id', auth()->id())),
            ],
            'tipe' => 'required|in:waktu,poin',
            'protokol_tes' => 'nullable|string|max:255',
            'durasi_detik' => 'nullable|integer|min:1|required_if:tipe,poin',
            'deskripsi' => 'nullable|string',
        ]);

        if ($data['tipe'] === 'waktu') {
            $data['durasi_detik'] = null;
        }

        $data['guru_id'] = auth()->id();

        JenisOlahraga::create($data);

        return redirect()->route('jenis-olahraga.index')->with('success', 'Jenis olahraga berhasil ditambahkan.');
    }

    public function update(Request $request, JenisOlahraga $jenisOlahraga)
    {
        abort_if($jenisOlahraga->guru_id !== auth()->id(), 403);

        $data = $request->validate([
            'nama_olahraga' => [
                'required', 'string', 'max:255',
                Rule::unique('jenis_olahraga')
                    ->where(fn ($q) => $q->where('guru_id', auth()->id()))
                    ->ignore($jenisOlahraga->id),
            ],
            'tipe' => 'required|in:waktu,poin',
            'protokol_tes' => 'nullable|string|max:255',
            'durasi_detik' => 'nullable|integer|min:1|required_if:tipe,poin',
            'deskripsi' => 'nullable|string',
        ]);

        if ($data['tipe'] === 'waktu') {
            $data['durasi_detik'] = null;
        }

        $jenisOlahraga->update($data);

        return redirect()->route('jenis-olahraga.index')->with('success', 'Jenis olahraga berhasil diupdate.');
    }

    public function destroy(JenisOlahraga $jenisOlahraga)
    {
        abort_if($jenisOlahraga->guru_id !== auth()->id(), 403);

        $jenisOlahraga->delete();

        return redirect()->route('jenis-olahraga.index')->with('success', 'Jenis olahraga berhasil dihapus.');
    }
}