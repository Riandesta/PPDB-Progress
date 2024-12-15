<?php
// app/Models/Administrasi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administrasi extends Model
{

    protected $table = 'administrasis';
    protected $fillable = [
        'pendaftaran_id',
        'biaya_pendaftaran',
        'biaya_ppdb',
        'biaya_mpls',
        'biaya_awal_tahun',
        'total_bayar',
        'status_pembayaran',
        'is_pendaftaran_lunas',
        'is_ppdb_lunas',
        'is_mpls_lunas',
        'is_awal_tahun_lunas',
        'tanggal_bayar_pendaftaran',
        'tanggal_bayar_ppdb',
        'tanggal_bayar_mpls',
        'tanggal_bayar_awal_tahun',
        'keterangan',
        'tahun_ajaran_id'
    ];

    protected $casts = [
        'is_pendaftaran_lunas' => 'boolean',
        'is_ppdb_lunas' => 'boolean',
        'is_mpls_lunas' => 'boolean',
        'is_awal_tahun_lunas' => 'boolean',
        'tanggal_bayar_pendaftaran' => 'date',
        'tanggal_bayar_ppdb' => 'date',
        'tanggal_bayar_mpls' => 'date',
        'tanggal_bayar_awal_tahun' => 'date'
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendafatran_id', 'id');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(tahunAjaran::class, 'tahun_ajaran_id');
    }

    // Helper Methods
    public function updateStatusPembayaran()
    {
        $this->status_pembayaran = ($this->sisa_pembayaran <= 0) ? 'Lunas' : 'Belum Lunas';
        $this->save();
    }

    public function tambahPembayaran($jumlah, $jenisBiaya, $metodePembayaran = null, $buktiPembayaran = null)
    {
        $this->total_bayar += $jumlah;
        $this->sisa_pembayaran = $this->total_biaya - $this->total_bayar;

        // Update status komponen yang dibayar
        switch($jenisBiaya) {
            case 'pendaftaran':
                $this->is_pendaftaran_lunas = true;
                $this->tanggal_bayar_pendaftaran = now();
                break;
            case 'ppdb':
                $this->is_ppdb_lunas = true;
                $this->tanggal_bayar_ppdb = now();
                break;
            case 'mpls':
                $this->is_mpls_lunas = true;
                $this->tanggal_bayar_mpls = now();
                break;
            case 'awal_tahun':
                $this->is_awal_tahun_lunas = true;
                $this->tanggal_bayar_awal_tahun = now();
                break;
        }

        if ($metodePembayaran) {
            $this->metode_pembayaran = $metodePembayaran;
        }

        if ($buktiPembayaran) {
            $this->bukti_pembayaran = $buktiPembayaran;
        }

        $this->updateStatusPembayaran();
    }

    public function getTotalBiayaAttribute()
    {
        return $this->biaya_pendaftaran +
               $this->biaya_ppdb +
               $this->biaya_mpls +
               $this->biaya_awal_tahun;
    }

    public function getSisaPembayaranAttribute()
    {
        return $this->total_biaya - $this->total_bayar;
    }

    public function isFullyPaid()
    {
        return $this->status_pembayaran === 'Lunas';
    }

    public function getStatusPembayaranLengkapAttribute()
    {
        $status = [];

        if ($this->is_pendaftaran_lunas) $status[] = 'Pendaftaran';
        if ($this->is_ppdb_lunas) $status[] = 'PPDB';
        if ($this->is_mpls_lunas) $status[] = 'MPLS';
        if ($this->is_awal_tahun_lunas) $status[] = 'Awal Tahun';

        return empty($status) ? 'Belum ada pembayaran' : implode(', ', $status) . ' telah lunas';
    }
}
