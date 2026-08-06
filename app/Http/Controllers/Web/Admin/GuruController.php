<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GuruController extends Controller
{
    public function index()
    {
        $guruList = User::where('role', 'guru')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.guru.index', compact('guruList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'],
            'role'     => 'guru',
        ]);

        return redirect()->route('admin.guru.index')
            ->with('success', 'Akun guru berhasil dibuat.');
    }

    public function update(Request $request, User $guru)
    {
        abort_if($guru->role !== 'guru', 403);

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($guru->id)],
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $guru->name = $data['name'];
        $guru->email = $data['email'];

        if (!empty($data['password'])) {
            $guru->password = $data['password'];
        }

        $guru->save();

        return redirect()->route('admin.guru.index')
            ->with('success', 'Akun guru berhasil diperbarui.');
    }

    public function destroy(User $guru)
    {
        abort_if($guru->role !== 'guru', 403);

        $guru->delete();

        return redirect()->route('admin.guru.index')
            ->with('success', 'Akun guru berhasil dihapus.');
    }
}