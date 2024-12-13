<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    //
    public function pendaftaran()
    {
        $pendaftaran = Pendaftaran::with(['jurusan'])->get();
        return view('laporan.pendaftaran', compact('pendaftaran'));
    }

}
