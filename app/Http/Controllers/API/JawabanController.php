<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jawaban;
use App\Models\SurveiPertanyaan;

class JawabanController extends Controller
{
    //
    public function index($surveiId, $pertanyaanId)
    {
        $pertanyaan = SurveiPertanyaan::with('pilihanJawaban')->find(($pertanyaanId));
        // return $pertanyaan->pilihanJawaban;
        $pertanyaan->pilihanJawaban->map(function ($data) use ($pertanyaanId) {
            $total = Jawaban::where(['pertanyaan_id' => $pertanyaanId, 'jawaban' => $data->pilihan_jawaban])
                ->count();
            $data->total = $total;
        });
        // return $pertanyaan;
        return response()->json([
            'status' => true,
            'data' => $pertanyaan,
            'pesan' => 'data ditemukan',
        ]);
    }
}
