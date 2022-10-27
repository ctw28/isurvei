<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanLainnya extends Model
{
    use HasFactory;
    protected $table = "survei_jawaban_lainnyas";
    protected $fillable = [
        'jawaban_id',
        'jawaban'
    ];

    public function jawaban()
    {
        return $this->hasOne('App\Models\Jawaban');
    }
}
