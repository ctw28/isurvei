<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AplikasiList extends Model
{
    use HasFactory;

    protected $fillable = [
        'aplikasi_nama',
        'aplikasi_singkatan',
        'aplikasi_url',
        'aplikasi_keterangan',
        'is_aktif',
    ];

    public function userRole()
    {
        return $this->hasMany('App\Models\UserRoleApp');
    }
}
