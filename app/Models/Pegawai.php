<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $fillable = [
        'idpeg',
        'pegawai_nomor_induk',
        'data_diri_id',
        'pegawai_kategori_id',
        'pegawai_jenis_id',
    ];

    public function userPegawai()
    {
        return $this->hasOne('App\Models\UserPegawai');
    }
    public function pegawaiDosen()
    {
        return $this->hasOne('App\Models\PegawaiDosen');
    }
    public function dataDiri()
    {
        return $this->belongsTo('App\Models\DataDiri');
    }
}
