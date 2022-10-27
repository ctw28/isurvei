<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
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
    public function defaultRole()
    {
        return $this->where('is_default', true);
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
