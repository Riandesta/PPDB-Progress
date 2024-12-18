<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('tahun_ajaran')) {
            Schema::create('tahun_ajaran', function (Blueprint $table) {
                $table->id();
                $table->string('tahun_ajaran'); // Tambahkan kolom ini
                $table->year('tahun_mulai');
                $table->year('tahun_selesai');
                $table->boolean('is_active')->default(false); // Gunakan boolean untuk status
                $table->date('tanggal_mulai');
                $table->date('tanggal_selesai');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahun_ajaran');
    }
};
