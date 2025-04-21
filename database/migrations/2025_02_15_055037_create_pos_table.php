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
        Schema::create('pos_management', function (Blueprint $table) {
            $table->bigIncrements('id_pos');
            $table->enum('tipe', ['member', 'outlet', 'general']);
            $table->unsignedBigInteger('id_outlet');
            $table->integer('diskon');
            $table->enum('satuan_diskon', ['persen', 'nominal']);
            $table->date('tanggal_mulai');
            $table->date('tanggal_akhir');
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brand');
    }
};
