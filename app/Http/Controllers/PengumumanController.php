<?php

namespace App\Http\Controllers;

use App\Models\CalonSiswa;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
// PengumumanController.php
public function showPengumuman()
{
    $pengumuman = CalonSiswa::where('status_seleksi', '!=', 'Pending')
        ->with('jurusan')
        ->orderBy('nama')
        ->get();

    return view('pengumuman.index', compact('pengumuman'));
}

}
