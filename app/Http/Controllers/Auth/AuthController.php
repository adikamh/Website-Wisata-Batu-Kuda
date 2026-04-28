<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;


class AuthController
{
    // ── Login ─────────────────────────────────────────────

    public function showLogin()
    {
        return view('Auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('dashboard');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Email atau password tidak sesuai.']);
    }

    public function showRegister()
    {
        return view('Auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'username'              => ['required', 'string', 'max:50', 'unique:users,username', 'regex:/^[a-zA-Z0-9_]+$/'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'Phone'                 => ['nullable', 'string', 'max:20'],
            'Address'               => ['nullable', 'string', 'max:255'],
            'password'              => ['required', 'confirmed', Password::min(8)],
        ], [
            'username.regex'        => 'Username hanya boleh mengandung huruf, angka, dan underscore.',
            'username.unique'       => 'Username sudah digunakan, coba yang lain.',
            'email.unique'          => 'Email sudah terdaftar.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
            'password.min'          => 'Password minimal 8 karakter.',
        ]);

        $user = User::create([
            'name'        => $request->name,
            'username'    => $request->username,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => 'user',  
            'Phone'       => $request->Phone,
            'Address'     => $request->Address,
            'is_verified' => false, 
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('status', 'Akun berhasil dibuat! Selamat datang, ' . $user->name . '.');
    }

    // ── Logout ────────────────────────────────────────────

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}