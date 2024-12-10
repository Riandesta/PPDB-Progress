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

}
