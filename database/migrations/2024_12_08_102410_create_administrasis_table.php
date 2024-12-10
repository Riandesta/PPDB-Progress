<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/2024_01_01_create_administrasis_table.php
public function up()
{
    Schema::create('administrasis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('calon_siswa_id')->constrained('pendaftarans')->onDelete('cascade');
        $table->decimal('biaya_pendaftaran', 10, 2)->default(100000);
        $table->decimal('biaya_ppdb', 10, 2)->default(5000000);
        $table->enum('status_pendaftaran', ['Belum Bayar', 'Sudah Bayar'])->default('Belum Bayar');
        $table->enum('status_ppdb', ['Belum Bayar', 'Sudah Bayar'])->default('Belum Bayar');
        $table->decimal('total_pembayaran', 10, 2)->default(0);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administrasis');
    }
};
