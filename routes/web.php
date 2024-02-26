<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EditorAkuisisiController;
use App\Http\Controllers\EditorNaskahController;
use App\Http\Controllers\PengelolaController;
use App\Http\Controllers\PenulisController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('only_sign_in')->group(function () {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/', [AuthController::class, 'authenticate']);
    Route::get('register', [AuthController::class, 'register'])->name('register');
    Route::post('register', [AuthController::class, 'registerProses']);

    Route::get('auth/google', [AuthController::class, 'google'])->name('google-login');
    Route::get('auth/google/callback', [AuthController::class, 'handleGoogle'])->name('google-callback');
});

Route::get('verify', function () {
    return view('auth.verify-email');
});

Route::post('verify', [AuthController::class, 'verify'])->name('verify');

Route::get('/email/verify', function () {
    return view('email.verify');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');



Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    Route::group(['middleware' => 'admin'], function () {
        Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('admin/pengguna', [AdminController::class, 'pengguna'])->name('admin.pengguna');
    });

    Route::group(['middleware' => 'penulis'], function () {
        Route::get('dashboard', [PenulisController::class, 'dashboard'])->name('dashboard');
    });

    Route::group(['middleware' => 'editor.naskah'], function () {
        Route::get('editor-naskah/dashboard', [EditorNaskahController::class, 'dashboard'])->name('editor.naskah.dashboard');
    });

    Route::group(['middleware' => 'editor.akuisisi'], function () {
        Route::get('editor-akuisisi/dashboard', [EditorAkuisisiController::class, 'dashboard'])->name('editor.akuisisi.dashboard');
    });

    Route::group(['middleware' => 'pengelola'], function () {
        Route::get('pengelola/dashboard', [PengelolaController::class, 'dashboard'])->name('pengelola.dashboard');
    });
});