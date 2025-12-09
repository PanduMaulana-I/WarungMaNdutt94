<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * 🧾 Tampilkan form login penjual
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * 🔐 Proses login penjual
     */
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            $request->session()->regenerate();

            // Pastikan yang login adalah seller
            if (Auth::user()->role !== 'seller') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun ini bukan penjual. Silakan login sebagai pembeli.'
                ])->onlyInput('email');
            }

            // 🔥 POPUP KHUSUS LOGIN — TIDAK BENTROK SESSION LAIN
            $sellerName = Auth::user()->name;
            $message = "Selamat datang kembali, Admin Warung MasNdutt94 — {$sellerName}!";

            return redirect()
                ->intended(route('seller.dashboard'))
                ->with('login_success', $message); // ⬅ SESSION BARU
        }

        // Jika gagal login
        return back()->withErrors([
            'email' => 'Email atau password tidak sesuai.',
        ])->onlyInput('email');
    }

    /**
     * 🚪 Logout penjual
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login.choice')
            ->with('logout_success', 'Anda berhasil logout. Sampai jumpa lagi!'); // ⬅ SESSION BARU
    }
}
