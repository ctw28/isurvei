<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataDiri extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_lengkap',
        'jenis_kelamin',
        'lahir_tempat',
        'lahir_tanggal',
        'no_hp',
        'alamat_ktp',
        'alamat_domisili',
        'nik',
    ];

    public function mahasiswa()
    {
        return $this->hasOne('App\Models\Mahasiswa');
    }
    public function pegawai()
    {
        return $this->hasOne('App\Models\Pegawai');
    }
}
