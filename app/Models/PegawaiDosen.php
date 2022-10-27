<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PegawaiDosen extends Model
{
    use HasFactory;
    protected $fillable = [
        'pegawai_id',
        'nidn',
        'dosen_status',
    ];

    public function pegawai()
    {
        return $this->belongsTo('App\Models\Pegawai');
    }
}
