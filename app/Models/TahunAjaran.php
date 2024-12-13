<?php

namespace App\Models;

use App\Models\KuotaPPDB;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'tahun_ajaran',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function pendaftaran()
    {
        return $this->hasOne(Pendaftaran::class);
    }
    public function administrasi()
    {
        return $this->hasOne(Administrasi::class);

    }
}
