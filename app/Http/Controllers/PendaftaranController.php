<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Pendaftaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\PendaftaranService;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\PendaftaranRequest;

class PendaftaranController extends Controller
{
    protected $pendaftaranService;

    public function __construct(PendaftaranService $pendaftaranService)
    {
        $this->pendaftaranService = $pendaftaranService;
    }

    public function index()
    {
        $jurusans = Jurusan::all();
        $pendaftars = Pendaftaran::with(['jurusan', 'administrasi', 'tahunAjaran'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('pendaftaran.index', compact('pendaftars', 'jurusans',));
    }

    public function create()
{
    // Ubah dari get() menjadi first()
    $tahunAjaran = TahunAjaran::where('is_active', true)->first();
    if (!$tahunAjaran) {
        return back()->with('error', 'Tidak ada tahun ajaran yang aktif');
    }

    $jurusans = Jurusan::all();
    return view('pendaftaran.form', compact('jurusans', 'tahunAjaran')); // Ubah nama variable
}


    // PendaftaranController.php
public function store(PendaftaranRequest $request, PendaftaranService $service)
{
    try {
        DB::beginTransaction();

        // Validasi tahun ajaran aktif
        $tahunAjaran = TahunAjaran::where('is_active', true)->first();
        if (!$tahunAjaran) {
            return back()->with('error', 'Tidak ada tahun ajaran aktif');
        }

        // Proses pendaftaran
        $pendaftar = $service->prosesPendaftaran($request->validated());

        DB::commit();
        return redirect()
            ->route('pendaftaran.index')
            ->with('success', 'Pendaftaran berhasil diproses');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}


    public function show(Pendaftaran $pendaftaran)
    {
        try {
            $pendaftaran->load(['jurusan', 'administrasi', 'tahunAjaran']);
            return response()->json([
                'success' => true,
                'data' => $pendaftaran
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    public function edit(Pendaftaran $pendaftaran)
    {
        $tahunAjaran = TahunAjaran::where('is_active', true)->first();
        $jurusans = Jurusan::all();

        return view('pendaftaran.form', [
            'pendaftaran' => $pendaftaran,
            'jurusans' => $jurusans,
            'tahunAjaran' => $tahunAjaran,
        ]);
    }



    public function update(
        PendaftaranRequest $request,
        PendaftaranService $service,
        Pendaftaran $pendaftaran
    ) {
        try {
            DB::beginTransaction();
    
            if ($request->hasFile('foto')) {
                // Hapus foto lama
                if ($pendaftaran->foto) {
                    Storage::delete('public/foto_siswa/' . basename($pendaftaran->foto));
                }

                $path = $request->file('foto')->store('public/foto_siswa');
                $data['foto'] = Storage::url($path);
            }
    
            $pendaftaran->update($request->validated());
    
            DB::commit();
            return redirect()
                ->route('pendaftaran.index')
                ->with('success', 'Data berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Pendaftaran $pendaftaran)
    {
        try {
            DB::beginTransaction();

            // Hapus foto jika ada
            if ($pendaftaran->foto) {
                $this->pendaftaranService->deleteFotoIfExists($pendaftaran->foto);
            }

            $pendaftaran->delete();

            DB::commit();
            return redirect()
                ->route('pendaftaran.index')
                ->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
