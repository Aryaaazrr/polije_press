<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\History;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class NaskahPengelolaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.pengelola.naskah.index');
    }

    public function data()
    {
        $data = Buku::with(['users', 'history'])->get();
        $rowData = [];

        foreach ($data as $row) {
            $historyRows = History::with(['users', 'buku'])
                ->where('id_buku', $row->id_buku)
                ->get();

            $rowData[] = [
                'id_buku' => $row->id_buku,
                'DT_RowIndex' => $row->id_buku,
                'penulis' => $row->users->name ?? '-',
                'judul' => $row->judul ?? '-',
                'subjudul' => $row->subjudul ?? '-',
                'status' => $row->status ?? '-',
                'historyRows' => $historyRows,
            ];
        }

        return DataTables::of($rowData)->toJson();
    }

    public function dataUser()
    {
        $users = User::where('id_role', '=', '2')->with('role')->get();

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
        $buku = Buku::with(['history', 'detailKategoriBuku', 'detailContributorBuku'])
            ->find($id);

        $detailKategoriBuku = $buku->detailKategoriBuku;
        $kategori = Kategori::whereIn('id_kategori', $detailKategoriBuku->pluck('id_kategori'))->get();

        $detailContributorBuku = $buku->detailContributorBuku;
        $users = User::whereIn('id_users', $detailContributorBuku->pluck('id_users'))->get();

        $dataKategori = Kategori::all();

        return view('pages.pengelola.naskah.edit', ['buku' => $buku, 'detailKategoriBuku' => $kategori, 'kategori' => $dataKategori, 'detailContributorBuku' => $users]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}