<?php

namespace App\Http\Controllers;

use App\Models\CalonSiswa;
use App\Models\Administrasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    //
    public function show() : View
    {
        return view('dashboard\index');
    }

    public function index()
{
    $statistics = [
        'total_pendaftar' => CalonSiswa::count(),
        'total_diterima' => CalonSiswa::where('status_seleksi', 'Lulus')->count(),
        'total_pembayaran' => Administrasi::where('status_ppdb', 'Sudah Bayar')->sum('total_pembayaran'),
        'pendaftar_per_jurusan' => CalonSiswa::select('jurusan_id', DB::raw('count(*) as total'))
            ->groupBy('jurusan_id')
            ->with('jurusan')
            ->get()
    ];

    return view('dashboard.index', compact('statistics'));
}

}

