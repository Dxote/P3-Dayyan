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
        Schema::create('service', function (Blueprint $table) {
              $table->string('kode_service', 10)->primary();
            $table->string('plat_nomor', 20);
            $table->string('nama_motor', 255);
            $table->string('kode_brand')->index();
            $table->text('deskripsi_masalah');
            $table->unsignedBigInteger('user_id')->index(); // ID pelanggan
            $table->unsignedBigInteger('petugas_id')->index(); // ID petugas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service');
    }
};
