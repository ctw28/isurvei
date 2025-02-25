<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Survei;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class SurveiController extends Controller
{
    //
    public function index($organisasi, $untuk)
    {
        // return $untuk;
        $data = Survei::where([
            'organisasi_id' => $organisasi,
            'survei_untuk' => $untuk
        ])
            ->orderBy('created_at', 'DESC')
            ->orderBy('is_sia', 'DESC')

            ->get();
        foreach ($data as $item) {
            if ($item->survei_untuk == "mitra")
                $item->decrypt_id = Crypt::encrypt($item->id);
            $item->id_encrypt = Crypt::encrypt($item->id);
            $item->bagianAwalAkhir->bagian_id_first_encrypt = Crypt::encrypt($item->bagianAwalAkhir->bagian_id_first);
        }
        return response()->json([
            'status' => true,
            'message' => 'Data ditemukan',
            'data' => $data,
        ], 200);
    }
    public function indexLihatOnly($untuk)
    {
        // return $untuk;
        $data = Survei::with('createdBy.adminOrganisasi')
            ->where([
                'survei_untuk' => $untuk
            ])
            ->orderBy('created_at', 'DESC')
            ->orderBy('is_sia', 'DESC')

            ->get();
        foreach ($data as $item) {
            if ($item->survei_untuk == "mitra")
                $item->decrypt_id = Crypt::encrypt($item->id);
            $item->id_encrypt = Crypt::encrypt($item->id);
            $item->bagianAwalAkhir->bagian_id_first_encrypt = Crypt::encrypt($item->bagianAwalAkhir->bagian_id_first);
        }
        return response()->json([
            'status' => true,
            'message' => 'Data ditemukan',
            'data' => $data,
        ], 200);
    }
}
