<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;
    protected $fillable = [
        'iddata',
        'nim',
        'data_diri_id',
        'organisasi_id',
    ];

    public function userMahasiswa()
    {
        return $this->hasOne('App\Models\UserMahasiswa');
    }
    public function dataDiri()
    {
        return $this->belongsTo('App\Models\DataDiri');
    }
    public function prodi()
    {
        return $this->belongsTo('App\Models\Organisasi', 'organisasi_id');
    }
}
