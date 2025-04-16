<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('outlet', function (Blueprint $table) {
            $table->bigIncrements('id_outlet');
            $table->string('nama');
            $table->text('alamat');
            $table->string('no_telp');
            $table->string('id_layanan');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('outlet');
    }
};
