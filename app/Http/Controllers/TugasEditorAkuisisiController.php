<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\History;
use App\Models\Kategori;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class TugasEditorAkuisisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.editorAkuisisi.tugas.index');
    }

    public function data()
    {
        $userId = Auth::id();

        $data = Buku::whereHas('history', function ($query) use ($userId) {
            $query->where('id_users', $userId)
                ->whereIn('status', ['Sedang Direview', 'Revisi']);
        })->with(['users', 'history'])->get();

        $rowData = [];

        foreach ($data as $row) {
            $historyRows = History::with(['users', 'buku'])
                ->where('id_buku', $row->id_buku)
                ->get();
            $rowData[] = [
                'id_buku' => $row->id_buku,
                'DT_RowIndex' => $row->id_buku,
                'judul' => $row->judul,
                'penulis' => $row->users->name ?? '-',
                'subjudul' => $row->subjudul ?? '-',
                'status' => $row->status ?? '-',
                'historyRows' => $historyRows,
            ];
        }

        return DataTables::of($rowData)->toJson();
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

        $latestHistory = History::where('id_buku', $id)
            ->whereNotNull('file_revisi') // Pastikan file_revisi tidak null
            ->latest('created_at') // Urutkan berdasarkan tanggal pembuatan (dari yang terbaru)
            ->first();

        return view('pages.editorAkuisisi.tugas.edit', [
            'buku' => $buku,
            'detailKategoriBuku' => $kategori,
            'kategori' => $datakategori,
            'detailContributorBuku' => $users,
            'latestHistory' => $latestHistory
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return redirect()->route('editor.akuisisi.tugas')->with('error', 'Kesalahan coba kembali.');
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

        return redirect()->route('editor.akuisisi.tugas')->with('success', 'Tugas Selesai.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}