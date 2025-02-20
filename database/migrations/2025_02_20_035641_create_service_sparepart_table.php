<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_sparepart', function (Blueprint $table) {
            $table->string('kode_service_sparepart', 10)->primary();
            $table->string('kode_service', 10)->index();
            $table->string('kode_sparepart', 6)->index();
            $table->foreign('kode_service')->references('kode_service')->on('service')->onDelete('cascade');
            $table->foreign('kode_sparepart')->references('kode_sparepart')->on('sparepart')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_sparepart');
    }
};

