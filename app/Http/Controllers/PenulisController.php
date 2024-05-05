<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenulisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    {
        $jumlahNaskahPenyerahan = Buku::where('id_users', Auth::id())->where('status', '=', 'Penyerahan')->count();
        $jumlahNaskahDiterima = Buku::where('id_users', Auth::id())->where('status', '=', 'Diterima')->count();
        $jumlahNaskahDitolak = Buku::where('id_users', Auth::id())->where('status', '=', 'Ditolak')->count();
        $history = History::with(['users', 'buku'])->orderBy('created_at', 'desc')->get();

        return view('pages.penulis.dashboard.index', [
            'jumlahNaskahPenyerahan' => $jumlahNaskahPenyerahan,
            'jumlahNaskahDiterima' => $jumlahNaskahDiterima,
            'jumlahNaskahDitolak' => $jumlahNaskahDitolak,
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