<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrasi extends Model
{
    protected $fillable = [
        'calon_siswa_id',
        'biaya_pendaftaran',
        'biaya_ppdb',
        'status_pendaftaran',
        'status_ppdb',
        'total_pembayaran'
    ];

    public function calonSiswa()
    {
        return $this->belongsTo(CalonSiswa::class);
    }
}
