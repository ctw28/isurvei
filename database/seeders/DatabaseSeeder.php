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

        DB::table('survei_roles')->insert([
            // ['nama_role' => 'administrator', "keterangan" => "admin utama"],
            // ['nama_role' => 'admin_fakultas', "keterangan" => "admin untuk mengelola fakultas"],
            // ['nama_role' => 'mahasiswa', "keterangan" => "akun mahasiswa"],
            ['nama_role' => 'pegawai', "keterangan" => "akun Tenaga Kependidikan"],
            ['nama_role' => 'dosen', "keterangan" => "akun Pendidik"],
        ]);


        DB::table('iservei_user_roles')->insert([
            ["user_id" => 1, "role_id" => 1, 'is_default' => true],
            ["user_id" => 4, "role_id" => 2, 'is_default' => true],
            ["user_id" => 5, "role_id" => 2, 'is_default' => true],
            ["user_id" => 6, "role_id" => 2, 'is_default' => true],
            ["user_id" => 7, "role_id" => 2, 'is_default' => true],
        ]);
    }
}
