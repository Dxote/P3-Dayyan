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
        Schema::create('absensi', function (Blueprint $table) {
            $table->string('kode_absen', 12)->primary();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relasi ke user
            $table->string('kode_shift', 10);
            $table->foreign('kode_shift')->references('kode_shift')->on('shift')->onDelete('cascade'); // Relasi ke shift
            $table->date('tanggal_absen');
            $table->time('jam_absen')->nullable(); // Waktu absensi
            $table->enum('status', ['hadir', 'izin', 'sakit', 'tanpa keterangan'])->default('tanpa keterangan');
            $table->string('keterangan', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
