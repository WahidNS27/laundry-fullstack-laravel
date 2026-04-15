<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        // Hanya menampilkan level Operator
        $users = User::with('level')->whereHas('level', function($q) {
            $q->where('level_name', 'Operator');
        })->latest()->paginate(10);
        
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        // Ambil level Operator
        $levelOperator = Level::where('level_name', 'Operator')->firstOrFail();
        return view('admin.users.create', compact('levelOperator'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_level' => ['required', 'exists:level,id'],
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:user,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'id_level' => $request->id_level,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User (Operator) berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:user,email,'.$user->id],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
        }

        $request->validate($rules);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
