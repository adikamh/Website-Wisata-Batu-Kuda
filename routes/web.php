<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminTicketController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\WisataController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\InfoWisataController;

Route::get('/', [WisataController::class, 'dashboard'])->name('home');

Route::get('/gallery-file/{path}', function (string $path) {
    $path = ltrim($path, '/');

    abort_if(str_contains($path, '..'), 404);
    abort_unless(Storage::disk('public')->exists($path), 404);

    return Storage::disk('public')->response($path);
})->where('path', '.*')->name('gallery.image');

Route::get('/infowisata', [InfoWisataController::class, 'index'])->name('infowisata.index');
Route::get('/gallery',[GalleryController::class,'index'])->name('gallery.index');
Route::get('/gallery/{gallery}',[GalleryController::class,'show'])->name('gallery.show');
Route::get('/gallery/{gallery}/komentar',[GalleryController::class,'getKomentar'])->name('gallery.komentar');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::get('/verify-otp', [AuthController::class, 'showVerifyOtp'])->name('verify.otp');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp.submit');
Route::post('/verify-otp/resend', [AuthController::class, 'resendOtp'])->name('verify.otp.resend');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetOtp'])->name('password.email');
Route::get('/reset-otp', [AuthController::class, 'showResetOtp'])->name('reset.otp');
Route::post('/reset-otp', [AuthController::class, 'verifyResetOtp'])->name('reset.otp.submit');
Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('password.update');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', function () {
        return redirect()->route('home');
    })->name('dashboard');

    Route::get('/tiket', [WisataController::class, 'tiket'])->name('tiket');
    Route::post('/tiket', [WisataController::class, 'storeTiket'])->name('tiket.store');
    Route::post('/gallery',[GalleryController::class,'store'])->name('gallery.store');
    Route::put('/gallery/{gallery}',[GalleryController::class,'update'])->name('gallery.update');
    Route::delete('/gallery/{gallery}',[GalleryController::class,'destroy'])->name('gallery.destroy');
    Route::post('/gallery/{gallery}/like',[GalleryController::class,'like'])->name('gallery.like');
    Route::post('/gallery/{gallery}/komentar',[GalleryController::class,'storeKomentar'])->name('gallery.komentar.store');
    Route::delete('/gallery/komentar/{komentar}',[GalleryController::class,'destroyKomentar'])->name('gallery.komentar.destroy');
    Route::get('/lokasi', [LocationController::class,'index'])->name('lokasi.index');
    Route::post  ('/infowisata',[InfoWisataController::class,'store'])->name('infowisata.store');
    Route::put   ('/infowisata/{infoWisata}',[InfoWisataController::class,'update'])->name('infowisata.update');
    Route::delete('/infowisata/{infoWisata}',[InfoWisataController::class,'destroy'])->name('infowisata.destroy');
    Route::post  ('/infowisata/{infoWisata}/poin',[InfoWisataController::class,'storePoin'])->name('infowisata.poin.store');
    Route::put   ('/infowisata/{infoWisata}/poin/{index}',[InfoWisataController::class,'updatePoin'])->name('infowisata.poin.update');
    Route::delete('/infowisata/{infoWisata}/poin/{index}',[InfoWisataController::class,'destroyPoin'])->name('infowisata.poin.destroy');
    Route::delete('/infowisata/{infoWisata}/gambar/{index}',[InfoWisataController::class,'destroyGambar'])->name('infowisata.gambar.destroy');
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminTicketController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/pengguna', [AdminTicketController::class, 'users'])->name('admin.users');
    Route::post('/pengguna', [AdminTicketController::class, 'storeUser'])->name('admin.users.store');
    Route::put('/pengguna/{user}', [AdminTicketController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/pengguna/{user}', [AdminTicketController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::get('/tiket', [AdminTicketController::class, 'index'])->name('admin.tickets');
    Route::post('/tickets', [AdminTicketController::class, 'store'])->name('admin.tickets.store');
    Route::put('/tickets/{ticket}', [AdminTicketController::class, 'update'])->name('admin.tickets.update');
    Route::delete('/tickets/{ticket}', [AdminTicketController::class, 'destroy'])->name('admin.tickets.destroy');
    Route::get('/reports/visitors.pdf', [AdminTicketController::class, 'downloadVisitorPdf'])->name('admin.reports.visitors.pdf');
    Route::get('/reports/finance.xls', [AdminTicketController::class, 'downloadFinanceExcel'])->name('admin.reports.finance.excel');
});

Route::post('/chat', ChatbotController::class);
