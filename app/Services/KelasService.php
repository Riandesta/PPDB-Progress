<?php

namespace App\Services;

use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class KelasService
{
    public function distributeStudents() {
        $lulusSiswa = Pendaftaran::where('status_seleksi', 'Lulus')
            ->whereNull('kelas_id')
            ->get();
            
        foreach($lulusSiswa as $siswa) {
            $this->assignToBalancedClass($siswa);
        }
    }
    private function assignToBalancedClass($siswa) {
        $firstLetter = strtoupper(substr($siswa->nama, 0, 1));
        $jurusan = $siswa->jurusan;
        
        // Hitung distribusi huruf di setiap kelas
        $kelasDistribution = Kelas::where('jurusan_id', $jurusan->id)
            ->where('tahun_ajaran_id', $siswa->tahun_ajaran_id)
            ->get()
            ->map(function($kelas) use ($firstLetter) {
                return [
                    'kelas' => $kelas,
                    'count' => $kelas->pendaftaran()
                        ->whereRaw('UPPER(LEFT(nama, 1)) = ?', [$firstLetter])
                        ->count()
                ];
            });
             // Pilih kelas dengan distribusi terendah
        $targetKelas = $kelasDistribution
        ->sortBy('count')
        ->first()['kelas'];
        
    // Assign siswa ke kelas
    $siswa->update(['kelas_id' => $targetKelas->id]);
}
    public function assignSiswaToPendaftaran(Pendaftaran $pendaftaran): bool
    {
        // Tambahkan pengecekan administrasi
        if ($pendaftaran->status_seleksi !== 'Lulus' ||
            !$pendaftaran->administrasi ||
            !$pendaftaran->administrasi->isFullyPaid()) {
            return false;
        }

        $kelas = $this->findOrCreateBalancedKelas(
            $pendaftaran->jurusan_id,
            $pendaftaran->tahun_ajaran,
            $pendaftaran->nama
        );

        if (!$kelas) {
            return false;
        }

        $pendaftaran->update(['kelas_id' => $kelas->id]);
        $kelas->increment('kapasitas_saat_ini');

        return true;
    }

    private function findOrCreateBalancedKelas($jurusanId, $tahunAjaran, $namaSiswa)
    {
        $jurusan = Jurusan::findOrFail($jurusanId);
        $firstLetter = strtoupper(substr($namaSiswa, 0, 1));

        // Ambil atau buat kelas pertama jika belum ada kelas
        $existingKelas = Kelas::where('jurusan_id', $jurusanId)
            ->where('tahun_ajaran', $tahunAjaran)
            ->get();

        if ($existingKelas->isEmpty()) {
            return $this->createNewKelas($jurusanId, $tahunAjaran, 1);
        }

        // Hitung total siswa per huruf di seluruh kelas
        $totalLetterCount = Pendaftaran::whereIn('kelas_id', $existingKelas->pluck('id'))
            ->whereRaw('UPPER(LEFT(nama, 1)) = ?', [$firstLetter])
            ->count();

        // Hitung rata-rata ideal siswa per huruf per kelas
        $idealLetterPerKelas = ceil($totalLetterCount / count($existingKelas));

        // Cari kelas yang paling cocok
        $targetKelas = null;
        $minLetterCount = PHP_INT_MAX;

        foreach ($existingKelas as $kelas) {
            $letterCount = Pendaftaran::where('kelas_id', $kelas->id)
                ->whereRaw('UPPER(LEFT(nama, 1)) = ?', [$firstLetter])
                ->orderBy('nama', 'asc') // Menambahkan pengurutan
                ->count();

            // Cek apakah kelas masih bisa menampung siswa
            if ($kelas->kapasitas_saat_ini < $jurusan->kapasitas_per_kelas) {
                // Jika jumlah siswa dengan huruf yang sama masih di bawah rata-rata
                if ($letterCount < $idealLetterPerKelas) {
                    if ($letterCount < $minLetterCount) {
                        $minLetterCount = $letterCount;
                        $targetKelas = $kelas;
                    }
                }
            }
        }

          // Jika tidak ada kelas yang cocok dan masih bisa membuat kelas baru
        if (!$targetKelas && count($existingKelas) < $jurusan->max_kelas) {
            $targetKelas = $this->createNewKelas(
                $jurusanId,
                $tahunAjaran,
                count($existingKelas) + 1
            );
        }

        // Jika masih tidak ada kelas yang cocok, pilih kelas dengan kapasitas paling sedikit
        if (!$targetKelas) {
            $targetKelas = $existingKelas->where('kapasitas_saat_ini',
                $existingKelas->min('kapasitas_saat_ini'))->first();
        }

        return $targetKelas;
    }


    private function createNewKelas($jurusanId, $tahunAjaran, $urutanKelas)
    {
        return Kelas::create([
            'jurusan_id' => $jurusanId,
            'nama_kelas' => 'Kelas ' . chr(64 + $urutanKelas), // A, B, C, dst
            'tahun_ajaran' => $tahunAjaran,
            'urutan_kelas' => $urutanKelas,
            'kapasitas_saat_ini' => 0
        ]);
    }

    public function removeSiswaFromKelas($pendaftaran): bool
    {
        try {
            DB::transaction(function() use ($pendaftaran) {
                // Update siswa dengan menghapus kelas_id
                $pendaftaran->kelas_id = null;
                $pendaftaran->save();

                // Jika ada kelas yang terkait, update kapasitasnya
                if ($pendaftaran->kelas) {
                    $kelas = $pendaftaran->kelas;
                    $kelas->jumlah_siswa = $kelas->calonSiswa()->count() - 1;
                    $kelas->save();
                }
            });

            Log::info('Siswa berhasil dihapus dari kelas', [
                'pendaftaran_id' => $pendaftaran->id,
                'nama_siswa' => $pendaftaran->nama
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Error removing siswa from kelas: ' . $e->getMessage());
            return false;
        }
    }
}
