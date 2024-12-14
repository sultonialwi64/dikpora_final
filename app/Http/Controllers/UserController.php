<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Menampilkan data user dengan role 'user'.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengambil data user dengan role 'user'
        $users = User::where('role', 'user')->get();

        // Mengirimkan data ke view
        return view('admin.user', compact('users'));
    }

    /**
     * Menambah data user baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'no_pegawai' => 'required|string|max:255|unique:users,no_pegawai',
            'bidang' => 'required|string|max:255',
            'telp' => 'required|string|max:15',
        ]);

        // Menambahkan user baru
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_pegawai' => $request->no_pegawai,
            'bidang' => $request->bidang,
            'telp' => $request->telp,
            'password' => Hash::make($request->no_pegawai), // Menggunakan no_pegawai sebagai password
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }
}
