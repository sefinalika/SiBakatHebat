<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jawaban', function (Blueprint $table) {
            $table->id();
            $table->foreignId('observasi_id')->constrained('observasi')->cascadeOnDelete();
            $table->unsignedTinyInteger('nomor_soal')->comment('1-76');
            $table->unsignedTinyInteger('nilai')->comment('skala 1-10');
            $table->timestamps();

            $table->unique(['observasi_id', 'nomor_soal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawaban');
    }
};
