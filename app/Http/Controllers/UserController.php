<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RiwayatDiagnosa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // ✅ role yang benar sesuai sistem kamu
    private array $roles = ['pemilik', 'pakar', 'peternak'];

    public function index()
    {
        $users = User::orderBy('name')->get();

        // ✅ user yang pernah melakukan diagnosa (untuk disable hapus di view)
        $usedUserIds = RiwayatDiagnosa::select('user_id')
            ->distinct()
            ->pluck('user_id')
            ->toArray();

        return view('users.index', compact('users', 'usedUserIds'));
    }

    public function create()
    {
        $roles = $this->roles;
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $roles = $this->roles;

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'], // form harus punya password_confirmation
            'role' => ['required', Rule::in($roles)],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => strtolower($request->role),
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roles = $this->roles;
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $roles = $this->roles;

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in($roles)],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => strtolower($request->role),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // ❌ tidak boleh hapus akun yang sedang login
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Tidak bisa menghapus akun yang sedang login.');
        }

        // ❌ tidak boleh hapus user yang sudah pernah diagnosa
        $pernahDiagnosa = RiwayatDiagnosa::where('user_id', $user->id)->exists();
        if ($pernahDiagnosa) {
            return back()->with('error', 'Pengguna ini tidak bisa dihapus karena sudah pernah melakukan diagnosa.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
