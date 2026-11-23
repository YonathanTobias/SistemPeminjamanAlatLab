<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengaturan_labs', function (Blueprint $table) {
            $table->string('nama_ketua')->default('apt. Wida Padminingsih, S.Farm., M.Farm.')->after('nip_kepala_lab');
            $table->string('nip_ketua')->default('NIDN. 0725048201')->after('nama_ketua');
            $table->string('nama_kabag_sarpras')->default('Petrus Tobias, S.Kom.')->after('nip_ketua');
            $table->string('nip_kabag_sarpras')->default('NIP. 2021.08.045')->after('nama_kabag_sarpras');
            $table->string('kota_dokumen')->default('Malang')->after('nip_kabag_sarpras');
            $table->date('tanggal_dokumen')->nullable()->after('kota_dokumen');
        });
    }

    public function down(): void
    {
        Schema::table('pengaturan_labs', function (Blueprint $table) {
            $table->dropColumn([
                'nama_ketua',
                'nip_ketua',
                'nama_kabag_sarpras',
                'nip_kabag_sarpras',
                'kota_dokumen',
                'tanggal_dokumen',
            ]);
        });
    }
};
