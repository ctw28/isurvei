<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jawaban extends Model
{
    use HasFactory;
    protected $table = "survei_jawabans";
    protected $fillable = [
        'sesi_id',
        'pertanyaan_id',
        'jawaban',
    ];

    public function pertanyaan()
    {
        return $this->belongsTo('App\Models\SurveiPertanyaan', 'pertanyaan_id');
    }

    public function sesi()
    {
        return $this->belongsTo('App\Models\SurveiSesi', 'sesi_id');
    }

    public function jawabanLainnya()
    {
        return $this->hasOne('App\Models\JawabanLainnya');
    }
}
