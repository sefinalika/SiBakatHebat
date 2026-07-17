<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Peserta (anak yang dites) kini punya nama sendiri, terpisah dari pemilik akun.
 * Satu akun guru/wali murid bisa mendaftarkan banyak anak.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peserta', function (Blueprint $table) {
            $table->string('nama', 100)->after('user_id')->nullable();
            $table->string('kelas', 50)->nullable()->after('nama_sekolah');
        });

        // Data lama: satu akun = satu anak, namanya diambil dari nama akun.
        DB::table('peserta')
            ->whereNull('nama')
            ->update(['nama' => DB::raw('(select name from users where users.id = peserta.user_id)')]);

        DB::table('peserta')->whereNull('nama')->update(['nama' => 'Tanpa Nama']);

        Schema::table('peserta', function (Blueprint $table) {
            $table->string('nama', 100)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('peserta', function (Blueprint $table) {
            $table->dropColumn(['nama', 'kelas']);
        });
    }
};
