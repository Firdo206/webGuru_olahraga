<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::where('guru_id', auth()->id())
            ->latest()
            ->get();

        return view('kelas.index', compact('kelas'));
    }
    public function show(Kelas $kela)
{
    $this->authorizeKelas($kela);

    $siswa = $kela->siswa()->latest()->get();

    return view('kelas.show', ['kelas' => $kela, 'siswa' => $siswa]);
}

    public function create()
    {
        return view('kelas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kelas' => 'required|string|max:255',
        ]);

        Kelas::create([
            'guru_id' => auth()->id(),
            'nama_kelas' => $data['nama_kelas'],
        ]);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kela)
    {
        $this->authorizeKelas($kela);

        return view('kelas.edit', ['kelas' => $kela]);
    }

    public function update(Request $request, Kelas $kela)
    {
        $this->authorizeKelas($kela);

        $data = $request->validate([
            'nama_kelas' => 'required|string|max:255',
        ]);

        $kela->update($data);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diupdate.');
    }

    public function destroy(Kelas $kela)
    {
        $this->authorizeKelas($kela);

        $kela->delete();

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }

    private function authorizeKelas(Kelas $kelas): void
    {
        abort_if($kelas->guru_id !== auth()->id(), 403);
    }
    
}