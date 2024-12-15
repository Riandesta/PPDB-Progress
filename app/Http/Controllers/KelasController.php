<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Pendaftaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{

    public function index()
    {
        $kelasGroup = Kelas::with('jurusan')->get()->groupBy('jurusan.nama');
        return view('kelas.index', compact('kelasGroup'));
    }

    public function distribusi()
    {
        $tahunAjaran = TahunAjaran::where('is_active', true)->first();

        if (!$tahunAjaran) {
            return back()->with('error', 'Tahun ajaran aktif tidak ditemukan.');
        }

        $siswa = Pendaftaran::with(['jurusan', 'kelas'])
            ->where('tahun_ajaran_id', $tahunAjaran->id)
            ->where('status_seleksi', 'Lulus')
            ->orderBy('rata_rata_nilai', 'desc')
            ->get();

        $kelas = Kelas::with('jurusan')
            ->where('tahun_ajaran_id', $tahunAjaran->id)
            ->get()
            ->groupBy('jurusan_id');

        return view('kelas.distribusi', compact('siswa', 'kelas', 'tahunAjaran'));
    }



    public function prosesDistribusi(Request $request)
{
    try {
        $data = $request->validate([
            'siswa_kelas' => 'required|array',
            'siswa_kelas.*' => 'required|exists:kelas,id'
        ]);

        foreach ($data['siswa_kelas'] as $siswaId => $kelasId) {
            $pendaftaran = Pendaftaran::find($siswaId);

            if (!$pendaftaran) {
                return back()->with('error', "Siswa dengan ID {$siswaId} tidak ditemukan.");
            }

            $pendaftaran->update(['kelas_id' => $kelasId]);
        }

        return redirect()
            ->route('kelas.distribusi')
            ->with('success', 'Distribusi kelas berhasil dilakukan.');
    } catch (\Exception $e) {
        return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

// KelasController.php
public function show(Kelas $kelas)
{
    // Eager load semua relasi yang dibutuhkan
    $kelas->load([
        'jurusan',
        'pendaftaran' => function($query) {
            $query->with('administrasi')
                  ->orderBy('nama');
        }
    ]);
    
    $siswaPerHuruf = $kelas->pendaftaran()
        ->select(DB::raw('LEFT(nama, 1) as huruf'), DB::raw('count(*) as jumlah'))
        ->groupBy(DB::raw('LEFT(nama, 1)'))
        ->get()
        ->pluck('jumlah', 'huruf');

    return view('kelas.show', compact('kelas', 'siswaPerHuruf'));
}

}