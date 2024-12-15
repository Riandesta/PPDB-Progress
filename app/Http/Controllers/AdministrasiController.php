<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Administrasi;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class AdministrasiController extends Controller
{
    public function show($pendaftaranId)
    {
        $administrasi = Administrasi::with(['pendaftaran', 'tahunAjaran'])
            ->where('pendaftaran_id', $pendaftaranId)
            ->firstOrFail();

        return view('administrasi.show', compact('administrasi'));
    }

    public function index()
    {
        $tahunAjaran = TahunAjaran::where('is_active', true)->first();
        $administrasi = Administrasi::with(['pendaftaran', 'tahunAjaran'])
            ->where('tahun_ajaran_id', $tahunAjaran->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('administrasi.pembayaran.index', compact('administrasi'));
    }

    public function pembayaran()
{
    try {
        $tahunAjaran = TahunAjaran::where('is_active', true)->firstOrFail();
        
        $administrasi = Administrasi::with([
            'pendaftaran.jurusan', 
            'pendaftaran', // tambahkan eager loading untuk pendaftaran
            'tahunAjaran'
        ])
        ->where('tahun_ajaran_id', $tahunAjaran->id)
        ->get();

        $totalPembayaran = $administrasi->sum('total_bayar');
        $totalTagihan = $administrasi->sum('total_biaya');
        $sisaBelumBayar = $totalTagihan - $totalPembayaran;
        
        return view('administrasi.pembayaran.index', compact(
            'administrasi', 
            'totalPembayaran', 
            'totalTagihan',
            'sisaBelumBayar'
        ));

    } catch (\Exception $e) {
        Log::error('Error in pembayaran: ' . $e->getMessage());
        return back()->with('error', 'Terjadi kesalahan saat memuat data pembayaran');
    }
}


    public function laporanKeuangan()
    {
        try {
            $tahunAjaran = TahunAjaran::where('is_active', true)->firstOrFail();
            
            $pembayaranPerJurusan = Administrasi::with(['pendaftaran.jurusan'])
                ->where('tahun_ajaran_id', $tahunAjaran->id)
                ->get()
                ->groupBy('pendaftaran.jurusan.nama_jurusan')
                ->map(function($items) {
                    return [
                        'total_tagihan' => $items->sum('total_biaya'),
                        'total_pembayaran' => $items->sum('total_bayar'),
                        'sisa_pembayaran' => $items->sum('total_biaya') - $items->sum('total_bayar'),
                        'jumlah_siswa' => $items->count(),
                        'lunas' => $items->where('status_pembayaran', 'Lunas')->count(),
                        'belum_lunas' => $items->where('status_pembayaran', 'Belum Lunas')->count()
                    ];
                });

            return view('administrasi.laporan', compact('pembayaranPerJurusan', 'tahunAjaran'));
            
        } catch (\Exception $e) {
            Log::error('Error in laporanKeuangan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat laporan keuangan');
        }
    }

    public function processPembayaran(Request $request, $pendaftaranId)
    {
        try {
            DB::beginTransaction();

            $administrasi = Administrasi::where('pendaftaran_id', $pendaftaranId)->firstOrFail();
            
            // Validasi jumlah pembayaran
            if ($request->jumlah_bayar <= 0) {
                throw new \Exception('Jumlah pembayaran harus lebih dari 0');
            }

            // Validasi jenis biaya
            if (!in_array($request->jenis_biaya, ['pendaftaran', 'ppdb', 'mpls', 'awal_tahun'])) {
                throw new \Exception('Jenis biaya tidak valid');
            }

            $administrasi->tambahPembayaran(
                $request->jumlah_bayar,
                $request->jenis_biaya,
                $request->metode_pembayaran,
                $request->file('bukti_pembayaran')
            );

            DB::commit();
            return back()->with('success', 'Pembayaran berhasil diproses');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in processPembayaran: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function laporan()
    {
        try {
            $tahunAjaran = TahunAjaran::where('is_active', true)->firstOrFail();
            
            $administrasi = Administrasi::with(['pendaftaran.jurusan'])
                ->where('tahun_ajaran_id', $tahunAjaran->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $summary = [
                'total_pendapatan' => $administrasi->sum('total_bayar'),
                'total_tagihan' => $administrasi->sum('total_biaya'),
                'total_siswa' => $administrasi->count(),
                'siswa_lunas' => $administrasi->where('status_pembayaran', 'Lunas')->count(),
                'siswa_belum_lunas' => $administrasi->where('status_pembayaran', 'Belum Lunas')->count()
            ];

            return view('administrasi.laporan', compact('administrasi', 'summary', 'tahunAjaran'));
            
        } catch (\Exception $e) {
            Log::error('Error in laporan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat laporan');
        }
    }

    public function tambahPembayaran(Request $request, Administrasi $administrasi) 
    {
        try {
            $validated = $request->validate([
                'jenis_pembayaran' => 'required|in:pendaftaran,ppdb,mpls,awal_tahun',
                'jumlah_bayar' => 'required|numeric|min:1',
                'metode_pembayaran' => 'required|in:tunai,transfer',
                'bukti_pembayaran' => 'required_if:metode_pembayaran,transfer|image|max:2048'
            ]);
            
            DB::transaction(function() use ($administrasi, $validated, $request) {
                // Handle file upload jika ada
                $buktiPembayaran = null;
                if ($request->hasFile('bukti_pembayaran')) {
                    $buktiPembayaran = $request->file('bukti_pembayaran')
                        ->store('public/bukti-pembayaran');
                }

                $administrasi->tambahCicilan(
                    $validated['jenis_pembayaran'],
                    $validated['jumlah_bayar'],
                    $validated['metode_pembayaran'],
                    $buktiPembayaran
                );
            });

            return back()->with('success', 'Pembayaran berhasil ditambahkan');
            
        } catch (\Exception $e) {
            Log::error('Error in tambahPembayaran: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan pembayaran');
        }
    }
}