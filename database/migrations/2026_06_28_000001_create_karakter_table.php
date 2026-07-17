<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel master 40 karakter TB-40 (sumber konten hasil observasi).
     * id 1-40 ditetapkan manual sesuai urutan abjad (bukan auto-increment).
     */
    public function up(): void
    {
        Schema::create('karakter', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary()->comment('1-40, urut abjad');
            $table->string('kode', 30)->unique()->comment("slug, contoh: 'adaalah'");
            $table->string('nama_karakter', 50);
            $table->string('nama_arab', 100)->nullable();
            $table->string('terjemahan', 50)->nullable();
            $table->text('label_diri')->nullable();
            $table->text('definisi')->nullable();
            $table->enum('dimensi', ['bakat', 'akal', 'hati'])->nullable();
            $table->enum('kelompok', ['karsa', 'cipta', 'rasa'])->nullable();
            $table->enum('tipe', ['introvert', 'extrovert'])->nullable();
            $table->text('profesi')->nullable();
            $table->text('jurusan')->nullable();
            $table->string('sifat_tercela_melalaikan', 100)->nullable();
            $table->text('cara_memperbaiki_melalaikan')->nullable();
            $table->string('sifat_tercela_berlebihan', 100)->nullable();
            $table->text('cara_memperbaiki_berlebihan')->nullable();
            $table->unsignedTinyInteger('nomor_soal')->comment('soal 37-76');
            $table->unsignedTinyInteger('urut_abjad')->comment('1-40');
            $table->unsignedTinyInteger('urut_grafik')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karakter');
    }
};
