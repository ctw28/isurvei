<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MitraSesi extends Model
{
    use HasFactory;

    protected $table = "survei_mitra_sesis";
    protected $fillable = [
        'mitra_id',
        'survei_id',
        'sesi_tanggal',
        'sesi_status'
    ];

    public function mitra()
    {
        return $this->belongsTo('App\Models\Mitra', 'mitra_id');
    }
    public function survei()
    {
        return $this->belongsTo('App\Models\Survei');
    }
    public function jawaban()
    {
        return $this->hasMany('App\Models\MitraJawaban', 'mitra_sesi_id');
    }
}
