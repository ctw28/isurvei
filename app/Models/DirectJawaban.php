<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectJawaban extends Model
{
    use HasFactory;

    protected $table = "survei_direct_jawabans";

    protected $fillable = [
        'pilihan_jawaban_id',
        'bagian_id'
    ];

    public function bagian()
    {
        return $this->belongsTo('App\Models\SurveiBagian', 'bagian_id');
    }
    public function pilihanJawaban()
    {
        return $this->belongsTo('App\Models\PilihanJawaban', 'pilihan_jawaban_id');
    }
}
