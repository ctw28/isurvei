<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MitraJawaban extends Model
{
    use HasFactory;

    protected $table = "survei_mitra_jawabans";

    protected $fillable = [
        'mitra_sesi_id',
        'pertanyaan_id',
        'jawaban',
    ];

    public function mitraSesi()
    {
        return $this->belongsTo('App\Models\MitraSesi', 'mitra_sesi_id');
    }

    public function pertanyaan()
    {
        return $this->belongsTo('App\Models\SurveiPertanyaan', 'pertanyaan_id');
    }

    public function mitraJawabanLainnya()
    {
        return $this->hasOne('App\Models\MitraJawabanLainnya');
    }
}
