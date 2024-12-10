<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        // Get all kelas with jurusan and calonSiswa relationships
        $kelasCollection = Kelas::with(['jurusan', 'calonSiswa'])
            ->get()
            ->groupBy('jurusan.nama_jurusan');

        return view('kelas.index', ['kelasGroup' => $kelasCollection]);
    }

    public function show($id)
    {
        $kelas = Kelas::with(['jurusan', 'calonSiswa' => function($query) {
            $query->orderBy('nama', 'asc'); // Mengurutkan berdasarkan nama
        }])->findOrFail($id);

        return view('kelas.show', compact('kelas'));
    }
}
