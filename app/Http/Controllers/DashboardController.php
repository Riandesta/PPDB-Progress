<?php

namespace App\Http\Controllers;

use App\Models\KuotaPPDB;
use App\Models\Pendaftaran;
use App\Models\Administrasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Hitung total pembayaran lunas dan belum
        $pembayaranStats = Administrasi::select(
            'status_pembayaran',
            DB::raw('count(*) as total')
        )->groupBy('status_pembayaran')->get();

        $totalPendaftar = Pendaftaran::count();
        $pembayaranLunas = $pembayaranStats->where('status_pembayaran', 'Lunas')->first()->total ?? 0;
        $pembayaranBelumLunas = $pembayaranStats->where('status_pembayaran', 'Belum Lunas')->first()->total ?? 0;

        // Hitung total kuota dan sisa kuota
        $totalKuota = KuotaPPDB::sum('kuota');
        $sisaKuota = $totalKuota - $totalPendaftar;

        $statistics = [
            'total_pendaftar' => $totalPendaftar,
            'total_diterima' => Pendaftaran::where('status_seleksi', 'Lulus')->count(),
            'total_pembayaran' => Administrasi::where('status_pembayaran', 'Lunas')
                ->sum('total_bayar'),
            'sisa_kuota' => max(0, $sisaKuota),
            'pendaftar_per_jurusan' => Pendaftaran::select('jurusan_id', DB::raw('count(*) as total'))
                ->groupBy('jurusan_id')
                ->with('jurusan')
                ->get(),
            'pembayaran_lunas' => $pembayaranLunas,
            'pembayaran_belum_lunas' => $pembayaranBelumLunas,
            'persentase_lunas' => $totalPendaftar > 0
                ? round(($pembayaranLunas / $totalPendaftar) * 100, 1) . '%'
                : '0%',
            'persentase_belum_lunas' => $totalPendaftar > 0
                ? round(($pembayaranBelumLunas / $totalPendaftar) * 100, 1) . '%'
                : '0%',

                'pendaftar_per_hari' => Pendaftaran::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('count(*) as total')
                )->groupBy('date')->get(),
                'status_seleksi' => Pendaftaran::select(
                    'status_seleksi',
                    DB::raw('count(*) as total')
                )->groupBy('status_seleksi')->get(),
                'persentase_kelulusan' => Pendaftaran::where('status_seleksi', 'Lulus')
                    ->count() / max(1, Pendaftaran::count()) * 100
        ];
        $title = 'Dashboard';
        return view('dashboard.index', compact('statistics', 'title'));
    }
        }

