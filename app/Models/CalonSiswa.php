<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalonSiswa extends Model
{
    use HasFactory;

    protected $table = 'pendaftarans';

    protected $fillable = [
        'NISN',
        'nama',
        'alamat',
        'tgl_lahir',
        'tmp_lahir',
        'jenis_kelamin',
        'agama',
        'asal_sekolah',
        'nama_ortu',
        'pekerjaan_ortu',
        'no_telp_ortu',
        'foto',
        'tahun_ajaran',
        'jurusan_id',
        'status_dokumen',
        'nilai_semester_1',
        'nilai_semester_2',
        'nilai_semester_3',
        'nilai_semester_4',
        'nilai_semester_5',
        'rata_rata_nilai',
        'status_seleksi'
    ];


    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }
    // app/Models/CalonSiswa.php
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // Method to calculate average
    public function hitungRataRata()
    {
        $nilai = [
            $this->nilai_semester_1,
            $this->nilai_semester_2,
            $this->nilai_semester_3,
            $this->nilai_semester_4,
            $this->nilai_semester_5
        ];

        // Filter out null values
        $nilai_valid = array_filter($nilai, function ($value) {
            return !is_null($value);
        });

        // Calculate average if there are valid values
        if (count($nilai_valid) > 0) {
            return array_sum($nilai_valid) / count($nilai_valid);
        }

        return 0; // Return 0 if no valid values
    }
}