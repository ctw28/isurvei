<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PilihanJawaban extends Model
{
    use HasFactory;

    protected $table = 'survei_pilihan_jawabans';

    protected $fillable = [
        'pertanyaan_id',
        'pilihan_jawaban',
        'urutan'
    ];

    public function pertanyaan()
    {
        return $this->belongsTo('App\Models\Pertanyaan', 'pertanyaan_id');
    }

    public function jawabanRedirect()
    {
        return $this->hasOne('App\Models\JawabanRedirect',);
    }
}
