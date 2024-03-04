<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Logout jika sudah login
        Auth::logout();

        $credentials = $request->validate([
            'Email' => 'required|email',
            'Password' => 'required',
        ]);

        // Kueri untuk mencari user dengan email yang sesuai
        $user = User::where('Email', $credentials['Email'])->first();

        // Cek apakah user ditemukan
        if ($user) {
            // Bandingkan hash password dengan password yang diinput
            if (password_verify($credentials['Password'], $user->Password)) {
                // Password sesuai
                \Log::info('Password matches for email: ' . $credentials['Email']);

                // Login manual
                Auth::login($user);

                // Autentikasi sukses
                return redirect()->intended('/');
            } else {
                // Password tidak sesuai
                return redirect()->route('login')->with('error', 'Password or Email does not match');
            }
        } else {
            // User tidak ditemukan
            return redirect()->route('login')->with('error', 'Password or Email does not match');
        }
    }
    public function logout()
    {
        Auth::logout();

        // Simpan log ke laravel error log
        \Log::info('User logged out.');

        return redirect()->route('home.index');
    }
}
