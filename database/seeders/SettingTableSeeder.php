<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('setting')->insert([
            'nama_sekolah' => 'Nama Sekolah Contoh',
            'path_logo' => 'path/to/logo.png',
            // Tambahkan kolom lain yang diperlukan
        ]);
    }
}
