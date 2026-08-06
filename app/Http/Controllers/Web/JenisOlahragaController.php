<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\JenisOlahraga;
use Illuminate\Http\Request;

class JenisOlahragaController extends Controller
{
    public function index()
    {
        $jenisOlahraga = JenisOlahraga::latest()->get();

        return view('jenis-olahraga.index', compact('jenisOlahraga'));
    }

public function store(Request $request)
{
    $data = $request->validate([
        'nama_olahraga' => 'required|string|max:255|unique:jenis_olahraga,nama_olahraga',
        'tipe' => 'required|in:waktu,poin',
        'protokol_tes' => 'nullable|string|max:255',
        'deskripsi' => 'nullable|string',
    ]);

    JenisOlahraga::create($data);

    return redirect()->route('jenis-olahraga.index')->with('success', 'Jenis olahraga berhasil ditambahkan.');
}

public function update(Request $request, JenisOlahraga $jenisOlahraga)
{
    $data = $request->validate([
        'nama_olahraga' => 'required|string|max:255|unique:jenis_olahraga,nama_olahraga,' . $jenisOlahraga->id,
        'tipe' => 'required|in:waktu,poin',
        'protokol_tes' => 'nullable|string|max:255',
        'deskripsi' => 'nullable|string',
    ]);

    $jenisOlahraga->update($data);

    return redirect()->route('jenis-olahraga.index')->with('success', 'Jenis olahraga berhasil diupdate.');
}

    public function destroy(JenisOlahraga $jenisOlahraga)
    {
        $jenisOlahraga->delete();

        return redirect()->route('jenis-olahraga.index')->with('success', 'Jenis olahraga berhasil dihapus.');
    }
    
}