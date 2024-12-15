<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

// LaporanController.php
class LaporanController extends Controller {
   // LaporanController.php
public function pendaftaran()
{
    $tahunAjaran = TahunAjaran::where('is_active', true)->first();
    
    $statistik = [
        'total_pendaftar' => Pendaftaran::where('tahun_ajaran_id', $tahunAjaran->id)->count(),
        'per_jurusan' => Pendaftaran::with('jurusan')
            ->where('tahun_ajaran_id', $tahunAjaran->id)
            ->get()
            ->groupBy('jurusan.nama_jurusan'),
        'per_status' => Pendaftaran::where('tahun_ajaran_id', $tahunAjaran->id)
            ->get()
            ->groupBy('status_seleksi')
    ];

    return view('laporan.pendaftaran', compact('statistik', 'tahunAjaran'));
}

public function kelulusan()
{
    $tahunAjaran = TahunAjaran::where('is_active', true)->first();
    
    $kelulusan = Pendaftaran::with(['jurusan', 'kelas'])
        ->where('tahun_ajaran_id', $tahunAjaran->id)
        ->where('status_seleksi', 'Lulus')
        ->orderBy('rata_rata_nilai', 'desc')
        ->get()
        ->groupBy('jurusan.nama_jurusan');

    return view('laporan.kelulusan', compact('kelulusan', 'tahunAjaran'));
}
}