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

        DB::table('user_levels')->insert([
            [
                "level_nama" => "super-admin",
                "level_keterangan" => "-",
            ],
            [
                "level_nama" => "admin",
                "level_keterangan" => "-",
            ],
            [
                "level_nama" => "user",
                "level_keterangan" => "-",
            ],
            [
                "level_nama" => "user-guest",
                "level_keterangan" => "-",
            ],
            [
                "level_nama" => "non-login",
                "level_keterangan" => "-",
            ],

        ]);

        // $fatik = "Fakultas Tarbiyah dan Ilmu Keguruan";
        // $febi = "Fakultas Ekonomi dan Bisnis Islam";
        // $fasya = "Fakultas Syariah";
        // $fuad = "Fakultas Ushuluddin Adab dan Dakwah";
        // $pascasarjana = "Pascasarjana";

        // DB::table('organisasi_grups')->insert([
        //     [
        //         "grup_nama" => "Institusi",
        //         "grup_singkatan" => "Institusi",
        //         'pimpinan_sebutan' => "Rektor",
        //         'grup_keterangan' => "Grup Institusi"
        //     ], [
        //         "grup_nama" => "Fakultas",
        //         "grup_singkatan" => "Fakultas",
        //         'pimpinan_sebutan' => "Dekan",
        //         'grup_keterangan' => "Grup Fakultas"
        //     ], [
        //         "grup_nama" => "Program Studi",
        //         "grup_singkatan" => "Prodi",
        //         'pimpinan_sebutan' => "Kepal Program Studi",
        //         'grup_keterangan' => "Grup Prodi"
        //     ], [
        //         "grup_nama" => "Pascasarjana",
        //         "grup_singkatan" => "Pascasarjana",
        //         'pimpinan_sebutan' => "Direktur",
        //         'grup_keterangan' => "Grup Pascasarjana"
        //     ], [
        //         "grup_nama" => "Lembaga",
        //         "grup_singkatan" => "Lembaga",
        //         'pimpinan_sebutan' => "ketua",
        //         'grup_keterangan' => "Grup Lembaga"
        //     ], [
        //         "grup_nama" => "Unit Pelaksana Teknis",
        //         "grup_singkatan" => "UPT",
        //         'pimpinan_sebutan' => "Kepala",
        //         'grup_keterangan' => "Grup Unit Pelaksana Teknis"
        //     ],
        //     [
        //         "grup_nama" => "Biro Administrasi Umum Akademik Kemahasiswaan",
        //         "grup_singkatan" => "Biro AUAK",
        //         'pimpinan_sebutan' => "Kepala Biro",
        //         'grup_keterangan' => "-"
        //     ],
        //     [
        //         "grup_nama" => "Organisasi Kemahasiswaan",
        //         "grup_singkatan" => "UK Lembaga",
        //         'pimpinan_sebutan' => "Ketua",
        //         'grup_keterangan' => "-"
        //     ],

        // ]);
        // DB::table('organisasis')->insert([
        //     [
        //         "organisasi_nama" => $fatik,
        //         "organisasi_singkatan" => "FATIK",
        //         "organisasi_grup_id" => 2,
        //         'organisasi_parent_id' => null,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => $fasya,
        //         "organisasi_singkatan" => "FASYA",
        //         "organisasi_grup_id" => 2,
        //         'organisasi_parent_id' => null,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => $fuad,
        //         "organisasi_singkatan" => "FUAD",
        //         "organisasi_grup_id" => 2,
        //         'organisasi_parent_id' => null,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => $pascasarjana,
        //         "organisasi_singkatan" => "PASCASARJANA",
        //         "organisasi_grup_id" => 4,
        //         'organisasi_parent_id' => null,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => $febi,
        //         "organisasi_singkatan" => "FEBI",
        //         "organisasi_grup_id" => 2,
        //         'organisasi_parent_id' => null,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Pendidikan Agama Islam",
        //         "organisasi_singkatan" => "PAI",
        //         "organisasi_grup_id" => 3,
        //         'organisasi_parent_id' => 1,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Pendidikan Bahasa Arab",
        //         "organisasi_singkatan" => "PBA",
        //         "organisasi_grup_id" => 3,
        //         'organisasi_parent_id' => 1,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Manajemen Pendidikan Islam",
        //         "organisasi_singkatan" => "MPI",
        //         "organisasi_grup_id" => 3,
        //         'organisasi_parent_id' => 1,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Pendidikan Guru Madrasah Ibtidaiyah",
        //         "organisasi_singkatan" => "PGMI",
        //         "organisasi_grup_id" => 3,
        //         'organisasi_parent_id' => 1,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Pendidikan Islam Anak Usia Dini",
        //         "organisasi_singkatan" => "PIAUD",
        //         "organisasi_grup_id" => 3,
        //         'organisasi_parent_id' => 1,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Tadris Bahasa Inggris",
        //         "organisasi_singkatan" => "TBI",
        //         "organisasi_grup_id" => 3,
        //         'organisasi_parent_id' => 1,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Tadris IPA",
        //         "organisasi_singkatan" => "TIPA",
        //         "organisasi_grup_id" => 3,
        //         'organisasi_parent_id' => 1,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Tadris Biologi",
        //         "organisasi_singkatan" => "TBLG",
        //         "organisasi_grup_id" => 3,
        //         'organisasi_parent_id' => 1,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Tadris Fisika",
        //         "organisasi_singkatan" => "TFSK",
        //         "organisasi_grup_id" => 3,
        //         'organisasi_parent_id' => 1,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Tadris Matematika",
        //         "organisasi_singkatan" => "TMTK",
        //         "organisasi_grup_id" => 3,
        //         'organisasi_parent_id' => 1,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Hukum Keluarga Islam (Ahwal Syakhshiyyah)",
        //         "organisasi_singkatan" => "AS",
        //         "organisasi_grup_id" => 3,
        //         'organisasi_parent_id' => 2,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Hukum Ekonomi Syariah (Mua'malah)",
        //         "organisasi_singkatan" => "MU",
        //         "organisasi_grup_id" => 3,
        //         'organisasi_parent_id' => 2,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Hukum Tatanegara (Siyasah Syar'iyyah)",
        //         "organisasi_singkatan" => "HTN",
        //         "organisasi_grup_id" => 3,
        //         'organisasi_parent_id' => 2,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Komunikasi dan Penyiaran Islam",
        //         "organisasi_singkatan" => "KPI",
        //         "organisasi_grup_id" => 3,
        //         'organisasi_parent_id' => 3,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Bimbingan Penyuluhan Islam",
        //         "organisasi_singkatan" => "BPI",
        //         "organisasi_grup_id" => 3,
        //         'organisasi_parent_id' => 3,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Manajemen Dakwah",
        //         "organisasi_singkatan" => "MD",
        //         "organisasi_grup_id" => 3,
        //         'organisasi_parent_id' => 3,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Ilmu Al-Qur'an dan Tafsir",
        //         "organisasi_singkatan" => "IQT",
        //         "organisasi_grup_id" => 3,
        //         'organisasi_parent_id' => 3,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Manajemen Pendidikan Islam",
        //         "organisasi_singkatan" => "MPI S2",
        //         "organisasi_grup_id" => 3,
        //         "organisasi_keterangan" => "",
        //         'organisasi_parent_id' => 4,
        //     ],
        //     [
        //         "organisasi_nama" => "Pendidikan Agama Islam",
        //         "organisasi_singkatan" => "PAI S2",
        //         "organisasi_grup_id" => 3,
        //         'organisasi_parent_id' => 4,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Hukum Keluarga Islam (Ahwal Syakhshiyyah)",
        //         "organisasi_singkatan" => "HKI S2",
        //         "organisasi_grup_id" => 3,
        //         'organisasi_parent_id' => 4,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Ekonomi Syariah",
        //         "organisasi_singkatan" => "ESY S2",
        //         "organisasi_grup_id" => 3,
        //         'organisasi_parent_id' => 4,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Ekonomi Syariah",
        //         "organisasi_singkatan" => "ESY",
        //         "organisasi_grup_id" => 3,
        //         'organisasi_parent_id' => 5,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Perbankan Syariah",
        //         "organisasi_singkatan" => "PBS",
        //         "organisasi_grup_id" => 3,
        //         'organisasi_parent_id' => 5,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Manajemen Bisnis Syariah",
        //         "organisasi_singkatan" => "MBS",
        //         "organisasi_grup_id" => 3,
        //         'organisasi_parent_id' => 5,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Lembaga Penelitian dan Pengabdian Kepada Masyarakat",
        //         "organisasi_singkatan" => "LPPM",
        //         "organisasi_grup_id" => 5,
        //         'organisasi_parent_id' => null,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Lembaga Penjamin Mutu",
        //         "organisasi_singkatan" => "LPM",
        //         "organisasi_grup_id" => 5,
        //         'organisasi_parent_id' => null,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Teknologi Informasi dan Pangkalan Data",
        //         "organisasi_singkatan" => "UPT TIPD",
        //         "organisasi_grup_id" => 6,
        //         'organisasi_parent_id' => null,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Perpustakaan",
        //         "organisasi_singkatan" => "UPT Perpustakaaan",
        //         "organisasi_grup_id" => 6,
        //         'organisasi_parent_id' => null,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Pengembangan Bahasa",
        //         "organisasi_singkatan" => "UPT Bahasa",
        //         "organisasi_grup_id" => 6,
        //         'organisasi_parent_id' => null,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Ma'had Al Jamiah",
        //         "organisasi_singkatan" => "UPT Ma'had",
        //         "organisasi_grup_id" => 6,
        //         'organisasi_parent_id' => null,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Satuan Pengawas Internal",
        //         "organisasi_singkatan" => "SPI",
        //         "organisasi_grup_id" => 1,
        //         'organisasi_parent_id' => null,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Bagian Perencanaan dan Keuangan",
        //         "organisasi_singkatan" => "Keuangan",
        //         "organisasi_grup_id" => 7,
        //         'organisasi_parent_id' => null,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Bagian Umum",
        //         "organisasi_singkatan" => "Umum",
        //         "organisasi_grup_id" => 7,
        //         'organisasi_parent_id' => null,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Bagian Akademik dan Kemahasiswaan",
        //         "organisasi_singkatan" => "AKMA",
        //         "organisasi_grup_id" => 7,
        //         'organisasi_parent_id' => null,
        //         "organisasi_keterangan" => "",
        //     ],
        //     [
        //         "organisasi_nama" => "Kelompok Jabatan Fungsional",
        //         "organisasi_singkatan" => "Jafung",
        //         "organisasi_grup_id" => 7,
        //         'organisasi_parent_id' => null,
        //         "organisasi_keterangan" => "",
        //     ],

        // ]);
    }
}
