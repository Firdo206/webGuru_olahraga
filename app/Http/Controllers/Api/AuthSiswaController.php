<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AkunSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthSiswaController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $akun = AkunSiswa::where('username', $request->username)->first();

        if (!$akun || !Hash::check($request->password, $akun->password)) {
            throw ValidationException::withMessages([
                'username' => ['Username atau password salah.'],
            ]);
        }

        // hapus token lama biar 1 akun cuma punya 1 sesi aktif
        $akun->tokens()->delete();
        $token = $akun->createToken('siswa_auth_token')->plainTextToken;

        $akun->load('siswa.kelas');

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'token'   => $token,
            'siswa'   => [
                'id'            => $akun->siswa->id,
                'nama'          => $akun->siswa->nama,
                'nomor_absen'   => $akun->siswa->nomor_absen,
                'jenis_kelamin' => $akun->siswa->jenis_kelamin,
                'kelas'         => $akun->siswa->kelas->nama_kelas ?? null,
                'username'      => $akun->username,
            ],
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ], 200);
    }

    public function me(Request $request)
    {
        $akun = $request->user();
        $akun->load('siswa.kelas');

        return response()->json([
            'id'            => $akun->siswa->id,
            'nama'          => $akun->siswa->nama,
            'nomor_absen'   => $akun->siswa->nomor_absen,
            'jenis_kelamin' => $akun->siswa->jenis_kelamin,
            'kelas'         => $akun->siswa->kelas->nama_kelas ?? null,
            'username'      => $akun->username,
        ], 200);
    }
}