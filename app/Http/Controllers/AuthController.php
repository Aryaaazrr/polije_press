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
                'username' => 'required|max:255',
                'email' => 'required|unique:users|email',
                'password' => 'required|min:8|regex:/^(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{8,}$/|max:255',
                'confirm-password' => 'required|same:password',
            ]);

            if ($validator->fails()) {
                return redirect('register')
                    ->withErrors($validator)
                    ->withInput();
            }

            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => Carbon::now(),
                'id_role' => 2
            ]);

            // event(new Registered($user));

            // Auth::login($user);

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

    public function google()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogle()
    {
        $googleUser = Socialite::driver('google')->user();

        $registeredUser = User::where('email', $googleUser->email)->first();

        if (!$registeredUser) {
            $user = User::updateOrCreate([
                'google_id' => $googleUser->id,
            ], [
                'name' => $googleUser->name,
                'username' => $googleUser->name,
                'email' => $googleUser->email,
                'password' => Hash::make('12345678'),
                'google_token' => $googleUser->token,
                'google_refresh_token' => $googleUser->refreshToken,
                'email_verified_at' => Carbon::now()
            ]);

            Auth::login($user);

            return redirect('/dashboard');
        }
        Auth::login($registeredUser);

        return redirect('/dashboard');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $registeredUser = User::where('email', $request->email)->first();

        if ($registeredUser) {

            if (Auth::attempt($credentials)) {
                if (Auth::user()->email_verified_at == '') {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return back()->withInput()->with('msg', '<div class="alert alert-danger alert-dismissible text-white" role="alert">
                    <span class="text-sm">Silahkan cek email untuk verifikasi akun.</span>
                    <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>');
                }

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

        // Auth::login($registeredUser);

        // if (Auth::user()->id_role == '1') {
        //     return redirect('admin/dashboard');
        // } elseif (Auth::user()->id_role == '2') {
        //     return redirect('dashboard');
        // } elseif (Auth::user()->id_role == '3') {
        //     return redirect('editor-naskah/dashboard');
        // } elseif (Auth::user()->id_role == '4') {
        //     return redirect('editor-akuisisi/dashboard');
        // } else {
        //     return redirect('pengelola/dashboard');
        // }
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = $request->email;

        $cek = User::select('email')->where('email', $email)->groupBy('email')->first();
        if ($cek) {
            $token = Str::random(10);

            // Simpan data verifikasi email ke dalam database
            $cek = TokenAcsess::create([
                'email' => $email,
                'token' => $token,
            ]);
            event(new Registered($cek));

            Auth::login($cek);

            return redirect('email/verify');
        } else {
            return redirect('login')->with('msg', '<div class="alert alert-danger alert-dismissible text-white" role="alert">
            <span class="text-sm">Kesalahan sistem.</span>
            <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            </div>');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
