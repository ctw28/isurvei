<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveiSesi extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'survei_id',
        'sesi_tanggal',
        'sesi_status'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function survei()
    {
        return $this->belongsTo('App\Models\Survei');
    }

    public function jawaban()
    {
        return $this->hasMany('App\Models\Jawaban');
    }
}
