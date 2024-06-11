<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use App\Models\OrganisasiGrup;
use App\Models\User;
use App\Models\SurveiSesi;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    //
    public function getSesi($surveiId, $userId)
    {
        $data = SurveiSesi::where(['user_id' => $userId, 'survei_id' => $surveiId])->orderBy('created_at', 'DESC')->get();
        return response()->json([
            'status' => true,
            'data' => $data,
            'pesan' => 'data ditemukan',
        ]);
    }

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

    public function changePassword()
    {
        // return "gg";
        $data = User::all();
        foreach ($data as $index => $user) {
            if ($index > 2000 && $index <= 3000) {
                echo $index . "<br>";
                $user->password = bcrypt($user->username);
                $user->save();
            }
        }
    }
}
