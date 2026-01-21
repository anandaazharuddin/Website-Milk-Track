<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // HAPUS middleware dari sini karena sudah di route
    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('isAdmin');
    // }

    public function index()
    {
        $users = User::latest()->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'role' => 'required|in:admin,peternak',
            'email' => 'nullable|email|max:255',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password), // Hash manual
            'role' => $request->role,
            'email' => $request->email,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dibuat');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|confirmed|min:6',
            'role' => 'required|in:admin,peternak',
            'email' => 'nullable|email|max:255',
        ]);

        $data = $request->only('name', 'username', 'role', 'email');
        
        // Hanya update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diupdate');
    }

    public function destroy(User $user)
    {
        // Cegah admin menghapus dirinya sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak bisa menghapus akun sendiri');
        }

        $user->delete();
        
        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus');
    }
}