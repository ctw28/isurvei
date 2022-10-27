<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;

    protected $table = "iservei_roles";

    protected $fillable = [
        'nama_role',
        'keterangan',
    ];

    public function user()
    {
        return $this->hasMany('App\Models\User');
    }
}
