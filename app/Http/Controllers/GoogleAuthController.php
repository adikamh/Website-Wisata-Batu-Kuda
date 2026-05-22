<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    private const SESSION_KEY = 'google_auth_user';

    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('login')
                ->withErrors(['login' => 'Login Google gagal. Silakan coba lagi.']);
        }

        if (! $googleUser->getEmail()) {
            return redirect()
                ->route('login')
                ->withErrors(['login' => 'Akun Google tidak mengirimkan email yang valid.']);
        }

        $user = User::query()
            ->where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            $user->forceFill([
                'google_id' => $googleUser->getId(),
                'email_verified_at' => $user->email_verified_at ?: now(),
                'is_verified' => true,
            ])->save();

            Auth::login($user);
            $request->session()->regenerate();

            return $this->redirectByRole($user);
        }

        $request->session()->put(self::SESSION_KEY, [
            'id' => $googleUser->getId(),
            'name' => $googleUser->getName() ?: Str::before($googleUser->getEmail(), '@'),
            'email' => $googleUser->getEmail(),
            'avatar' => $googleUser->getAvatar(),
            'suggested_username' => $this->suggestUsername($googleUser->getName(), $googleUser->getEmail()),
        ]);

        return redirect()
            ->route('auth.google.complete')
            ->with('status', 'Lengkapi data akun Google Anda sebelum masuk.');
    }

    public function showCompletionForm(Request $request)
    {
        $googleUser = $request->session()->get(self::SESSION_KEY);

        if (! $googleUser) {
            return redirect()
                ->route('login')
                ->withErrors(['login' => 'Sesi login Google sudah berakhir. Silakan coba lagi.']);
        }

        return view('Auth.google-complete', compact('googleUser'));
    }

    public function completeRegistration(Request $request)
    {
        $googleUser = $request->session()->get(self::SESSION_KEY);

        if (! $googleUser) {
            return redirect()
                ->route('login')
                ->withErrors(['login' => 'Sesi login Google sudah berakhir. Silakan coba lagi.']);
        }

        $existingUser = User::where('email', $googleUser['email'])->first();

        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9_]+$/',
                Rule::unique('users', 'username')->ignore($existingUser?->id),
            ],
            'Phone' => ['nullable', 'string', 'max:20'],
            'Address' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.regex' => 'Username hanya boleh mengandung huruf, angka, dan underscore.',
            'username.unique' => 'Username sudah digunakan, coba yang lain.',
        ]);

        $user = User::updateOrCreate(
            ['email' => $googleUser['email']],
            [
                'name' => $googleUser['name'],
                'username' => $validated['username'],
                'google_id' => $googleUser['id'],
                'password' => $existingUser?->password ?: Hash::make(Str::random(40)),
                'role' => $existingUser?->role ?: 'user',
                'Phone' => $validated['Phone'] ?? null,
                'Address' => $validated['Address'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'is_verified' => true,
                'email_verified_at' => $existingUser?->email_verified_at ?: now(),
            ]
        );

        Auth::login($user);
        $request->session()->forget(self::SESSION_KEY);
        $request->session()->regenerate();

        return $this->redirectByRole($user);
    }

    private function suggestUsername(?string $name, string $email): string
    {
        $base = Str::of($name ?: Str::before($email, '@'))
            ->lower()
            ->replaceMatches('/[^a-z0-9_]+/', '_')
            ->trim('_')
            ->substr(0, 20)
            ->value();

        $base = $base !== '' ? $base : 'user';
        $username = $base;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $suffix = '_' . $counter++;
            $username = Str::substr($base, 0, 50 - strlen($suffix)) . $suffix;
        }

        return $username;
    }

    private function redirectByRole(User $user)
    {
        return $user->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('home');
    }
}
