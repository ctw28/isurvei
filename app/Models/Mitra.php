<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    use HasFactory;
    protected $table = "survei_mitras";

    protected $fillable = [
        'mitra_nama',
        'mitra_instansi',
        'mitra_jabatan',
    ];

    public function mitraSesi()
    {
        return $this->hasMany('App\Models\MitraSesi', 'mitra_id');
    }
}
