<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(10)->create();

        DB::table('aplikasi_lists')->insert([
            [
                "aplikasi_nama" => "Sistem Informasi Akademik",
                "aplikasi_singkatan" => "SIA",
                'aplikasi_url' => "https://sia.iainkendari.ac.id"
            ],
            [
                "aplikasi_nama" => "Sistem Informasi Pegawai",
                "aplikasi_singkatan" => "SIMPEG",
                'aplikasi_url' => "https://apple.iainkendari.ac.id"
            ],
            [
                "aplikasi_nama" => "Aplikasi Pendaftaran PPL",
                "aplikasi_singkatan" => "APPEL",
                'aplikasi_url' => "https://apple.iainkendari.ac.id"
            ],
            [
                "aplikasi_nama" => "Aplikasi Survei IAIN Kendari",
                "aplikasi_singkatan" => "ISURVEI",
                'aplikasi_url' => "https://isurvei.iainkendari.ac.id"
            ],

        ]);
    }
}
