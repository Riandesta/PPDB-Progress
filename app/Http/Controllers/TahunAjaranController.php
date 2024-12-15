<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjaran = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();
        return view('tahun-ajaran.index', compact('tahunAjaran'));
    }

    public function create()
    {
        return view('tahun-ajaran.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|unique:tahun_ajaran,tahun_ajaran',
            'tahun_mulai' => 'required|string',
            'tahun_selesai' => 'required|string',
            'is_active' => 'boolean'
        ]);

        // Jika tahun ajaran baru diset aktif, nonaktifkan yang lain
        if ($request->is_active) {
            TahunAjaran::where('is_active', true)
                ->update(['is_active' => false]);
        }

        TahunAjaran::create($request->all());

        return redirect()
            ->route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan');
    }

    public function edit(TahunAjaran $tahunAjaran)
    {
        return view('tahun-ajaran.form', compact('tahunAjaran'));
    }

    public function update(Request $request, TahunAjaran $tahunAjaran)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|unique:tahun_ajaran,tahun_ajaran,' . $tahunAjaran->id,
            'tahun_mulai' => 'required|string',
            'tahun_selesai' => 'required|string',
            'is_active' => 'boolean'
        ]);

        // Jika tahun ajaran ini diset aktif, nonaktifkan yang lain
        if ($request->is_active) {
            TahunAjaran::where('is_active', true)
                ->where('id', '!=', $tahunAjaran->id)
                ->update(['is_active' => false]);
        }

        $tahunAjaran->update($request->all());

        return redirect()
            ->route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui');
    }

    public function destroy(TahunAjaran $tahunAjaran)
    {
        try {
            $tahunAjaran->delete();
            return redirect()
                ->route('tahun-ajaran.index')
                ->with('success', 'Tahun ajaran berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Tidak dapat menghapus tahun ajaran yang masih digunakan');
        }
    }

    public function setActive($id)
    {
        // Non-aktifkan semua tahun ajaran
        TahunAjaran::where('is_active', true)->update(['is_active' => false]);

        // Aktifkan tahun ajaran yang dipilih
        $tahunAjaran = TahunAjaran::findOrFail($id);
        $tahunAjaran->update(['is_active' => true]);

        return redirect()->route('tahun-ajaran.index')
            ->with('success', 'Tahun Ajaran berhasil diaktifkan');
    }
}
