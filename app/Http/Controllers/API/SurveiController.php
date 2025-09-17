<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Jawaban;
use App\Models\Survei;
use App\Models\SurveiBagian;
use App\Models\SurveiPertanyaan;
use App\Models\SurveiSesi;
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

    public function getSesi($surveiId, Request $request)
    {
        // return $surveiId;
        // return $prodi_id;
        $survei = Survei::find($surveiId);
        // return $survei;
        if ($survei->survei_untuk == "mahasiswa") {
            $prodi_id = $request->input('prodi_id');
            if ($prodi_id)
                $sesi = SurveiSesi::with(['user.userMahasiswa.mahasiswa.dataDiri', 'user.userMahasiswa.mahasiswa.prodi'])
                    ->whereHas('user.userMahasiswa.mahasiswa.prodi', function ($query) use ($prodi_id) {
                        $query->where('id', $prodi_id);
                    })
                    ->where('survei_id', $surveiId)->get();
            // ->where(['survei_id' => $surveiId, 'sesi_status' => "1"])->get();
        } else if ($survei->survei_untuk == "dosen" || $survei->survei_untuk == "pegawai") {

            $sesi = SurveiSesi::with(['user.userPegawai.pegawai.dataDiri'])->where('survei_id', $surveiId)->get();
        } else {
            $sesi = MitraSesi::with(['mitra'])->where('survei_id', $surveiId)->get();
        }
        // $bagian = SurveiBagian::with(['pertanyaan', 'survei'])->where('survei_id', $surveiId)->get();
        return $sesi;

        // $surveiId = $request->survei_id;
        // $fakultas = $request->fakultas;
        // $fakultasId = match ($fakultas) {
        //     "02" => 3,
        //     "03" => 4,
        //     "04" => 5,
        //     "05" => 6,
        //     default => 2,
        // };

        // $sesi = SurveiSesi::with(['user.mahasiswa.dataDiri', 'user.mahasiswa.prodi'])
        //     ->whereHas('user.mahasiswa.prodi', function ($prodi) use ($fakultasId) {
        //         $prodi->where('organisasi_parent_id', $fakultasId);
        //     })
        //     ->where('survei_id', $surveiId)
        //     ->whereHas('jawaban')
        //     ->get();

        // return response()->json($sesi);
    }


    public function getJawaban(Request $request)
    {
        $jawaban = Jawaban::where('sesi_id', $request->sesi_id)
            ->select('pertanyaan_id', 'jawaban')
            ->get()
            ->groupBy('pertanyaan_id');

        return response()->json($jawaban);
    }

    public function getPertanyaanCetak($surveiId)
    {
        $stepIds = SurveiBagian::where('survei_id', $surveiId)->pluck('id');
        // return $stepIds;
        $pertanyaan = SurveiPertanyaan::whereIn('bagian_id', $stepIds)
            ->select('id', 'bagian_id', 'pertanyaan', 'pertanyaan_urutan')
            ->orderBy('bagian_id')
            // ->orderBy('pertanyaan_urutan')
            ->get();

        return response()->json($pertanyaan);
    }
}
