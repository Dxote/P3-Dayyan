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
        Schema::create('sparepart', function (Blueprint $table) {
            $table->string('kode_sparepart', 6)->primary();
            $table->string('nama_sparepart', 255);
            $table->string('stok', 255);
            $table->string('harga', 255);
            $table->string('jumlah_satuan', 255);
            $table->string('kode_satuan')-> index();
            $table->string('kode_brand')-> index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sparepart');
    }
};
