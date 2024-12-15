<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Pendaftaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\PendaftaranService;
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
        $tahunAjaran = TahunAjaran::where('is_active', 'aktif')->first();
        if (!$tahunAjaran) {
            return back()->with('error', 'Tidak ada tahun ajaran yang aktif');
        }

        $jurusans = Jurusan::all();
        return view('pendaftaran.form', compact('jurusans', 'tahunAjaran'));
    }

    public function store(PendaftaranRequest $request, PendaftaranService $service)
    {
        try {
            DB::beginTransaction();

            $tahunAjaran = TahunAjaran::where('status', 'aktif')->first();
            if (!$tahunAjaran) {
                return back()->with('error', 'Tidak ada tahun ajaran aktif');
            }

            // Upload foto jika ada
            if ($request->hasFile('foto')) {
                $foto = $this->$service->handleFotoUpload($request->file('foto'));
                $request->merge(['foto' => $foto]);
            }

            // Validasi kuota jurusan
            $this->$service->validateJurusanKuota($request->jurusan_id);

            // Tambahkan tahun ajaran ke request
            $request->merge(['tahun_ajaran_id' => $tahunAjaran->id]);

            // Proses pendaftaran
            $pendaftar = $this->$service->prosesPendaftaran($request->validated());

            DB::commit();
            return redirect()
                ->route('pendaftaran.index')
                ->with('success', 'Pendaftaran berhasil diproses');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
        $tahunAjaran = TahunAjaran::where('status', 'aktif')->first();
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

            // Upload foto baru jika ada
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                $this->$service->deleteFotoIfExists($pendaftaran->foto);

                // Upload foto baru
                $foto = $this->$service->handleFotoUpload($request->file('foto'));
                $request->merge(['foto' => $foto]);
            }

            // Update data pendaftaran
            $pendaftaran->update($request->validated());

            DB::commit();
            return redirect()
                ->route('pendaftaran.index')
                ->with('success', 'Data berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
