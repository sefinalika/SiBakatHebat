<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Skor 40 karakter TB-40 per observasi (soal 37-76).
     */
    public function up(): void
    {
        Schema::create('hasil_observasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('observasi_id')->constrained('observasi')->cascadeOnDelete();
            $table->unsignedTinyInteger('karakter_id');
            $table->foreign('karakter_id')->references('id')->on('karakter')->cascadeOnDelete();
            $table->decimal('skor', 4, 2);
            $table->enum('kategori', ['hitam', 'abu', 'hijau', 'kuning', 'merah']);
            $table->timestamps();

            $table->unique(['observasi_id', 'karakter_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_observasi');
    }
};
