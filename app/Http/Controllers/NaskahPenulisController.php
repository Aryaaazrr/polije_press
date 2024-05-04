<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\DetailContributorsBuku;
use App\Models\DetailKategoriBuku;
use App\Models\History;
use App\Models\Kategori;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class NaskahPenulisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.penulis.naskah.index');
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
        $loggedInUserId = Auth::id();
        $users = User::where('id_role', '=', '2')->whereNotIn('id_users', [$loggedInUserId])->with('role')->get();

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
        return view('pages.penulis.naskah.create', ['kategori' => $kategori, 'role' => $role, 'users' => $users]);
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
            $naskah->seri = $request->seri;
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

            $detail_kontributor = new DetailContributorsBuku();
            $detail_kontributor->id_buku = $naskah->id_buku;
            $detail_kontributor->id_users = Auth::id();
            $detail_kontributor->save();

            foreach ($kontributor_array as $id_user) {
                $detail_kontributor = new DetailContributorsBuku();
                $detail_kontributor->id_buku = $naskah->id_buku;
                $detail_kontributor->id_users = $id_user;
                $detail_kontributor->save();
            }


            return redirect()->route('naskah')->with('success', 'Naskah berhasil dikirim');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $buku = Buku::with(['history', 'detailKategoriBuku', 'detailContributorBuku'])
            ->find($id);

        if (!$buku) {
            return redirect()->route('naskah')->with('error', 'Kesalahan coba kembali.');
        }

        $detailKategoriBuku = $buku->detailKategoriBuku;
        $kategoriTerhubung = $detailKategoriBuku->pluck('id_kategori')->toArray();
        $kategori = Kategori::whereIn('id_kategori', $detailKategoriBuku->pluck('id_kategori'))->get();

        // Mendapatkan kategori yang tidak terhubung dengan buku
        $datakategori = Kategori::whereNotIn('id_kategori', $kategoriTerhubung)->get();

        $detailContributorBuku = $buku->detailContributorBuku;
        $users = User::whereIn('id_users', $detailContributorBuku->pluck('id_users'))->get();

        return view('pages.penulis.naskah.show', [
            'buku' => $buku,
            'detailKategoriBuku' => $kategori,
            'kategori' => $datakategori,
            'detailContributorBuku' => $users
        ]);
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
        $buku = Buku::find($id);
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'subjudul' => 'required',
            'abstrak' => 'required',
            'file' => 'required|mimes:doc,docx',
            'kategori.*' => 'required', // Memastikan setiap kategori terpilih
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $naskah_path = null;
        $file_path_naskah = 'uploads/naskah';

        if ($request->file('file')) {
            $naskah = $request->file('file');
            $naskah_path = $naskah->storePublicly($file_path_naskah, 'public');
        }

        $buku->judul = $request->judul;
        $buku->subjudul = $request->subjudul;
        $buku->abstrak = $request->abstrak;
        $buku->seri = $request->seri;
        $buku->save();

        // Hapus terlebih dahulu detail kategori yang terkait dengan id_buku
        DetailKategoriBuku::where('id_buku', $id)->delete();

        // Tambahkan kembali detail kategori yang dipilih
        foreach ($request->kategori as $kategori_id) {
            DetailKategoriBuku::create([
                'id_buku' => $id,
                'id_kategori' => $kategori_id,
            ]);
        }

        $history = History::create([
            'id_buku' => $id,
            'id_users' => Auth::id(),
            'file_revisi' => $naskah_path,
            'keterangan' => Auth::user()->name . " mengirim file revisi naskah.",
        ]);

        return redirect()->route('naskah')->with('success', 'Revisi naskah berhasil dikirim.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}