<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Administrasi;
use Illuminate\Http\Request;

class AdministrasiController extends Controller
{
    public function show($pendaftaranId)
    {
        $administrasi = Administrasi::where('pendaftaran_id', $pendaftaranId)
            ->with('pendaftaran')
            ->firstOrFail();

        return view('administrasi.show', compact('administrasi'));
    }

    public function index()
    {
        $pendaftaran = Pendaftaran::all();
        $administrasi = Administrasi::with('pendaftaran')->get();
        return view('administrasi.pembayaran.index', compact('administrasi', 'pendaftaran'));
    }

    public function pembayaran()
    {
        $administrasi = Administrasi::with('pendaftaran')->get();
        return view('administrasi.pembayaran.index', compact('administrasi'));
    }

    public function laporan()
    {
        $administrasi = Administrasi::with(['pendaftaran'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('administrasi.laporan', compact('administrasi'));
    }
}
