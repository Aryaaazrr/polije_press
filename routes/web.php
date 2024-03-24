<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EditorAkuisisiController;
use App\Http\Controllers\EditorNaskahController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KelolaEditorController;
use App\Http\Controllers\NaskahController;
use App\Http\Controllers\PengelolaController;
use App\Http\Controllers\PenggunaController;
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

        Route::get('admin/naskah', [NaskahController::class, 'index'])->name('admin.naskah');
        Route::get('admin/naskah/data', [NaskahController::class, 'data'])->name('admin.naskah.data');
        Route::get('admin/naskah/datauser', [NaskahController::class, 'dataUser'])->name('admin.naskah.datauser');
        Route::get('admin/naskah/add', [NaskahController::class, 'create'])->name('admin.naskah.create');
        Route::post('admin/naskah/add', [NaskahController::class, 'store'])->name('admin.naskah.store');
        Route::get('admin/naskah/edit/{id}', [NaskahController::class, 'edit'])->name('admin.naskah.edit');
        Route::put('admin/naskah/edit/{id}', [NaskahController::class, 'update'])->name('admin.naskah.update');

        Route::get('admin/kategori', [KategoriController::class, 'index'])->name('admin.kategori');
        Route::get('admin/kategori/data', [KategoriController::class, 'data'])->name('admin.kategori.data');
        Route::post('admin/kategori/add', [KategoriController::class, 'store'])->name('admin.kategori.store');
        Route::put('admin/kategori/{id}', [KategoriController::class, 'update'])->name('admin.kategori.update');
        Route::get('admin/kategori/{id}', [KategoriController::class, 'destroy'])->name('admin.kategori.destroy');

        Route::get('admin/editor', [KelolaEditorController::class, 'index'])->name('admin.editor');
        Route::get('admin/editor/data', [KelolaEditorController::class, 'data'])->name('admin.editor.data');

        Route::get('admin/pengguna', [PenggunaController::class, 'index'])->name('admin.pengguna');
        Route::get('admin/pengguna/data', [PenggunaController::class, 'data'])->name('admin.pengguna.data');
        Route::get('admin/pengguna/add', [PenggunaController::class, 'create'])->name('admin.pengguna.create');
        Route::post('admin/pengguna/add', [PenggunaController::class, 'store'])->name('admin.pengguna.store');
        Route::get('admin/pengguna/edit/{id}', [PenggunaController::class, 'edit'])->name('admin.pengguna.edit');
        Route::put('admin/pengguna/edit/{id}', [PenggunaController::class, 'update'])->name('admin.pengguna.update');
        Route::get('admin/pengguna/{id}', [PenggunaController::class, 'destroy'])->name('admin.pengguna.destroy');

        Route::get('admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
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