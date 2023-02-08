<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SurveiBagian;

class BagianController extends Controller
{
    public function index($surveiId)
    {
        $data = SurveiBagian::where('survei_id', $surveiId)->get();
        return response()->json([
            'status' => true,
            'data' => $data,
            'pesan' => 'data ditemukan',
        ]);
    }
}
