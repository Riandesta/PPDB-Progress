<?php
// database/migrations/2024_12_08_102410_create_administrasis_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('administrasis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pendaftaran_id')
            ->nullable()
            ->constrained('pendaftarans')
            ->onDelete('set null');

          

            // Biaya-biaya yang harus dibayar
            $table->decimal('biaya_pendaftaran', 10, 2)->default(100000);
            $table->decimal('biaya_ppdb', 10, 2)->default(5000000);
            $table->decimal('biaya_mpls', 10, 2)->default(250000);
            $table->decimal('biaya_awal_tahun', 10, 2)->default(1500000);

            // Total dan sisa pembayaran
            $table->decimal('total_biaya', 10, 2)
                ->storedAs('biaya_pendaftaran + biaya_ppdb + biaya_mpls + biaya_awal_tahun');
            $table->decimal('total_bayar', 10, 2)->default(0);
            $table->decimal('sisa_pembayaran', 10, 2)
                ->virtualAs('total_biaya - total_bayar');

            // Status pembayaran
            $table->enum('status_pembayaran', ['Belum Lunas', 'Lunas'])
                ->default('Belum Lunas');

            // Tracking pembayaran
            $table->boolean('is_pendaftaran_lunas')->default(false);
            $table->boolean('is_ppdb_lunas')->default(false);
            $table->boolean('is_mpls_lunas')->default(false);
            $table->boolean('is_awal_tahun_lunas')->default(false);

            // Tanggal pembayaran
            $table->date('tanggal_bayar_pendaftaran')->nullable();
            $table->date('tanggal_bayar_ppdb')->nullable();
            $table->date('tanggal_bayar_mpls')->nullable();
            $table->date('tanggal_bayar_awal_tahun')->nullable();

            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('administrasis');
    }
};
