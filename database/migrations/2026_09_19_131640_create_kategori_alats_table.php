<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('kategori_alats')) {
            Schema::create('kategori_alats', function (Blueprint $table) {
                $table->id();
                $table->string('nama_kategori')->unique();
                $table->string('ikon')->default('bi-grid-fill');
                $table->text('deskripsi')->nullable();
                $table->timestamps();
            });

            $defaultCategories = [
                [
                    'nama_kategori' => 'KDM & Tanda Vital',
                    'ikon' => 'bi-heart-pulse-fill',
                    'deskripsi' => 'Alat kebutuhan dasar manusia, pemeriksaan tanda vital (TTV), dan diagnostik fisik dasar.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama_kategori' => 'Simulasi & Manikin',
                    'ikon' => 'bi-person-arms-up',
                    'deskripsi' => 'Phantom peraga anatomi, manikin CPR/RJP, manikin infus, dan model persalinan.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama_kategori' => 'Elektromedis & Terapi',
                    'ikon' => 'bi-lightning-charge-fill',
                    'deskripsi' => 'Peralatan terapi elektrik, nebulizer, suction pump, syringe pump, dan EKG.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama_kategori' => 'Instrumen Bedah Minor',
                    'ikon' => 'bi-scissors',
                    'deskripsi' => 'Set heacting (jahit luka), pinset anatomis/sirurgis, gunting perban, dan bak instrumen.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama_kategori' => 'Mobilisasi & Rehabilitasi',
                    'ikon' => 'bi-universal-access',
                    'deskripsi' => 'Kursi roda, kruk ketiak, walker, matras dekubitus, dan tandu evakuasi medis.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];

            DB::table('kategori_alats')->insert($defaultCategories);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_alats');
    }
};
