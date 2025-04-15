<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saldo_topup', function (Blueprint $table) {
            $table->bigIncrements('id_topup');
            $table->string('id_user')->unique();
            $table->string('metode')->default('midtrans');
            $table->integer('amount');
            $table->enum('status', ['pending', 'success', 'failed']);
            $table->string('snap_token')->nullable();
            $table->timestamps();
        });        
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_keluar');
    }
};
