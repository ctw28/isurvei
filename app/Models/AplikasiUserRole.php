<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AplikasiUserRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'aplikasi_id',
        'user_level_id',
        'organisasi_id',
        'user_role_nama',
        'is_default',
        'create',
        'read',
        'update',
        'delete',
        'print',
    ];

    public function userLevel()
    {
        return $this->belongsTo('App\Models\UserLevel', 'user_level_id');
    }

    public function aplikasi()
    {
        return $this->belongsTo('App\Models\AplikasiList', 'aplikasi_id');
    }

    public function organisasiId()
    {
        return $this->belongsTo('App\Models\Organisasi', 'organisasi_id');
    }

    public function userAplikasi()
    {
        return $this->hasMany('App\Models\UserAplikasi');
    }
}
