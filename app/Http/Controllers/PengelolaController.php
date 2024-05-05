<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\History;
use App\Models\User;
use Illuminate\Http\Request;

class PengelolaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    {
        $jumlahNaskahPenyerahan = Buku::where('status', '=', 'Penyerahan')->count();
        $jumlahNaskahDiterima = Buku::where('status', '=', 'Diterima')->count();
        $jumlahNaskahDitolak = Buku::where('status', '=', 'Ditolak')->count();
        $jumlahPenulis = User::where('id_role', '=', '2')->count();
        $jumlahEditorAkuisisi = User::where('id_role', '=', '4')->count();
        $jumlahEditorNaskah = User::where('id_role', '=', '3')->count();
        $history = History::with(['users', 'buku'])->orderBy('created_at', 'desc')->get();

        return view('pages.pengelola.dashboard.index', [
            'jumlahNaskahPenyerahan' => $jumlahNaskahPenyerahan,
            'jumlahNaskahDiterima' => $jumlahNaskahDiterima,
            'jumlahNaskahDitolak' => $jumlahNaskahDitolak,
            'jumlahPenulis' => $jumlahPenulis,
            'jumlahEditorAkuisisi' => $jumlahEditorAkuisisi,
            'jumlahEditorNaskah' => $jumlahEditorNaskah,
            'history' => $history
        ]);
        
    }

    public function index()
    {
        //
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