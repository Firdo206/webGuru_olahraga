<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AkunSiswa;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AkunSiswaController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil daftar semua kelas untuk opsi dropdown filter
        $kelas = Kelas::all();

        // 2. Query data siswa beserta relasi kelas dan akun
        $query = Siswa::with(['kelas', 'akun']);

        // Filter berdasarkan Kelas jika dipilih
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        // Filter/Search berdasarkan nama siswa
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $siswa = $query->get();

        return view('akun-siswa.index', compact('siswa', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id|unique:akun_siswas,siswa_id',
            'password' => 'required|min:4',
        ], [
            'siswa_id.unique' => 'Siswa ini sudah memiliki akun!',
        ]);

        $siswa = Siswa::findOrFail($request->siswa_id);

        $baseUsername = Str::slug($siswa->nama, '');
        $username = $baseUsername;

        if (AkunSiswa::where('username', $username)->exists()) {
            $username = $baseUsername . $siswa->nomor_absen;

            $counter = 1;
            while (AkunSiswa::where('username', $username)->exists()) {
                $username = $baseUsername . $siswa->nomor_absen . $counter;
                $counter++;
            }
        }

        AkunSiswa::create([
            'siswa_id'       => $siswa->id,
            'username'       => $username,
            'password'       => Hash::make($request->password),
            'password_plain' => $request->password,
        ]);

        return redirect()->back()->with('success', "Akun berhasil dibuat dengan Username: {$username}");
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:4',
        ]);

        $akun = AkunSiswa::findOrFail($id);
        $akun->update([
            'password'       => Hash::make($request->password),
            'password_plain' => $request->password,
        ]);

        return redirect()->back()->with('success', 'Password siswa berhasil diperbarui!');
    }
}