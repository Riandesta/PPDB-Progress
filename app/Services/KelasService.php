<?php

namespace App\Services;

use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\CalonSiswa;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class KelasService
{
    public function assignSiswaToCalonSiswa(CalonSiswa $calonSiswa): bool
    {
        try {
            // Ambil jurusan
            $jurusan = Jurusan::findOrFail($calonSiswa->jurusan_id);

            // Dapatkan atau buat kelas yang sesuai untuk siswa ini
            $kelas = $this->findOrCreateBalancedKelas(
                $calonSiswa->jurusan_id,
                $calonSiswa->tahun_ajaran,
                $calonSiswa->nama
            );

            if (!$kelas) {
                return false;
            }

            // Update siswa dengan kelas_id
            $calonSiswa->kelas_id = $kelas->id;
            $calonSiswa->save();

            // Update kapasitas kelas
            $kelas->increment('kapasitas_saat_ini');

            return true;
        } catch (\Exception $e) {
            Log::error('Error assigning siswa to kelas: ' . $e->getMessage());
            return false;
        }
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
        $totalLetterCount = CalonSiswa::whereIn('kelas_id', $existingKelas->pluck('id'))
            ->whereRaw('UPPER(LEFT(nama, 1)) = ?', [$firstLetter])
            ->count();

        // Hitung rata-rata ideal siswa per huruf per kelas
        $idealLetterPerKelas = ceil($totalLetterCount / count($existingKelas));

        // Cari kelas yang paling cocok
        $targetKelas = null;
        $minLetterCount = PHP_INT_MAX;

        foreach ($existingKelas as $kelas) {
            $letterCount = CalonSiswa::where('kelas_id', $kelas->id)
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
}
