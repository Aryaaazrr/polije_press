<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('id_role', '!=', '1')->with('role')->get();
        return view('pages.admin.pengguna.index', compact('users'));
    }

    public function data()
    {
        $users = User::where('id_role', '!=', '1')->with('role')->get();

        return DataTables::of($users)
            ->addColumn('DT_RowIndex', function ($user) {
                return $user->id_users;
            })
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $role = Role::whereNotIn('id_role', [1, 2])->get();
        return view('pages.admin.pengguna.create', compact('role'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'username' => 'required|max:255|unique:users',
            'password' => 'required|min:8',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user = new User();
            $user->name = $request->name;
            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->id_role = $request->role;
            $user->save();

            Session::flash('success', 'Pengguna berhasil ditambahkan.');
            return redirect()->route('admin.pengguna');
        } catch (QueryException $e) {
            return back()->withInput()->withErrors(['error' => 'Gagal menambahkan pengguna. Silakan coba lagi.']);
        }
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
        $users = User::with('role')->find($id);
        $userRoleId = $users->id_role;
        $role = Role::whereNotIn('id_role', [1, 2, $userRoleId])->get();
        return view('pages.admin.pengguna.edit', ['users' => $users, 'role' => $role]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return back()->withErrors(['error' => 'Pengguna tidak ditemukan.']);
        }

        $validator = Validator::make($request->all(), [
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->id_role = $request->role;
        $user->save();

        return redirect()->route('admin.pengguna')->with('success', 'Peran pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $users = User::where('id_users', $id)->first();
        $users->delete();

        return redirect()->route('admin.pengguna')->with('success', 'Pengguna berhasil dihapus');
    }
}