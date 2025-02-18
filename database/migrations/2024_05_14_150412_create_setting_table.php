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
        Schema::create('setting', function (Blueprint $table) {
            $table->bigIncrements('id_setting');
            $table->string('nama_perusahaan', 30);
            $table->string('alamat', 50);
            $table->string('email', 30);
            $table->text('website');
            $table->integer('kodepos')->nullable();
            $table->string('telepon', 20);
            $table->string('path_logo', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting');
    }
};
