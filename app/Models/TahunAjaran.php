<?php

namespace App\Models;

use App\Models\KuotaPPDB;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'tahun_ajaran',
        'tahun_mulai',
        'tahun_selesai',
        'is_active',
        'tanggal_mulai',
        'tanggal_selesai'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function pendaftaran()
    {
        return $this->hasOne(Pendaftaran::class);
    }
    public function kelas()
    {
        return $this->hasOne(Kelas::class);
    }
    public function administrasi()
    {
        return $this->hasOne(Administrasi::class);
    }
}
