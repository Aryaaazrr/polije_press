<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\History;
use App\Models\TugasEditor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class KelolaEditorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.editor.index');
    }

    // public function data()
    // {

    //     $data = Buku::with('users')
    //         ->whereHas('buku', function ($query) {
    //             $query->where('status', 'Penyerahan');
    //         })
    //         ->get();

    //     $rowData = [];

    //     foreach ($data as $row) {
    //         if (!empty($row->buku->id_users)) {
    //             $editor = User::find($row->buku->id_users);
    //             $status = $editor->name ?? '-';
    //         } else {
    //             $status = 'Belum Direview';
    //         }

    //         $rowData[] = [
    //             'DT_RowIndex' => $row->id_buku,
    //             'penulis' => $row->users->username ?? '-',
    //             'judul' => $row->buku->judul ?? '-',
    //             'subjudul' => $row->buku->subjudul ?? '-',
    //             'status' => $row->buku->status ?? '-',
    //             'editor' => $status
    //         ];
    //     }

    //     return response()->json(['data' => $rowData]);
    // }

    public function data(string $id)
    {
        $query = Buku::query();

        $cekroleeditor = User::with('role')->find($id);

        if (!$cekroleeditor) {
            return response()->json(['error' => 'Editor tidak ditemukan'], 404);
        }

        if ($cekroleeditor->role->nama_role === 'Editor Naskah') {
            $query->where('status', 'Diterima')->where('publish', null);
            $data = $query->with(['users', 'history'])->get();

            $rowData = [];
            foreach ($data as $row) {
                $rowData[] = [
                    'id_editor' => $cekroleeditor->id_users,
                    'DT_RowIndex' => $row->id_buku,
                    'penulis' => $row->users->name ?? '-',
                    'judul' => $row->judul ?? '-',
                    'subjudul' => $row->subjudul ?? '-',
                    'status' => $row->status ?? '-',
                    'editorType' => $cekroleeditor->role->nama_role,
                ];
            }

            return response()->json(['data' => $rowData]);
        } else {
            $query->where('status', 'Penyerahan');
            $data = $query->with(['users', 'history'])->get();

            $rowData = [];
            foreach ($data as $row) {
                $rowData[] = [
                    'id_editor' => $cekroleeditor->id_users,
                    'DT_RowIndex' => $row->id_buku,
                    'penulis' => $row->users->name ?? '-',
                    'judul' => $row->judul ?? '-',
                    'subjudul' => $row->subjudul ?? '-',
                    'status' => $row->status ?? '-',
                    'editorType' => $cekroleeditor->role->nama_role,
                ];
            }

            return response()->json(['data' => $rowData]);
        }
    }

    public function dataEditor()
    {
        $users = User::whereNotIn('id_role', [1, 2, 5])->with('role')->get();

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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
        return view('pages.admin.editor.edit', ['id' => $id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $editor = User::find($id);

        if (!$editor) {
            return redirect()->route('admin.editor')->with('error', 'Kesalahan coba kembali.');
        }

        $validator = Validator::make($request->all(), [
            'id_buku' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->with('error', 'Naskah tidak ditemukan.');
        }

        $buku = Buku::find($request->id_buku);

        if ($buku->status == 'Penyerahan') {
            $buku->status = 'Sedang Direview';
            $buku->save();

            $history = History::create([
                'id_buku' => $request->id_buku,
                'id_users' => $id,
                'keterangan' => Auth::user()->name . " menugaskan " . $editor->name . " sebagai Editor Naskah untuk mereview naskah.",
            ]);
        } else {
            $history = History::create([
                'id_buku' => $request->id_buku,
                'id_users' => $id,
                'keterangan' => Auth::user()->name . " menugaskan " . $editor->name . " sebagai Editor Akuisisi untuk menentukan penerbitan naskah.",
            ]);
        }

        return redirect()->route('admin.editor')->with('success', 'Tugas berhasil dikirim.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}