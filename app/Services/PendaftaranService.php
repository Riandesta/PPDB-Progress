<?php
// app/Services/PendaftaranService.php

namespace App\Services;

use App\Models\KuotaPPDB;
use Faker\Provider\Image;
use App\Models\Pendaftaran;
use App\Models\TahunAjaran;
use Nette\Utils\Image as foto;
use App\Models\Administrasi;
use App\Services\KelasService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PendaftaranService
{
    protected $kelasService;

    public function __construct(KelasService $kelasService)
    {
        $this->kelasService = $kelasService;
    }

    public function prosesPendaftaran($data)
    {
        return DB::transaction(function () use ($data) {
            // Handle upload foto jika ada
            if (isset($data['foto'])) {
                $data['foto'] = $data['foto']->store('public/foto-siswa');
            }

            // Hitung rata-rata nilai
            $data['rata_rata_nilai'] = $this->hitungRataRata([
                $data['nilai_semester_1'] ?? 0,
                $data['nilai_semester_2'] ?? 0,
                $data['nilai_semester_3'] ?? 0,
                $data['nilai_semester_4'] ?? 0,
                $data['nilai_semester_5'] ?? 0
            ]);

            // Cek kuota
            $kuota = KuotaPPDB::where('tahun_ajaran_id', $data['tahun_ajaran_id'])
                ->where('jurusan_id', $data['jurusan_id'])
                ->first();

            $data['status_seleksi'] = $this->determineSelectionStatus(
                $kuota?->isKuotaAvailable() ?? false,
                $data['rata_rata_nilai']
            );

            // Buat pendaftaran
            $pendaftaran = Pendaftaran::create($data);

            // Buat administrasi
            $this->createAdministrasi($pendaftaran);

            // Proses pembayaran awal jika ada
            if (isset($data['pembayaran_awal']) && $data['pembayaran_awal'] > 0) {
                $this->processPembayaranAwal($pendaftaran, $data['pembayaran_awal']);
            }

            return $pendaftaran;
        });
    }
    private function createAdministrasi($pendaftaran)
    {
        return Administrasi::create([
            'calon_siswa_id' => $pendaftaran->id,
            'biaya_pendaftaran' => config('ppdb.biaya_pendaftaran', 100000),
            'biaya_ppdb' => config('ppdb.biaya_ppdb', 5000000),
            'biaya_mpls' => config('ppdb.biaya_mpls', 250000),
            'biaya_awal_tahun' => config('ppdb.biaya_awal_tahun', 1500000),
            'total_bayar' => 0,
            'status_pembayaran' => 'Belum Lunas'
        ]);
    }

    private function hitungRataRata(array $nilai): float
    {
        $nilai_valid = array_filter($nilai, fn($value) => $value > 0);
        return count($nilai_valid) > 0 ? array_sum($nilai_valid) / count($nilai_valid) : 0;
    }

    private function determineSelectionStatus(bool $kuotaAvailable, float $rataRata): string
    {
        if (!$kuotaAvailable) {
            return 'Pending';
        }
        return $rataRata >= config('ppdb.nilai_minimum', 75) ? 'Lulus' : 'Tidak Lulus';
    }

    private function processPembayaranAwal($pendaftaran, $jumlahBayar)
    {
        $administrasi = $pendaftaran->administrasi;
        if ($administrasi) {
            $administrasi->total_bayar = $jumlahBayar;
            $administrasi->sisa_pembayaran = $administrasi->total_biaya - $jumlahBayar;
            $administrasi->status_pembayaran = $administrasi->sisa_pembayaran <= 0 ? 'Lunas' : 'Belum Lunas';
            $administrasi->save();

            if ($jumlahBayar >= config('ppdb.minimum_pembayaran', 0)) {
                $this->kelasService->assignSiswaToPendaftaran($pendaftaran);
            }
        }
    }

private function handleFotoUpload($file)
{
    $fileName = time() . '_' . $file->getClientOriginalName();
    $path = $file->storeAs('public/foto-siswa', $fileName);

    // Kompres foto
    $img = foto::make(storage_path('app/' . $path));
    $img->resize(800, null, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
    });
    $img->save();

    return $path;
}

public function validateJurusanKuota($jurusanId)
    {
        $kuota = KuotaPPDB::where('jurusan_id', $jurusanId)
            ->where('tahun_ajaran_id', $this->getActiveTahunAjaran()->id)
            ->first();

        if (!$kuota || !$kuota->isKuotaAvailable()) {
            throw new \Exception('Kuota untuk jurusan ini sudah penuh');
        }

        return true;
    }

    private function getActiveTahunAjaran()
    {
        $tahunAjaran = TahunAjaran::where('status', 'aktif')->first();
        if (!$tahunAjaran) {
            throw new \Exception('Tidak ada tahun ajaran yang aktif');
        }
        return $tahunAjaran;
    }

    public function updatePendaftaran($pendaftaran, $data)
    {
        return DB::transaction(function () use ($pendaftaran, $data) {
            // Handle upload foto baru jika ada
            if (isset($data['foto'])) {
                // Hapus foto lama jika ada
                if ($pendaftaran->foto) {
                    Storage::delete($pendaftaran->foto);
                }
                $data['foto'] = $data['foto']->store('public/foto-siswa');
            }

            // Hitung rata-rata nilai jika ada perubahan nilai
            if (isset($data['nilai_semester_1']) || isset($data['nilai_semester_2']) ||
                isset($data['nilai_semester_3']) || isset($data['nilai_semester_4']) ||
                isset($data['nilai_semester_5'])) {

                $data['rata_rata_nilai'] = $this->hitungRataRata([
                    $data['nilai_semester_1'] ?? $pendaftaran->nilai_semester_1 ?? 0,
                    $data['nilai_semester_2'] ?? $pendaftaran->nilai_semester_2 ?? 0,
                    $data['nilai_semester_3'] ?? $pendaftaran->nilai_semester_3 ?? 0,
                    $data['nilai_semester_4'] ?? $pendaftaran->nilai_semester_4 ?? 0,
                    $data['nilai_semester_5'] ?? $pendaftaran->nilai_semester_5 ?? 0
                ]);
            }
  // Cek kuota jika ada perubahan jurusan
  if (isset($data['jurusan_id']) && $data['jurusan_id'] != $pendaftaran->jurusan_id) {
    $kuota = KuotaPPDB::where('tahun_ajaran_id', $pendaftaran->tahun_ajaran_id)
        ->where('jurusan_id', $data['jurusan_id'])
        ->first();

    $data['status_seleksi'] = $this->determineSelectionStatus(
        $kuota?->isKuotaAvailable() ?? false,
        $data['rata_rata_nilai'] ?? $pendaftaran->rata_rata_nilai
    );
}

// Update data pendaftaran
$pendaftaran->update($data);

// Proses pembayaran tambahan jika ada
if (isset($data['pembayaran_tambahan']) && $data['pembayaran_tambahan'] > 0) {
    $this->processPembayaranAwal(
        $pendaftaran,
        $pendaftaran->administrasi->total_bayar + $data['pembayaran_tambahan']
    );
}

return $pendaftaran->fresh();
});
}

public function deletePendaftaran($pendaftaran)
{
    return DB::transaction(function () use ($pendaftaran) {
        try {
            // Hapus foto jika ada
            if ($pendaftaran->foto) {
                Storage::delete($pendaftaran->foto);
            }

           
            if ($pendaftaran->administrasi) {
                $pendaftaran->administrasi->delete();
            }

            // Hapus penempatan kelas jika ada
            if ($pendaftaran->kelas) {
                $this->kelasService->removeSiswaFromKelas($pendaftaran);
            }

            // Hapus pendaftaran
            $pendaftaran->delete();

            return true;
        } catch (\Exception $e) {
            Log::error('Error deleting pendaftaran: ' . $e->getMessage());
            throw new \Exception('Gagal menghapus data pendaftaran');
        }
    });
}


public function deleteFotoIfExists($fotoPath)
{
    if ($fotoPath && Storage::exists($fotoPath)) {
        Storage::delete($fotoPath);
    }
}

 // Method helper untuk validasi data sebelum update
 private function validateUpdateData($pendaftaran, $data)
 {
     // Validasi perubahan jurusan
     if (isset($data['jurusan_id']) && $pendaftaran->status_seleksi === 'Lulus') {
         throw new \Exception('Tidak dapat mengubah jurusan untuk pendaftar yang sudah lulus');
     }

     // Validasi perubahan tahun ajaran
     if (isset($data['tahun_ajaran_id']) && $data['tahun_ajaran_id'] !== $pendaftaran->tahun_ajaran_id) {
        throw new \Exception('Tidak dapat mengubah tahun ajaran pendaftaran');
    }


     return true;
 }
}
