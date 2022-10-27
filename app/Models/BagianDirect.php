<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BagianDirect extends Model
{
    protected $table = 'survei_bagian_directs';
    protected $fillable = [
        // 'survei_id',
        'bagian_id',
        'bagian_id_direct',
        'bagian_id_direct_back',
        'is_direct_by_jawaban',
        'is_first',
        'is_last',
    ];

    // public function survei()
    // {
    //     return $this->belongsTo('App\Models\Survei');
    // }
    public function bagian()
    {
        return $this->belongsTo('App\Models\SurveiBagian');
    }
    public function direct()
    {
        return $this->belongsTo('App\Models\SurveiBagian', 'bagian_id_direct');
    }
    public function directBack()
    {
        return $this->belongsTo('App\Models\SurveiBagian', 'bagian_id_direct_back');
    }
}
