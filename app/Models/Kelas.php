<?php

namespace App\Models;

use App\Models\Jurusan;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelas extends Model
{
    protected $fillable = [
        'jurusan_id',
        'nama_kelas',
        'tahun_ajaran',
        'urutan_kelas',
        'kapasitas_saat_ini'
    ];

    protected $appends = ['is_full', 'jumlah_siswa'];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    // Change this from calonSiswas to calonSiswa to match the usage
    public function calonSiswa()
    {
        return $this->hasMany(CalonSiswa::class);
    }

    public function getIsFullAttribute()
    {
        return $this->kapasitas_saat_ini >= $this->jurusan->kapasitas_per_kelas;
    }

    public function getJumlahSiswaAttribute()
    {
        return $this->kapasitas_saat_ini;
    }
    public static function createNewKelas($jurusanId, $tahunAjaran)
    {
        $jurusan = Jurusan::find($jurusanId);

        // Tentukan nama kelas baru (misal "A", "B", dst.)
        $existingCount = self::where('jurusan_id', $jurusanId)
                            ->where('tahun_ajaran', $tahunAjaran)
                            ->count();
        $newClassName = chr(65 + $existingCount); // A, B, C, ...

        return self::create([
            'jurusan_id' => $jurusanId,
            'nama_kelas' => "Kelas $newClassName",
            'tahun_ajaran' => $tahunAjaran,
            'kapasitas_saat_ini' => 0
        ]);
    }

    public function getLetterDistribution()
{
    $distribution = $this->calonSiswa()
        ->select(DB::raw('UPPER(LEFT(nama, 1)) as letter'), DB::raw('count(*) as count'))
        ->groupBy(DB::raw('UPPER(LEFT(nama, 1))'))
        ->orderBy('letter', 'asc') // Menambahkan pengurutan berdasarkan huruf
        ->get()
        ->pluck('count', 'letter')
        ->toArray();

    // Memastikan array terurut berdasarkan key (huruf)
    ksort($distribution);

    return $distribution;
}

}
