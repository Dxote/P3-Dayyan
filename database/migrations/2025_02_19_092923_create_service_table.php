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
            $table->string('plat_nomor', 15);
            $table->string('nama_motor', 100);
            $table->string('kode_brand', 6);
            $table->text('deskripsi_masalah');
            $table->json('sparepart'); // Simpan dalam bentuk JSON
            $table->json('alat'); // Simpan dalam bentuk JSON
            $table->unsignedBigInteger('user_id'); // ID Pengguna
            $table->unsignedBigInteger('petugas_id'); // ID Petugas
            $table->timestamps();
            // Relasi ke tabel lain
            $table->foreign('kode_brand')->references('kode_brand')->on('brand')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('petugas_id')->references('id')->on('users')->onDelete('cascade');
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
