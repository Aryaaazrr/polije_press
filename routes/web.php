<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EditorAkuisisiController;
use App\Http\Controllers\EditorNaskahController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KelolaEditorController;
use App\Http\Controllers\KelolaEditorPengelolaController;
use App\Http\Controllers\NaskahController;
use App\Http\Controllers\NaskahPengelolaController;
use App\Http\Controllers\NaskahPenulisController;
use App\Http\Controllers\PengelolaController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PenulisController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TugasEditorAkuisisiController;
use App\Http\Controllers\TugasEditorNaskahController;
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
        Route::get('admin/editor/data/{id}', [KelolaEditorController::class, 'data'])->name('admin.editor.data');
        Route::get('admin/editor/dataeditor', [KelolaEditorController::class, 'dataEditor'])->name('admin.editor.dataeditor');
        Route::get('admin/editor/{id}', [KelolaEditorController::class, 'edit'])->name('admin.editor.edit');
        Route::put('admin/editor/{id}', [KelolaEditorController::class, 'update'])->name('admin.editor.update');

        Route::get('admin/pengguna', [PenggunaController::class, 'index'])->name('admin.pengguna');
        Route::get('admin/pengguna/data', [PenggunaController::class, 'data'])->name('admin.pengguna.data');
        Route::get('admin/pengguna/add', [PenggunaController::class, 'create'])->name('admin.pengguna.create');
        Route::post('admin/pengguna/add', [PenggunaController::class, 'store'])->name('admin.pengguna.store');
        Route::get('admin/pengguna/edit/{id}', [PenggunaController::class, 'edit'])->name('admin.pengguna.edit');
        Route::put('admin/pengguna/edit/{id}', [PenggunaController::class, 'update'])->name('admin.pengguna.update');
        Route::get('admin/pengguna/{id}', [PenggunaController::class, 'destroy'])->name('admin.pengguna.destroy');

        Route::get('admin/profile', [SettingController::class, 'index'])->name('admin.profile');
        Route::put('admin/profile/{id}', [SettingController::class, 'update'])->name('admin.profile.update');
    });

    Route::group(['middleware' => 'penulis'], function () {
        Route::get('dashboard', [PenulisController::class, 'dashboard'])->name('dashboard');

        Route::get('naskah', [NaskahPenulisController::class, 'index'])->name('naskah');
        Route::get('naskah/data', [NaskahPenulisController::class, 'data'])->name('naskah.data');
        Route::get('naskah/datauser', [NaskahPenulisController::class, 'dataUser'])->name('naskah.datauser');
        Route::get('naskah/add', [NaskahPenulisController::class, 'create'])->name('naskah.create');
        Route::post('naskah/add', [NaskahPenulisController::class, 'store'])->name('naskah.store');
        Route::get('naskah/{id}', [NaskahPenulisController::class, 'show'])->name('naskah.show');
        Route::get('naskah/edit/{id}', [NaskahPenulisController::class, 'edit'])->name('naskah.edit');
        Route::put('naskah/edit/{id}', [NaskahPenulisController::class, 'update'])->name('naskah.update');

        Route::get('profile', [SettingController::class, 'index'])->name('profile');
        Route::put('profile/{id}', [SettingController::class, 'update'])->name('profile.update');
    });

    Route::group(['middleware' => 'editor.naskah'], function () {
        Route::get('editor-naskah/dashboard', [EditorNaskahController::class, 'dashboard'])->name('editor.naskah.dashboard');

        Route::get('editor-naskah/tugas', [TugasEditorNaskahController::class, 'index'])->name('editor.naskah.tugas');
        Route::get('editor-naskah/tugas/data', [TugasEditorNaskahController::class, 'data'])->name('editor.naskah.data');
        Route::get('editor-naskah/tugas/{id}', [TugasEditorNaskahController::class, 'edit'])->name('editor.naskah.edit');
        Route::put('editor-naskah/tugas/{id}', [TugasEditorNaskahController::class, 'update'])->name('editor.naskah.update');

        Route::get('editor-naskah/profile', [SettingController::class, 'index'])->name('editor.naskah.profile');
    });

    Route::group(['middleware' => 'editor.akuisisi'], function () {
        Route::get('editor-akuisisi/dashboard', [EditorAkuisisiController::class, 'dashboard'])->name('editor.akuisisi.dashboard');

        Route::get('editor-akuisisi/tugas', [TugasEditorAkuisisiController::class, 'index'])->name('editor.akuisisi.tugas');
        Route::get('editor-akuisisi/tugas/data', [TugasEditorAkuisisiController::class, 'data'])->name('editor.akuisisi.data');
        Route::get('editor-akuisisi/tugas/{id}', [TugasEditorAkuisisiController::class, 'edit'])->name('editor.akuisisi.edit');
        Route::put('editor-akuisisi/tugas/{id}', [TugasEditorAkuisisiController::class, 'update'])->name('editor.akuisisi.update');

        Route::get('editor-akuisisi/profile', [SettingController::class, 'index'])->name('editor.akuisisi.profile');
    });

    Route::group(['middleware' => 'pengelola'], function () {
        Route::get('pengelola/dashboard', [PengelolaController::class, 'dashboard'])->name('pengelola.dashboard');

        Route::get('pengelola/naskah', [NaskahPengelolaController::class, 'index'])->name('pengelola.naskah');
        Route::get('pengelola/naska/data', [NaskahPengelolaController::class, 'data'])->name('pengelola.naskah.data');
        Route::get('pengelola/naskah/datauser', [NaskahPengelolaController::class, 'dataUser'])->name('pengelola.naskah.datauser');
        Route::get('pengelola/naskah/add', [NaskahPengelolaController::class, 'create'])->name('pengelola.naskah.create');
        Route::post('pengelola/naskah/add', [NaskahPengelolaController::class, 'store'])->name('pengelola.naskah.store');
        Route::get('pengelola/naskah/edit/{id}', [NaskahPengelolaController::class, 'edit'])->name('pengelola.naskah.edit');
        Route::put('pengelola/naskah/edit/{id}', [NaskahPengelolaController::class, 'update'])->name('pengelola.naskah.update');

        Route::get('pengelola/editor', [KelolaEditorPengelolaController::class, 'index'])->name('pengelola.editor');
        Route::get('pengelola/editor/data/{id}', [KelolaEditorPengelolaController::class, 'data'])->name('pengelola.editor.data');
        Route::get('pengelola/editor/dataeditor', [KelolaEditorPengelolaController::class, 'dataEditor'])->name('pengelola.editor.dataeditor');
        Route::get('pengelola/editor/{id}', [KelolaEditorPengelolaController::class, 'edit'])->name('pengelola.editor.edit');
        Route::put('pengelola/editor/{id}', [KelolaEditorPengelolaController::class, 'update'])->name('pengelola.editor.update');

        Route::get('pengelola/profile', [SettingController::class, 'index'])->name('pengelola.profile');
    });
});