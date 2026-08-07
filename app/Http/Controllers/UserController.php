<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{

    public function index()
    {
        // Ambil role user yang sedang login saat ini
        $currentUserRole = Auth::user()->role;

        // $users = User::latest()->paginate(10);
        $users = User::query()
        // JIKA yang login adalah Supervisor, maka FILTER/BUANG data user yang rolenya Admin atau Owner
        ->when($currentUserRole === 'Supervisor', function ($query) {
            return $query->whereNotIn('role', ['Admin', 'Owner']);
        })
        // 🚀 TAMBAHAN: JIKA yang login adalah Owner, FILTER/BUANG data user Admin
        ->when($currentUserRole === 'Owner', function ($query) {
            return $query->where('role', '!=', 'Admin');
        })
        ->latest() 
        ->paginate(10); 

        return view(
            'users.index',
            compact('users')
        );

    }

    public function create()
    {

        return view('users.create');

    }

    public function store(Request $request)
    {

        $request->validate([

            'name'=>'required',

            'email'=>'required|email|unique:users',

            'role'=>'required',

            'password'=>'required|min:6'

        ]);

        User::create([

            'name'=>$request->name,

            'email'=>$request->email,

            'role'=>$request->role,

            'password'=>Hash::make(
                $request->password
            )

        ]);

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'User berhasil ditambahkan.'
            );

    }

    public function edit(User $user)
    {
        // tambahan kalo spv tdk bisa edit admin dan owner
        // Cek jika yang login adalah Supervisor, dan yang mau diedit adalah Admin/Owner
        if (Auth::user()->role === 'Supervisor' && in_array($user->role, ['Admin', 'Owner'])) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah data akun ini.');
        }

        // 🚀 TAMBAHAN: Proteksi jika Owner mencoba mengedit Admin via URL langsung
        if (Auth::user()->role === 'Owner' && $user->role === 'Admin') {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah data akun ini.');
        }

        return view(
            'users.edit',
            compact('user')
        );

    }

    public function update(
        Request $request,
        User $user
    )
    {

        // Cek proteksi yang sama sebelum data sempat disimpan
        if (Auth::user()->role === 'Supervisor' && in_array($user->role, ['Admin', 'Owner'])) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah data akun ini.');
        }

        // 🚀 TAMBAHAN: Proteksi backend untuk Owner sebelum data tersimpan
        if (Auth::user()->role === 'Owner' && $user->role === 'Admin') {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah data akun ini.');
        }
        
        $request->validate([

            'name'=>'required',
            'email'=>'required|email|unique:users,email,'.$user->id,
            'role'=>'required',
            'is_active'=>'required',
            'password'  => 'nullable|min:6',

        ]);

        $data = [

            'name'      => $request->name,

            'email'     => $request->email,

            'role'      => $request->role,

            'is_active' => $request->is_active,

        ];

        // Jika password diisi, update password
        if ($request->filled('password')) {

            $data['password'] = Hash::make($request->password);

        }

        $user->update($data);

        return redirect()

        ->route('users.index')

        ->with(
            'success',
            'User berhasil diperbarui.'
        );

    }

    // reset pwd----------------------------------
    public function resetPassword(User $user)
    {
        $user->update([

            'password'=>Hash::make('87654321')

        ]);

        return back()

            ->with(

                'success',

                'Password berhasil direset menjadi 87654321.'

            );
    }

}