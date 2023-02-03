<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveiPertanyaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'bagian_id',
        'pertanyaan',
        'pertanyaan_urutan',
        'pertanyaan_jenis_jawaban',
        'required',
        'lainnya'
    ];

    public function bagian()
    {
        return $this->belongsTo('App\Models\SurveiBagian', 'bagian_id');
    }

    public function pilihanJawaban()
    {
        return $this->hasMany('App\Models\PilihanJawaban', 'pertanyaan_id');
    }
    public function jawaban()
    {
        return $this->hasMany('App\Models\Jawaban', 'pertanyaan_id');
    }
    public function jawabanMitra()
    {
        return $this->hasMany('App\Models\MitraJawaban', 'pertanyaan_id');
    }
    public function textProperties()
    {
        return $this->hasOne('App\Models\SurveiTextAtribut', 'pertanyaan_id');
    }
}
