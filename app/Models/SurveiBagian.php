<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveiBagian extends Model
{
    use HasFactory;

    protected $fillable = [
        'survei_id',
        'bagian_nama',
        'bagian_kode',
        'bagian_urutan',
        'bagian_parent',
    ];

    public function survei()
    {
        return $this->belongsTo('App\Models\Survei');
    }

    public function bagianParent()
    {
        return $this->belongsTo('App\Models\SurveiBagian');
    }

    public function pertanyaan()
    {
        return $this->hasMany('App\Models\SurveiPertanyaan', 'bagian_id');
    }

    public function bagianDirect()
    {
        return $this->hasOne('App\Models\BagianDirect', 'bagian_id');
    }
}
