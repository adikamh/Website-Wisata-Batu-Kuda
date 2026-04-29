<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;

class AuthController
{
    public function showLogin()
    {
        return view('Auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Email atau username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $loginField = filter_var($validated['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($loginField, $validated['login'])->first();

        if ($user && ! $user->is_verified) {
            return back()
                ->withInput($request->only('login', 'remember'))
                ->withErrors([
                    'login' => 'Akun Anda belum terverifikasi. Silakan verifikasi OTP terlebih dahulu.',
                ])
                ->with('verification_email', $user->email);
        }

        $credentials = [
            $loginField => $validated['login'],
            'password' => $validated['password'],
        ];

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'login' => 'Email / username atau password tidak sesuai.',
            ]);
        }

        $request->session()->regenerate();

        return $this->redirectByRole(Auth::user());
    }

    public function showRegister()
    {
        return view('Auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username', 'regex:/^[a-zA-Z0-9_]+$/'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'Phone' => ['nullable', 'string', 'max:20'],
            'Address' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'username.regex' => 'Username hanya boleh mengandung huruf, angka, dan underscore.',
            'username.unique' => 'Username sudah digunakan, coba yang lain.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'Phone' => $request->Phone,
            'Address' => $request->Address,
            'is_verified' => false,
        ]);

        $this->issueOtp($user);

        return redirect()
            ->route('verify.otp', ['email' => $user->email])
            ->with('status', 'Registrasi berhasil. OTP sudah dikirim ke email Anda.')
            ->with('verification_email', $user->email)
            ->with('otp_resent_at', now()->timestamp);
    }

    public function showVerifyOtp(Request $request)
    {
        return view('Auth.verify-otp', [
            'email' => old('email', session('verification_email', $request->query('email'))),
            'resendAvailableAt' => now()->addSeconds(60)->timestamp,
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'digits:6'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.digits' => 'OTP harus terdiri dari 6 digit.',
        ]);

        $user = User::where('email', $validated['email'])
            ->where('otp', $validated['otp'])
            ->first();

        if (! $user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['otp' => 'Kode OTP yang Anda masukkan tidak valid.']);
        }

        if (! $user->otp_expired_at || $user->otp_expired_at->isPast()) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['otp' => 'OTP sudah kedaluwarsa. Silakan kirim ulang OTP baru.']);
        }

        $user->forceFill([
            'is_verified' => true,
            'otp' => null,
            'otp_expired_at' => null,
            'email_verified_at' => now(),
        ])->save();

        return redirect()
            ->route('login')
            ->with('success', 'Verifikasi berhasil, silakan login ke akun Anda.')
            ->with('verification_email', $user->email);
    }

    public function resendOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user) {
            return back()
                ->withErrors(['email' => 'Email tidak ditemukan.'])
                ->withInput();
        }

        if ($user->is_verified) {
            return redirect()
                ->route('login')
                ->with('status', 'Akun ini sudah terverifikasi. Silakan login.');
        }

        $this->issueOtp($user);

        return redirect()
            ->route('verify.otp', ['email' => $user->email])
            ->with('status', 'OTP baru sudah dikirim ke email Anda.')
            ->with('verification_email', $user->email)
            ->with('otp_resent_at', now()->timestamp);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function issueOtp(User $user): void
    {
        $otp = (string) random_int(100000, 999999);
        $otpExpiredAt = now()->addMinutes(5);

        $user->forceFill([
            'otp' => $otp,
            'otp_expired_at' => $otpExpiredAt,
            'is_verified' => false,
        ])->save();

        Mail::raw(
            "Kode OTP verifikasi akun Anda adalah {$otp}. Kode ini berlaku selama 5 menit sampai {$otpExpiredAt->format('H:i')}.",
            function ($message) use ($user): void {
                $message->to($user->email)->subject('Kode OTP Verifikasi Akun');
            }
        );
    }

    private function redirectByRole(User $user)
    {
        return $user->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('home');
    }
}
