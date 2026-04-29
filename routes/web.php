<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\WisataController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;

Route::get('/', [WisataController::class, 'dashboard'])->name('home');
Route::get('/tiket', [WisataController::class, 'tiket'])->name('tiket');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::get('/verify-otp', [AuthController::class, 'showVerifyOtp'])->name('verify.otp');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp.submit');
Route::post('/verify-otp/resend', [AuthController::class, 'resendOtp'])->name('verify.otp.resend');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', function () {
    return view('Auth.forgot-password');
})->name('password.request');

Route::middleware('auth')->get('dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth'])->get('admin/dashboard', function () {
    if (Auth::user()->role !== 'admin') {
        return redirect()->route('home');
    }

    return view()->file(resource_path('views/Admin/admin.dashboard.blade.php'));
})->name('admin.dashboard');
Route::post('/chat', ChatbotController::class);