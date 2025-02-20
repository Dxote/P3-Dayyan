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
        Schema::create('service_alat', function (Blueprint $table) {
            $table->string('kode_service_alat', 10)->primary();
            $table->string('kode_service', 10)->index();
            $table->string('kode_alat', 6)->index();
            $table->foreign('kode_service')->references('kode_service')->on('service')->onDelete('cascade');
            $table->foreign('kode_alat')->references('kode_alat')->on('alat')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_alat');
    }
};
