<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Blameable;

class Survei extends Model
{
    // use HasFactory;
    use Blameable;

    protected $fillable = [
        'survei_nama',
        'survei_oleh',
        'survei_deskripsi',
        'survei_untuk',
        'is_aktif',
        'is_wajib',
        'survei_status',
        'created_by',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'survei_oleh');
    }
    public function bagian()
    {
        return $this->hasMany('App\Models\SurveiBagian');
    }
    public function bagianAwalAkhir()
    {
        return $this->hasOne('App\Models\BagianAwalAkhir');
    }
    public function sesi()
    {
        return $this->hasMany('App\Models\SurveiSesi');
    }
}
