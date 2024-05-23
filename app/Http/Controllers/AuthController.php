<?php

namespace App\Http\Controllers;

use App\Models\TokenAcsess;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function registerProses(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|max:255|regex:/^\S*$/u',
                'password' => 'required|min:8|max:255',
                'confirm-password' => 'required|same:password',
            ]);

            if ($validator->fails()) {
                return redirect('register')
                    ->withErrors($validator)
                    ->withInput();
            }

            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'id_role' => 2
            ]);

            DB::commit();

            return redirect('/')->with('msg', '<div class="alert alert-success alert-dismissible text-white" role="alert">
            <span class="text-sm">Daftar akun berhasil.</span>
            <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['email' => 'Daftar akun gagal. Silakan coba lagi.']);
        }
    }

    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        $cekDataPengguna = User::where('username', $request->username)->first();

        if ($cekDataPengguna) {
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                if (Auth::user()->id_role == '1') {
                    return redirect('admin/dashboard');
                } elseif (Auth::user()->id_role == '2') {
                    return redirect('dashboard');
                } elseif (Auth::user()->id_role == '3') {
                    return redirect('editor-naskah/dashboard');
                } elseif (Auth::user()->id_role == '4') {
                    return redirect('editor-akuisisi/dashboard');
                } else {
                    return redirect('pengelola/dashboard');
                }
            } else {
                return back()->withInput()->with(
                    'msg',
                    '<div class="alert alert-danger alert-dismissible text-white" role="alert">
        <span class="text-sm">Email atau password salah.</span>
        <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>'
                );
            }
        }
        return back()->withInput()->with('msg', '<div class="alert alert-danger alert-dismissible text-white" role="alert">
        <span class="text-sm">Akun Tidak Ditemukan. Silahkan Daftar akun terlebih dahulu.</span>
        <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
