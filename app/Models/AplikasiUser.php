<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AplikasiUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'aplikasi_user_role',
        'is_default',
        'is_aktif',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function aplikasiUserRole()
    {
        return $this->belongsTo('App\Models\AplikasiUserRole', 'aplikasi_user_role');
    }
}
