<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\KuotaPPDB;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class TahunAjaranController extends Controller
{
    public function index()
    {
        try {
            $tahunAjaran = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();
            return view('tahun-ajaran.index', compact('tahunAjaran'));
        } catch (\Exception $e) {
            Log::error('Error in TahunAjaranController@index: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data tahun ajaran');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'tahun_ajaran' => 'required|unique:tahun_ajaran,tahun_ajaran',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after:tanggal_mulai'
            ]);

            DB::transaction(function() use ($request, $validated) {
                if ($request->is_active) {
                    TahunAjaran::where('is_active', true)
                        ->update(['is_active' => false]);
                }

                TahunAjaran::create([
                    'tahun_ajaran' => $validated['tahun_ajaran'],
                    'tanggal_mulai' => $validated['tanggal_mulai'],
                    'tanggal_selesai' => $validated['tanggal_selesai'],
                    'is_active' => $request->is_active ?? false
                ]);
            });

            return redirect()
                ->route('tahun-ajaran.index')
                ->with('success', 'Tahun Ajaran berhasil dibuat');

        } catch (\Exception $e) {
            Log::error('Error in TahunAjaranController@store: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat tahun ajaran baru: ' . $e->getMessage());
        }
    }

    public function activate(TahunAjaran $tahunAjaran)
    {
        try {
            DB::transaction(function() use ($tahunAjaran) {
                // Non-aktifkan semua tahun ajaran
                TahunAjaran::query()->update(['is_active' => false]);
                
                // Aktifkan tahun ajaran yang dipilih
                $tahunAjaran->update(['is_active' => true]);
                
                // Buat kuota PPDB dan kelas baru
                $this->createKuotaPPDB($tahunAjaran);
                $this->createKelas($tahunAjaran);
            });

            return redirect()
                ->route('tahun-ajaran.index')
                ->with('success', 'Tahun Ajaran berhasil diaktifkan');

        } catch (\Exception $e) {
            Log::error('Error in TahunAjaranController@activate: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengaktifkan tahun ajaran: ' . $e->getMessage());
        }
    }

    private function createKuotaPPDB(TahunAjaran $tahunAjaran)
    {
        try {
            $jurusans = Jurusan::all();
            
            foreach ($jurusans as $jurusan) {
                KuotaPPDB::create([
                    'tahun_ajaran_id' => $tahunAjaran->id,
                    'jurusan_id' => $jurusan->id,
                    'kuota' => $jurusan->kapasitas_per_kelas * $jurusan->max_kelas
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error creating KuotaPPDB: ' . $e->getMessage());
            throw $e;
        }
    }

    private function createKelas(TahunAjaran $tahunAjaran)
    {
        try {
            $jurusans = Jurusan::all();
            
            foreach ($jurusans as $jurusan) {
                for ($i = 1; $i <= $jurusan->max_kelas; $i++) {
                    Kelas::create([
                        'jurusan_id' => $jurusan->id,
                        'nama_kelas' => $jurusan->kode_jurusan . ' ' . chr(64 + $i),
                        'tahun_ajaran' => $tahunAjaran->tahun_ajaran,
                        'tahun_ajaran_id' => $tahunAjaran->id,
                        'urutan_kelas' => $i,
                        'kapasitas_saat_ini' => 0
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error creating Kelas: ' . $e->getMessage());
            throw $e;
        }
    }
}
