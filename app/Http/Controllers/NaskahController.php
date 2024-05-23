<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\DetailContributorsBuku;
use App\Models\DetailKategoriBuku;
use App\Models\History;
use App\Models\Kategori;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class NaskahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.naskah.index');
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
        $kategori = Kategori::all();
        $role = Role::all();
        $users = User::where('id_role', '==', '2')->with('role')->get();
        return view('pages.admin.naskah.create', ['kategori' => $kategori, 'role' => $role, 'users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->komen == 'komentar') {
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
        } else {
            $validator = Validator::make($request->all(), [
                'judul' => 'required',
                'subjudul' => 'required',
                'abstrak' => 'required',
                'kontributor' => 'required',
                'cover' => 'required|image|mimes:jpeg,png,jpg',
                'file' => 'required|mimes:doc,docx',
                'kategori' => 'required',
                'persyaratan' => 'required',
                'kebijakanPrivasi' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $naskah_path = null;
            $cover_path = null;
            $file_path_naskah = 'uploads/naskah';
            $file_path_cover = 'uploads/cover';

            if ($request->file('cover')) {
                $cover = $request->file('cover');
                $cover_path = $cover->storePublicly($file_path_cover, 'public');
            }

            if ($request->file('file')) {
                $naskah = $request->file('file');
                $naskah_path = $naskah->storePublicly($file_path_naskah, 'public');
            }

            $kontributor = $request->input('kontributor');
            $kontributor_array = json_decode($kontributor);

            $naskah = new Buku();
            $naskah->judul = $request->judul;
            $naskah->subjudul = $request->subjudul;
            $naskah->abstrak = $request->abstrak;
            $naskah->cover = $cover_path;
            $naskah->file = $naskah_path;
            $naskah->status = 'Penyerahan';
            $naskah->id_users = Auth::id();
            $naskah->save();

            $history = new History();
            $history->id_users = Auth::id();
            $history->id_buku = $naskah->id_buku;
            $history->keterangan = Auth::user()->name . " Mengirim file naskah.";
            $history->save();

            foreach ($request->kategori as $kategori_id) {
                $detail_kategori = new DetailKategoriBuku();
                $detail_kategori->id_buku = $naskah->id_buku;
                $detail_kategori->id_kategori = $kategori_id;
                $detail_kategori->save();
            }

            foreach ($kontributor_array as $id_user) {
                $detail_kontributor = new DetailContributorsBuku();
                $detail_kontributor->id_buku = $naskah->id_buku;
                $detail_kontributor->id_users = $id_user;
                $detail_kontributor->save();
            }

            return redirect()->route('admin.naskah')->with('success', 'berhasil.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        return view('pages.admin.naskah.show');
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

        return view('pages.admin.naskah.edit', ['buku' => $buku, 'detailKategoriBuku' => $kategori, 'kategori' => $dataKategori, 'detailContributorBuku' => $users]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return back()->withErrors(['error' => 'Kesalahan sistem coba kembali.']);
        }

        if ($request->status == 'Revisi') {
            $validator = Validator::make($request->all(), [
                'status' => 'required',
                'keterangan' => 'required',
            ]);

            if ($validator->fails()) {
                return back()
                    ->with('error', 'Naskah tidak ditemukan.');
            }

            $buku->status = $request->status;
            $buku->save();

            $history = History::create([
                'id_buku' => $id,
                'id_users' => Auth::id(),
                'keterangan' => Auth::user()->name . " memberi keputusan bahwa naskah perlu revisi dengan catatan " . $request->keterangan . ".",
            ]);
        } elseif ($request->status == 'Ditolak') {
            $validator = Validator::make($request->all(), [
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return back()
                    ->with('error', 'Naskah tidak ditemukan.');
            }

            $buku->status = $request->status;
            $buku->save();

            $history = History::create([
                'id_buku' => $id,
                'id_users' => Auth::id(),
                'keterangan' => Auth::user()->name . " memberi keputusan bahwa naskah '" . $request->status . "'.",
            ]);
        } elseif ($request->status == 'Layak terbit') {
            $validator = Validator::make($request->all(), [
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return back()
                    ->with('error', 'Naskah tidak ditemukan.');
            }

            $loa_path = null;
            $file_path_loa = 'uploads/loa';

            if ($request->file('file')) {
                $loa = $request->file('file');
                $loa_path = $loa->storePublicly($file_path_loa, 'public');
            }

            $buku->loa = $loa_path;
            $buku->publish = Carbon::now();
            $buku->save();
            $history = History::create([
                'id_buku' => $id,
                'id_users' => Auth::id(),
                'keterangan' => Auth::user()->name . " memberi keputusan bahwa naskah " . $request->status . ".",
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return back()
                    ->with('error', 'Naskah tidak ditemukan.');
            }

            $buku->status = $request->status;
            $buku->save();
            $history = History::create([
                'id_buku' => $id,
                'id_users' => Auth::id(),
                'keterangan' => Auth::user()->name . " memberi keputusan bahwa naskah " . $request->status . ".",
            ]);
        }

        return back()->with(['success' => 'Berhasil memberi keputusan.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
