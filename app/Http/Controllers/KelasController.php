<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Pendaftaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

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
                Pendaftaran::where('id', $siswaId)->update([
                    'kelas_id' => $kelasId
                ]);
            }

            return redirect()
                ->route('kelas.distribusi')
                ->with('success', 'Distribusi kelas berhasil dilakukan');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
