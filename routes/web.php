<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\WisataController;
use App\Http\Controllers\Auth\AuthController;

Route::get('/', [WisataController::class, 'dashboard'])->name('home');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', function () {return view('Auth.forgot-password'); })->name('password.request');
Route::middleware('auth')->get('dashboard', function () {
	return view('dashboard');
})->name('dashboard');

Route::middleware(['auth'])->get('admin/dashboard', function () {
	if (Auth::user()->role !== 'admin') {
		return redirect()->route('dashboard');
	}
	return view()->file(resource_path('views/Admin/admin.dashboard.blade.php'));
})->name('admin.dashboard');

