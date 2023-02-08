<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SurveiPertanyaan;

class PertanyaanController extends Controller
{
    //
    public function index($bagianId)
    {
        $data = SurveiPertanyaan::where('bagian_id', $bagianId)->orderBy('pertanyaan_urutan', 'DESC')->get();
        return response()->json([
            'status' => true,
            'data' => $data,
            'pesan' => 'data ditemukan',
        ]);
    }
}
