<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\DetailContributorsBuku;
use App\Models\DetailKategoriBuku;
use App\Models\History;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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

    public function dataTerbit()
    {
        $query = Buku::query();

        $query->where('status', 'Diterima')->whereNot('publish', null);
        $data = $query->with(['users', 'history'])->get();
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
                'tanggalTerbit' => $row->publish ?? '-',
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
        $cek = Buku::find($request->id_buku);

        if (!$cek) {
            return back()->withErrors(['error' => 'Kesalahan sistem coba kembali.']);
        }

        $validator = Validator::make($request->all(), [
            'komentar' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $naskah = new History();
        $naskah->id_users = Auth::id();
        $naskah->id_buku = $request->id_buku;
        $naskah->keterangan = Auth::user()->name . " Memberi komentar " . $request->komentar;
        $naskah->save();

        return back()->with(['success' => 'Berhasil memberi komentar.']);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        return view('pages.pengelola.naskah.show');
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