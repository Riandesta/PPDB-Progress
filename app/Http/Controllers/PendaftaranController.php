<?php

namespace App\Http\Controllers;

use App\Services\PendaftaranService;
use App\Http\Requests\PendaftaranRequest;
use App\Models\Jurusan;
use App\Models\TahunAjaran;
use App\Models\Pendaftaran;

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
        $pendaftaran = Pendaftaran::with(['jurusan', 'administrasi'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('pendaftaran.index', compact('pendaftaran', 'jurusans'));
    }

    public function create()
    {
        $jurusans = Jurusan::all();
        $tahunAjaran = TahunAjaran::where('is_active', true)->get();

        return view('pendaftaran.form', compact('jurusans', 'tahunAjaran'));
    }

    public function store(PendaftaranRequest $request)
    {
        try {
            $pendaftaran = $this->pendaftaranService->prosesPendaftaran($request->validated());
            
            return redirect()
                ->route('pendaftaran.show', $pendaftaran->id)
                ->with('success', 'Pendaftaran berhasil diproses');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Pendaftaran $pendaftaran)
    {
        return view('pendaftaran.show', compact('pendaftaran'));
    }

    public function edit(Pendaftaran $pendaftaran)
    {
        $jurusans = Jurusan::all();
        $tahunAjaran = TahunAjaran::where('is_active', true)->get();
        
        return view('pendaftaran.form', compact('pendaftaran', 'jurusans', 'tahunAjaran'));
    }

    public function update(PendaftaranRequest $request, Pendaftaran $pendaftaran)
    {
        try {
            $pendaftaran = $this->pendaftaranService->updatePendaftaran($pendaftaran, $request->validated());
            
            return redirect()
                ->route('pendaftaran.show', $pendaftaran->id)
                ->with('success', 'Data berhasil diperbarui');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Pendaftaran $pendaftaran)
    {
        try {
            $pendaftaran->delete();
            return redirect()
                ->route('pendaftaran.index')
                ->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
