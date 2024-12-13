<?php

namespace App\Http\Controllers;

use App\Models\Administrasi;
use Illuminate\Http\Request;

class AdministrasiController extends Controller
{
    // AdminstrasiController.php
public function show($calonSiswaId)
{
    $administrasi = Administrasi::where('calon_siswa_id', $calonSiswaId)
        ->with('calonSiswa')
        ->firstOrFail();

    return view('administrasi.show', compact('administrasi'));
}

public function index()
{
    $pembayaran = Administrasi::with('calonSiswa')->get();
    return view('administrasi.pembayaran.index', compact('pembayaran'));
}


public function laporan()
{
    $administrasi = Administrasi::with(['calonSiswa'])
        ->orderBy('created_at', 'desc')
        ->get();

    return view('administrasi.laporan', compact('administrasi'));
}

}
