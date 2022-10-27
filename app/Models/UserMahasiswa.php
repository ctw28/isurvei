<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMahasiswa extends Model
{
    protected $fillable = [
        'user_id',
        'mahasiswa_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function mahasiswa()
    {
        return $this->belongsTo('App\Models\Mahasiswa');
    }
}
