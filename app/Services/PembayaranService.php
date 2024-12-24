<?php

namespace App\Services;

use App\Models\Administrasi;
use App\Models\RiwayatPembayaran;
use Illuminate\Support\Facades\Storage;

class PembayaranService
{
    public function prosesPembayaran(Administrasi $administrasi, array $data)
    {
        // Validasi jumlah pembayaran
        $this->validasiPembayaran($administrasi, $data);

        // Upload bukti pembayaran jika ada
        if (isset($data['bukti_pembayaran'])) {
            $data['bukti_pembayaran'] = $this->uploadBuktiPembayaran($data['bukti_pembayaran']);
        }

        // Buat record riwayat pembayaran
        $riwayat = $this->createRiwayatPembayaran($administrasi, $data);

        // Update status pembayaran administrasi
        $this->updateStatusPembayaran($administrasi, $data);



        return $riwayat;
    }

    private function validasiPembayaran(Administrasi $administrasi, array $data)
    {
        $biayaKey = 'biaya_' . $data['jenis_pembayaran'];
        $sisaBiaya = $administrasi->$biayaKey;

        if ($data['jumlah_bayar'] > $sisaBiaya) {
            throw new \Exception('Jumlah pembayaran melebihi sisa tagihan');
        }
    }



    private function uploadBuktiPembayaran($file)
    {
        $path = $file->store('bukti-pembayaran', 'public');
        return $path;
    }

    private function createRiwayatPembayaran(Administrasi $administrasi, array $data)
    {
        return RiwayatPembayaran::create([
            'administrasi_id' => $administrasi->id,
            'no_pembayaran' => 'BYR' . date('YmdHis') . rand(100, 999),
            'tanggal_bayar' => now(),
            'jenis_pembayaran' => $data['jenis_pembayaran'],
            'jumlah_bayar' => $data['jumlah_bayar'],
            'metode_pembayaran' => $data['metode_pembayaran'],
            'bukti_pembayaran' => $data['bukti_pembayaran'] ?? null,
            'status' => 'success'
        ]);
    }

    private function updateStatusPembayaran(Administrasi $administrasi, array $data)
    {
        $administrasi->total_bayar += $data['jumlah_bayar'];

        // Update status komponen yang dibayar
        $statusField = 'is_' . $data['jenis_pembayaran'] . '_lunas';
        $biayaField = 'biaya_' . $data['jenis_pembayaran'];
        $tanggalField = 'tanggal_bayar_' . $data['jenis_pembayaran'];

        if ($administrasi->total_bayar >= $administrasi->$biayaField) {
            $administrasi->$statusField = true;
            $administrasi->$tanggalField = now();
        }

        // Update status keseluruhan
        $totalBiaya = $administrasi->biaya_pendaftaran +
                     $administrasi->biaya_ppdb +
                     $administrasi->biaya_mpls +
                     $administrasi->biaya_awal_tahun;

        $administrasi->status_pembayaran =
            ($administrasi->total_bayar >= $totalBiaya) ? 'Lunas' : 'Belum Lunas';

            $administrasi->sisa_pembayaran = $totalBiaya - $administrasi->total_bayar;

        $administrasi->save();
    }
}
