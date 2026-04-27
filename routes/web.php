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
Route::post('/logout', function () {
Auth::logout();return redirect()->route('home'); })->name('logout');
Route::get('/forgot-password', function () {return view('Auth.forgot-password'); })->name('password.request');