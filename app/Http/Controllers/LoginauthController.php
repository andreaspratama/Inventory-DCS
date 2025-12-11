<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginauthController extends Controller
{
    public function login()
    {
        return view('welcome'); // buat file login.blade.php
    }

    public function loginPros(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Coba login
        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            // Cek role
            if (Auth::user()->role == 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Berhasil login sebagai admin');
            }

            if (Auth::user()->role == 'sarpra') {
                return redirect()->route('sarpra.dashboard')
                    ->with('success', 'Berhasil login sebagai sarpra');
            }

            return redirect()->back()->with('error', 'Role tidak dikenali.');
        }

        // Jika gagal login → kasih notif error
        return redirect()->back()
            ->with('error', 'Email atau password salah!')
            ->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
