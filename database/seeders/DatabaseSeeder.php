<?php

namespace Database\Seeders;

use App\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Dayy',
            'last_name' => 'Fayy',
            'email' => 'yann@gmail.com',
            'email_verified_at' => now(),
            'password' => '12345678', // password
        ]);
        Setting::create([
            'nama_perusahaan' => 'PT Berdaya',
            'alamat' => 'Nindya Biodistrict Hotel',
            'email' => 'berdayaenterprise@gmail.com',
            'website' => 'blckroot.com',
            'kodepos' => '40124',
            'telepon' => '1234567890',
            'path_logo' => 'path/to/logo.png',
        ]);
    }
}
