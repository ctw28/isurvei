<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganisasiGrup extends Model
{
    use HasFactory;

    protected $fillable = [
        'grup_nama',
        'grup_singkatan',
        'grup_flag',
        'grup_keterangan',
    ];

    public function organisasi()
    {
        return $this->hasMany('App\Models\Organisasi');
    }
}
