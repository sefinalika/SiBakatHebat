<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('observasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('peserta')->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('nama_guru', 100)->nullable();
            $table->string('nama_kepala_sekolah', 100)->nullable();
            $table->string('tahun_ajaran', 20)->nullable()->comment('contoh: 2025/2026');
            $table->string('semester', 20)->nullable()->comment('ganjil/genap');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('observasi');
    }
};
