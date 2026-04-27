<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WisataController;

Route::get('/', [WisataController::class, 'dashboard'])->name('home');