<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Model: RiwayatPembayaran.php
class RiwayatPembayaran extends Model
{
    protected $table = 'riwayat_pembayaran';

    protected $fillable = [
        'administrasi_id',
        'no_pembayaran',
        'tanggal_bayar',
        'jenis_pembayaran',
        'jumlah_bayar',
        'metode_pembayaran',
        'bukti_pembayaran',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'jumlah_bayar' => 'integer'
    ];

    public function administrasi()
    {
        return $this->belongsTo(Administrasi::class);
    }
}
