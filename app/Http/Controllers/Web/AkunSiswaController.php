<?php

namespace App\Http\Controllers\Web;

use App\Exports\AkunSiswaExport;
use App\Http\Controllers\Controller;
use App\Models\AkunSiswa;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class AkunSiswaController extends Controller
{
    public function index(Request $request)
    {
        // 1. Kelas cuma punya guru yang login
        $kelas = Kelas::where('guru_id', auth()->id())->get();

        // 2. Query siswa cuma yang kelasnya milik guru ini
        $query = Siswa::with(['kelas', 'akun'])
            ->whereIn('kelas_id', $kelas->pluck('id'));

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

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

        $siswa = Siswa::whereHas('kelas', function ($q) {
            $q->where('guru_id', auth()->id());
        })->findOrFail($request->siswa_id);

        $username = $this->generateUsername($siswa);

        AkunSiswa::create([
            'siswa_id'       => $siswa->id,
            'username'       => $username,
            'password'       => Hash::make($request->password),
            'password_plain' => $request->password,
        ]);

        return redirect()->back()->with('success', "Akun berhasil dibuat dengan Username: {$username}");
    }

    /**
     * Buat akun otomatis (username + password random) sekaligus
     * buat semua siswa di 1 kelas yang belum punya akun.
     */
    public function storeBulk(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $kelas = Kelas::where('id', $request->kelas_id)
            ->where('guru_id', auth()->id())
            ->firstOrFail();

        $siswaTanpaAkun = Siswa::where('kelas_id', $kelas->id)
            ->whereDoesntHave('akun')
            ->get();

        if ($siswaTanpaAkun->isEmpty()) {
            return redirect()->back()->with('success', "Semua siswa di kelas {$kelas->nama_kelas} sudah punya akun.");
        }

        foreach ($siswaTanpaAkun as $siswa) {
            $username = $this->generateUsername($siswa);
            $password = (string) random_int(100000, 999999); // password 6 digit angka, gampang diketik

            AkunSiswa::create([
                'siswa_id'       => $siswa->id,
                'username'       => $username,
                'password'       => Hash::make($password),
                'password_plain' => $password,
            ]);
        }

        $jumlah = $siswaTanpaAkun->count();

        return redirect()->back()->with('success', "{$jumlah} akun berhasil dibuat otomatis untuk kelas {$kelas->nama_kelas}.");
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:4',
        ]);

        $akun = AkunSiswa::whereHas('siswa.kelas', function ($q) {
            $q->where('guru_id', auth()->id());
        })->findOrFail($id);

        $akun->update([
            'password'       => Hash::make($request->password),
            'password_plain' => $request->password,
        ]);

        return redirect()->back()->with('success', 'Password siswa berhasil diperbarui!');
    }

    /**
     * Download daftar username + password (per kelas) dalam bentuk Excel.
     */
    public function export(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $kelas = Kelas::where('id', $request->kelas_id)
            ->where('guru_id', auth()->id())
            ->firstOrFail();

        $filename = 'akun-siswa-' . Str::slug($kelas->nama_kelas) . '.xlsx';

        return Excel::download(new AkunSiswaExport($kelas->id, auth()->id(), $kelas->nama_kelas), $filename);
    }

    /**
     * Generate username unik dari nama siswa (dipakai store() & storeBulk()).
     */
    private function generateUsername(Siswa $siswa): string
    {
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

        return $username;
    }
}