<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Blameable;

class SurveiAPI extends Model
{
    // use HasFactory;
    // use Blameable;
    protected $table = "surveis";

    protected $fillable = [
        'survei_nama',
        'survei_oleh',
        'survei_deskripsi',
        'survei_untuk',
        'is_aktif',
        'is_wajib',
        'is_sia',
        'organisasi_id',
        'created_by',
        'updated_by',
    ];

    public function organisasi()
    {
        return $this->belongsTo('App\Models\Organisasi');
    }
    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
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
