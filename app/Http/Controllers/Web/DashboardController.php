<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\SesiTes;
use App\Models\Siswa;

class DashboardController extends Controller
{
    public function index()
    {
        $guruId = auth()->id();

        // Hitung total kelas milik guru login
        $totalKelas = Kelas::where('guru_id', $guruId)->count();

        // Hitung total siswa yang berada di kelas milik guru login
        $totalSiswa = Siswa::whereHas('kelas', function ($query) use ($guruId) {
            $query->where('guru_id', $guruId);
        })->count();

        // Hitung sesi tes milik guru login yang statusnya sudah selesai
        $tesTerlaksana = SesiTes::where('guru_id', $guruId)
            ->where('status', 'selesai')
            ->count();

        return view('dashboard', compact('totalKelas', 'totalSiswa', 'tesTerlaksana'));
    }
}