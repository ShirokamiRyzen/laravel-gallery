<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    // Store data register
    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'NamaLengkap' => ['required', 'string', 'max:255'],
            'Username' => ['required', 'string', 'max:255', 'unique:users'],
            'Email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'Alamat' => ['required', 'string', 'max:255'],
            'Password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Simpan data dengan fungsi attribute
        $attributes = [
            'NamaLengkap' => $request->input('NamaLengkap'),
            'Username' => $request->input('Username'),
            'Email' => $request->input('Email'),
            'Alamat' => $request->input('Alamat'),
            'Password' => bcrypt($request->input('Password')),
        ];

        // Memasukkan data ke database dari value attribute
        $user = User::create($attributes);

        auth()->login($user);

        return redirect()->route('home.index')->with('success', 'Account created successfully');;
    }
}
