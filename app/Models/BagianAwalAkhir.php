<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BagianAwalAkhir extends Model
{
    use HasFactory;

    protected $table = "survei_bagian_awal_akhirs";
    protected $fillable = [
        'survei_id',
        'bagian_id_first',
        'bagian_id_last',
    ];

    public function survei()
    {
        return $this->belongsTo('App\Models\Survei');
    }
    public function bagianFirst()
    {
        return $this->belongsTo('App\Models\SurveiBagian', 'bagian_id_first');
    }
    public function bagianLast()
    {
        return $this->belongsTo('App\Models\SurveiBagian', 'bagian_id_last');
    }
}
