<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SurveiPertanyaan;
use App\Models\Jawaban;
use App\Models\Survei;
use App\Models\SurveiBagian;
use App\Models\SurveiSesi;
use App\Models\MitraSesi;

class ApiController extends Controller
{
    //
    public function index()
    {
        return array('fdas' => 'asd');
    }

    public function detailJawaban($surveiId, $userId)
    {
        $survei = Survei::find($surveiId);
        // $data = [];
        $data['survei'] = $survei;
        if ($survei->survei_untuk == "mitra") {
            $data['data'] = SurveiBagian::with(['pertanyaan' => function ($pertanyaan) use ($userId) {
                $pertanyaan->with(['jawabanMitra' => function ($jawaban) use ($userId) {
                    $jawaban->where(['mitra_sesi_id' => $userId]);
                }]);
            }])->where('survei_id', $survei->id)->get();
        } else {
            $data['data'] = SurveiBagian::with(['pertanyaan' => function ($pertanyaan) use ($userId) {
                $pertanyaan->with(['jawaban' => function ($jawaban) use ($userId) {
                    $jawaban->where(['user_id' => $userId]);
                }]);
            }])->where('survei_id', $survei->id)->get();
        }
        // $data->map(function ($item) {
        //     $item-
        // });
        // $data['bagian'] = Step::with(['pertanyaan' => function ($pertanyaan) use ($user) {
        //     $pertanyaan->with(['jawaban' => function ($jawaban) use ($user) {
        //         $jawaban->where(['user_id' => $user->id]);
        //     }]);
        // }])->get();
        return $data;
    }

    public function getParticipants($surveiId)
    {
        try {
            $survei = Survei::find($surveiId);
            if ($survei->survei_untuk == "dosen" || $survei->survei_untuk == "pegawai")
                $data = SurveiSesi::with(['user.userPegawai.pegawai.dataDiri'])->where('survei_id', $survei->id)->get();
            else if ($survei->survei_untuk == "mahasiswa")
                $data = SurveiSesi::with(['user.userMahasiswa.mahasiswa.dataDiri'])->where('survei_id', $survei->id)->get();
            else
                $data = MitraSesi::with(['mitra'])->where('survei_id', $survei->id)->get();

            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'details' => $data,
                'survei' => $survei,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function getPertanyaan($bagianId)
    {
        return SurveiPertanyaan::with(['textProperties'])->where(['bagian_id' => $bagianId])->get();
    }

    public function getCountJawaban(Request $request)
    {
        $pertanyaanId = $request->pertanyaanId;
        $usersId = json_decode($request->usersId);
        $tahunId = json_decode($request->filter);
        $users = [];
        if ($request->filter == "-") {
            $users = $usersId;
        } else {
            $userTahunAjar = Jawaban::where(['pertanyaan_id' => 2])->whereIn('jawaban', $tahunId)->whereIn('user_id', $usersId)->get();

            // $userTahunAjar = Jawaban::where(['pertanyaan_id' => 2, 'jawaban' => $request->filter])->whereIn('user_id', $usersId)->get();
            foreach ($userTahunAjar as $item) {
                $users[] = $item->user_id;
            }
        }
        // $userTahunAjar->map(function ($user) use ($users) {
        // });
        // $users["aa"] = "mantap";
        // return $users;
        // if ($usersId == null)
        //     $usersId = [];
        $data['dataPertanyaan'] = SurveiPertanyaan::with([
            'jawabanJenis'
        ])->where('id', $pertanyaanId)->get();
        $data['dataPertanyaan'][0]->jawabanJenis->map(function ($data) use ($pertanyaanId, $users) {
            if ($users == null)
                $total = 0;
            // $total = Jawaban::where(['pertanyaan_id' => $pertanyaanId, 'jawaban' => $data->pilihan_jawaban])->count();
            else
                $total = Jawaban::where(['pertanyaan_id' => $pertanyaanId, 'jawaban' => $data->pilihan_jawaban])
                    ->whereIn('user_id', $users)->count();
            $data->total = $total;
        });
        $data['asdfa'] = $request->all();
        $data['user'] = $users;
        return $data;
    }

    public function getAngkaResult(Request $request)
    {
        $pertanyaanId = $request->pertanyaanId;
        $usersId = json_decode($request->usersId);
        if ($usersId == null) {
            $data['dataPertanyaan'] = SurveiPertanyaan::with('jawaban')->where('id', $pertanyaanId)->get();
        } else {
            $data['dataPertanyaan'] = SurveiPertanyaan::with(['jawaban' => function ($jawaban) use ($usersId) {
                $jawaban->whereIn('user_id', $usersId);
            }])->where('id', $pertanyaanId)->get();
        }
        $data['dataPertanyaan']->map(function ($data) use ($usersId) {
            if ($usersId == null) {
                $data->total = 0;
                $data->rata = 0;
                $data->max = 0;
                $data->min = 0;
                return;
            }
            $total = $data->jawaban->reduce(function ($tot, $data) {
                return $tot + $data->jawaban;
            }, 0);
            $angka = [];
            // $data->jawaban->foreach(function ($data) {
            //     $angka[] = $data->jawaban;
            // });
            foreach ($data->jawaban as $jawaban) {
                $angka[] = (float)$jawaban->jawaban;
            }
            $data->total = number_format($total, 0, ',', '.');
            $data->rata = round($total / count($data->jawaban), 2);
            // $data->rata = number_format(round($total / count($data->jawaban), 2), 0, ',', '.');
            $data->max = number_format(max($angka), 0, ',', '.');
            $data->min = number_format(min($angka), 0, ',', '.');
        });
        return $data;
    }

    public function getfilteredData(Request $request)
    {
        // $users = [];
        // if ($request->filter == "-")
        //     $userTahunAjar = Jawaban::where(['pertanyaan_id' => 2])->get();
        // else
        //     $userTahunAjar = Jawaban::where(['pertanyaan_id' => 2, 'jawaban' => $request->filter])->get();
        // foreach ($userTahunAjar as $item) {
        //     $users[] = $item->user_id;
        // }

        // $user = User::whereIn('id', $users)->get();
        // $users = [];

        // foreach ($user as $item) {
        //     $users[] = $item->name;
        // }
        // return $users;
    }


    public function isParticipated(Request $request)
    {

        $id = $request->id;
        $data = Survei::with(['sesi' => function ($sesi) use ($id) {
            $sesi->with(['user.userPegawai.pegawai' => function ($pegawai) use ($id) {
                $pegawai->where('pegawai_nomor_induk', $id);
            }])
                ->whereHas('user.userPegawai.pegawai', function ($pegawai) use ($id) {
                    $pegawai->where('pegawai_nomor_induk', $id);
                });
            // ->where('sesi_status', "0");
        }])
            ->where([
                'survei_untuk' => $request->kategori,
                'harus_diisi' => true,
            ])
            ->get();
        $status = [];
        foreach ($data as $item) {
            if (count($item->sesi) == 0) {
                $status = [
                    'status' => false,
                    'pesan' => "Mohon mengisi survei terlebih dahulu untuk dapat menggunakan aplikasi",
                    'link' => "https://isurvei.iainkendari.ac.id/",
                ];
                break;
            } else if ($item->sesi[0]->sesi_status == "0") {
                $status = [
                    'status' => false,
                    'pesan' => "Mohon mengisi survei terlebih dahulu untuk dapat menggunakan aplikasi",
                    'link' => "https://isurvei.iainkendari.ac.id/",
                ];
                break;
            } else {
                $status = [
                    'status' => true,
                    'pesan' => "sudah selesaimi",
                    'link' => null,
                ];
            }
        };
        return $status;
        return array('data' => $status);
    }
}
