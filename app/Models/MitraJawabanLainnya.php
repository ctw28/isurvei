<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MitraJawabanLainnya extends Model
{
    use HasFactory;

    protected $table = "survei_mitra_jawaban_lainnyas";
    protected $fillable = [
        'mitra_jawaban_id',
        'jawaban'
    ];

    public function mitraJawaban()
    {
        return $this->belongsTo('App\Models\MitraJawaban', 'mitra_jawaban_id');
    }
}
