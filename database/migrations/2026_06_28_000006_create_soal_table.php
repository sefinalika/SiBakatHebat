<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Master 76 soal observasi (teks pertanyaan + bagian/section).
     */
    public function up(): void
    {
        Schema::create('soal', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('nomor_soal')->unique()->comment('1-76');
            $table->text('teks');
            $table->enum('bagian', ['aqidah', 'ibadah', 'karakter_belajar', 'karakter_bakat']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soal');
    }
};
