<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->id_role == 1) {
            return view('pages.admin.profile.index');
        } elseif (Auth::user()->id_role == 2) {
            return view('pages.penulis.profile.index');
        } elseif (Auth::user()->id_role == 3) {
            return view('pages.editorNaskah.profile.index');
        } elseif (Auth::user()->id_role == 4) {
            return view('pages.editorAkuisisi.profile.index');
        } else {
            return view('pages.pengelola.profile.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $users = User::find($id);

        if (!$users) {
            return back()->withErrors(['error' => 'Kesalahan sistem coba kembali.']);
        }

        if ($request->up_password == 'up_password') {
            $validator = Validator::make($request->all(), [
                'password' => 'required|min:6',
                'newpassword' => 'required|min:6',
                'renewpassword' => 'required|min:6|same:newpassword',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $newpassword = $request->input('newpassword');

            $users->password = Hash::make($newpassword);
            $users->save();

            return redirect()->back()->with('success', 'Password berhasil diperbarui');
        } else {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'username' => 'required',
                'email' => 'required', 'email',
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $users->name = $request->name;
            $users->username = $request->username;
            $users->email = $request->email;
            $users->save();

            if (Auth::user()->id_role == '1') {
                return redirect()->route('admin.profile')->with('success', 'Profil berhasil diperbarui.');
            } elseif (Auth::user()->id_role == '2') {
                return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
            } elseif (Auth::user()->id_role == '3') {
                return redirect('editor-naskah/dashboard');
            } elseif (Auth::user()->id_role == '4') {
                return redirect('editor-akuisisi/dashboard');
            } else {
                return redirect('pengelola/dashboard');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}