<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use App\Models\OrganisasiGrup;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    //

    public function getOrganisasi($flag)
    {
        $data = OrganisasiGrup::with('organisasi')->where('grup_flag', $flag)->get();
        return response()->json([
            'status' => true,
            'data' => $data,
            'pesan' => 'data ditemukan',
        ]);
    }
    public function getProdi($fakultasId)
    {
        $data = Organisasi::where('organisasi_parent_id', $fakultasId)->get();
        return response()->json([
            'status' => true,
            'data' => $data,
            'pesan' => 'data ditemukan',
        ]);
    }
}
