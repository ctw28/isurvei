<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRoleApp extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_id',
        'user_id',
        'aplikasi_id',
        'is_default',
    ];

    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function aplikasi()
    {
        return $this->belongsTo('App\Models\AplikasiList', 'aplikasi_id');
    }
}
