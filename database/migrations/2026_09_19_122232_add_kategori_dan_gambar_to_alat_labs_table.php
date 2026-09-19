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
        Schema::table('alat_labs', function (Blueprint $table) {
            $table->string('kategori')->default('KDM & Tanda Vital')->after('nama_alat');
            $table->string('gambar')->nullable()->after('kondisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alat_labs', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'gambar']);
        });
    }
};
