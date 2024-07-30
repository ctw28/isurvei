<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BagianAwalAkhir;
use Illuminate\Http\Request;
use App\Models\SurveiPertanyaan;
use App\Models\Jawaban;
use App\Models\Survei;
use App\Models\SurveiBagian;
use App\Models\SurveiSesi;
use App\Models\MitraSesi;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use App\Models\User;
use App\Models\UserRole;
use App\Models\DataDiri;
use App\Models\Pegawai;
use App\Models\UserPegawai;
use App\Models\PegawaiDosen;
use App\Models\MasterProdi;
use App\Models\Mahasiswa;
use App\Models\UserMahasiswa;
use App\Models\DirectJawaban;
use App\Models\PilihanJawaban;
use App\Models\SurveiAPI;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    //
    public function index()
    {
        return array('fdas' => 'asd');
    }

    public function authenticate(Request $request)
    {
        // return $request->all();

        $user = User::where('username', $request->username)->first();
        // return $user;
        $token = JWTAuth::fromUser($user);

        JWTAuth::setToken($token)->toUser();
        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
        // $credentials = $request->only('username', 'password');
        $credentials = $request->only('username');

        //valid credential
        $validator = Validator::make($credentials, [
            'username' => 'required',
        ]);
        // $validator = Validator::make($credentials, [
        //     'username' => 'required',
        //     'password' => 'required',
        // ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is validated
        //Crean token
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
            return $credentials;
            return response()->json([
                'success' => false,
                'message' => 'Could not create token.',
            ], 500);
        }

        //Token created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }
    public function logout(Request $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is validated, do logout        
        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
                    $jawaban->where(['sesi_id' => $userId]);
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
                $data = SurveiSesi::with(['user.userPegawai.pegawai.dataDiri'])->where('survei_id', $survei->id)->paginate(20);
            else if ($survei->survei_untuk == "mahasiswa")
                $data = SurveiSesi::with(['user.userMahasiswa.mahasiswa.dataDiri'])->where('survei_id', $survei->id)->paginate(20);
            else
                $data = MitraSesi::with(['mitra'])->where('survei_id', $survei->id)->paginate(20);

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
            'pilihanJawaban'
        ])->where('id', $pertanyaanId)->get();
        $data['dataPertanyaan'][0]->pilihanJawaban->map(function ($data) use ($pertanyaanId, $users) {
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
        // $token = JWTAuth::getToken();
        // $payload = JWTAuth::getPayload($token)->toArray();
        // $id = $payload['id'];
        // $kategori = $payload['kategori'];
        $id = $request->user;
        $kategori = $request->kategori;
        // return $id;
        $user = User::where('username', $id)->first();
        // return $user;
        if (empty($user)) {
            $url = 'https://sia.iainkendari.ac.id/konseling_api/pegawai/' . $kategori . '/' . $id;
            $data = (object) json_decode(file_get_contents($url), true);
            DB::beginTransaction();
            try {
                $name = "";
                $roleId = "";
                if ($request->jenis_akun == "mahasiswa") {
                    $name = $data->nim;
                    $roleId = 4;
                }
                if ($request->jenis_akun == "pegawai") {
                    $name = $data->nip;
                    if ($data->nidn != 'non-nidn')
                        $roleId = 3;
                    else
                        $roleId = 3;
                }

                $user = User::create([
                    'username' => $request->username,
                    'email' => $name . '@mail.com',
                    'password' => bcrypt($request->username)
                ]);

                $userRole = UserRole::create([
                    'user_id' => $user->id,
                    'role_id' => $roleId
                ]);

                $dataDiri = DataDiri::create([
                    'nama_lengkap' => $data->nama,
                    'jenis_kelamin' => ($data->kelamin != '') ? $data->kelamin : "L",
                    'lahir_tempat' => $data->tmplahir,
                    'lahir_tanggal' => $data->tgllahir,
                    'no_hp' => $data->hp,
                    'alamat_ktp' => $data->alamat,
                    'alamat_domisili' => $data->alamat,
                    'nik' => (isset($data->nik) ? $data->nik : '0000000000000000'),
                ]);

                if ($request->jenis_akun == "mahasiswa") {
                    // $prodiSia = $data->idprodi;
                    // if ($prodiSia == "FSK")
                    // $prodiSia = "TFSK";
                    $prodi = Organisasi::where('organisasi_singkatan_sia', $data->idprodi)->first();
                    $mahasiswa = Mahasiswa::create([
                        'nim' => $data->nim,
                        'data_diri_id' => $dataDiri->id,
                        'organisasi_id' => $prodi->id,
                    ]);
                    $userMahasiswa = UserMahasiswa::create([
                        'user_id' => $user->id,
                        'mahasiswa_id' => $mahasiswa->id,
                    ]);
                } else {
                    $kategoriId = 1;
                    $jenisId = 1;
                    if ($data->statuspeg == "NON PNS")
                        $kategoriId = 3;
                    if ($data->dosentetap == "N")
                        $jenisId = 2;

                    $pegawai = Pegawai::create([
                        'idpeg' => $data->idpegawai,
                        'pegawai_nomor_induk' => $data->nip,
                        'data_diri_id' => $dataDiri->id,
                        'pegawai_kategori_id' => $kategoriId,
                        'pegawai_jenis_id' => $jenisId,
                    ]);
                    $userPegawai = UserPegawai::create([
                        'user_id' => $user->id,
                        'pegawai_id' => $pegawai->id,
                    ]);

                    if ($data->nidn != 'non-nidn') {
                        PegawaiDosen::create([
                            'pegawai_id' => $pegawai->id,
                            'nidn' => $data->nidn,
                            'dosen_status' => $data->statusdosen,

                        ]);
                    }
                }
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollback();
                return $th;
            }
        }
        // $kategori = "dosen";

        $data = [];
        $pesan = "Assalamu’alaikum warohmatullahi wabarokatuh
        Dalam rangka evaluasi untuk peningkatan mutu Institut Agama Islam Negeri (IAIN) Kendari, dengan ini mahasiswa diharapkan berpartisipasi dengan mengisi Survey Kepuasan Mahasiswa terhadap Tata Kelola, Keuangan dan Sapras, Layanan diluar Pembelajaran terstruktur, serta Layanan Pendidikan, melalui form berikut.
        Atas Kesediaan dan kerjasamanya, Kami ucapkan terima kasih.
        Tim Survey IAIN Kendari";
        if ($kategori == "pegawai")
            $pesan = "Assalamu’alaikum warohmatullahi wabarokatuh Dalam rangka evaluasi untuk 
            peningkatan mutu Institut Agama Islam Negeri (IAIN) Kendari, dengan ini setiap
             Tenaga Kependidikan (Tendik) diharapkan berpartisipasi dengan mengisi Survey Kepuasan Tendik 
             terhadap layanan pengelolaan SDM, layanan tata kelola, tata pamong dan kerjasama di IAIN 
             Kendari melalui form berikut. Atas Kesediaan dan kerjasamanya, Kami ucapkan terima kasih. 
             Tim Survey IAIN Kendari";
        else if ($kategori == "dosen")
            $pesan = "Assalamu’alaikum warohmatullahi wabarokatuh
            Dalam rangka evaluasi untuk peningkatan mutu Institut Agama Islam Negeri (IAIN) Kendari, dengan ini setiap Dosen diharapkan berpartisipasi dengan mengisi Survey Kepuasan Dosen terhadap layanan penelitian, pengabdian kepada masyarakat, pengelolaan SDM, layanan keuangan, sarana dan prasarana, layanan tata kelola, tata pamong dan kerjasama di IAIN Kendari melalui form berikut.
            Atas Kesediaan dan kerjasamanya, Kami ucapkan terima kasih.
            Tim Survey IAIN Kendari";

        if ($kategori == "dosen" || $kategori == "pegawai") {
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
                    'survei_untuk' => $kategori,
                    'is_wajib' => true,
                    'is_aktif' => true,
                    'is_sia' => true,

                ])
                ->get();
        } else {
            $data = Survei::with(['sesi' => function ($sesi) use ($id) {
                $sesi->with(['user.userMahasiswa.mahasiswa' => function ($pegawai) use ($id) {
                    $pegawai->where('nim', $id);
                }])
                    ->whereHas('user.userMahasiswa.mahasiswa', function ($pegawai) use ($id) {
                        $pegawai->where('nim', $id);
                    });
                // ->where('sesi_status', "0");
            }])
                ->where([
                    'survei_untuk' => $kategori,
                    'is_wajib' => true,
                    'is_aktif' => true,
                    'is_sia' => true,
                    'survei_status' => false,
                ])
                ->get();
        }
        $status = [];
        $belumMulai = 0;
        $belumSelesai = 0;
        foreach ($data as $item) {
            if (count($item->sesi) == 0) {
                $belumMulai++;
                // break;
            }
            foreach ($item->sesi as $sesi) {
                if ($sesi->sesi_status == "0") {

                    $belumSelesai++;
                    // break;
                }
            }
            // else {
            //     $status = [
            //         'status' => true,
            //         'pesan' => "sudah selesaimi",
            //         'link' => null,
            //     ];
            // }
        };
        $pesan = '';
        $status = true;
        if ($belumMulai > 0) {
            $pesan .= $belumMulai . " Survei belum dimulai, ";
        }
        if ($belumSelesai > 0) {
            $pesan .= $belumSelesai . " Survei belum diselesaikan";
        }
        $link = '';
        if ($belumMulai != 0 || $belumSelesai != 0) {

            $status = false;
            // $link = "https://isurvei.iainkendari.ac.id/masuk-survei/" . $id;
            $link = "http://127.0.0.1:8001/masuk-survei/" . $id;
        } else {
            $pesan = 'sudah selesaimi';
        }
        $cek = [
            'status' => $status,
            'pesan' => $pesan,
            // 'link' => "http://127.0.0.1:8000/" . $token,
            'link' => $link
        ];
        return response()->json($cek);
        return array('data' => $status);
    }


    //DIRECT BERDASARKAN JAWABAN

    public function storeJawabanRedirect(Request $request)
    {
        // return $request->all();
        // JawabanRedirect::create($request->all());
        DirectJawaban::updateOrCreate(
            ['pilihan_jawaban_id' => $request->pilihan_jawaban_id],
            ['bagian_id' => $request->bagian_id]
        );
        return array("status" => "sukses");
    }
    public function deleteJawabanRedirect(Request $request)
    {
        try {
            DirectJawaban::where('pilihan_jawaban_id', $request->pilihan_jawaban_id)->delete();
            return array("status" => "sukses");
        } catch (\Throwable $e) {
            return array("status" => "gagal");
        }
    }

    public function surveiUpdate(Request $request, $id)
    {
        // $data['ggwp'] = "ggwp";
        // return $data;

        // return $request->all();
        $survei = Survei::find($id);
        $value = false;
        if ($request->value == "true")
            $value = true;
        $survei[$request->column] = $value;
        // $survei->update($request->all());
        $survei->save();
        return response()->json([
            'status' => 'sukses',
            'message' => 'sukses update',
        ], 200);
    }

    public function copySurvei($surveiIdCopy)
    {
        DB::beginTransaction();

        try {
            //code...
            $data = Survei::with(['bagian.pertanyaan.pilihanJawaban', 'BagianAwalAkhir'])->find($surveiIdCopy);
            // return $data;
            $survei = SurveiAPI::create([
                'survei_nama' => "copy of " . $data->survei_nama,
                'survei_deskripsi' => $data->survei_deskripsi,
                'survei_untuk' => $data->survei_untuk,
                'is_aktif' => 0,
                'is_wajib' => 0,
                'is_sia' => 0,
                'organisasi_id' => $data->organisasi_id,
                'created_by' => $data->created_by,
                'updated_by' => $data->updated_by,
            ]);
            // return $data;
            foreach ($data->bagian as $bagian) {
                $bagianCopy = SurveiBagian::create([
                    'survei_id' => $survei->id,
                    'bagian_nama' => $bagian->bagian_nama,
                    'bagian_kode' => $bagian->bagian_kode,
                    'bagian_urutan' => $bagian->bagian_urutan,
                    'bagian_parent' => $bagian->bagian_parent,
                ]);
                foreach ($bagian->pertanyaan as $pertanyaan) {
                    $pertanyaanCopy = SurveiPertanyaan::create([
                        'bagian_id' => $bagianCopy->id,
                        'pertanyaan' => $pertanyaan->pertanyaan,
                        'pertanyaan_urutan' => $pertanyaan->pertanyaan_urutan,
                        'pertanyaan_jenis_jawaban' => $pertanyaan->pertanyaan_jenis_jawaban,
                        'required' => $pertanyaan->required,
                        'lainnya' => $pertanyaan->lainnya
                    ]);
                    $pilihanJawaban = [];
                    if ($pertanyaan->pertanyaan_jenis_jawaban != "Text" || $pertanyaan->pertanyaan_jenis_jawaban != "Text Panjang") {
                        foreach ($pertanyaan->pilihanJawaban as $pilihan) {
                            $pilihanJawaban[] = [
                                'pertanyaan_id' => $pertanyaanCopy->id,
                                'pilihan_jawaban' => $pilihan->pilihan_jawaban,
                                'urutan' => $pilihan->urutan
                            ];
                        }
                        PilihanJawaban::insert($pilihanJawaban);
                    }
                }
            }
            BagianAwalAkhir::create([
                'survei_id' => $survei->id,
                'bagian_id_first' => $data->bagianAwalAkhir->bagian_id_first,
                'bagian_id_last' => $data->bagianAwalAkhir->bagian_id_last,
            ]);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Data dicopy',
                'details' => $pilihanJawaban,
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            return;
            return response()->json([
                'status' => true,
                'message' => 'Data gagal dicopy',
                'details' => $th,
            ], 500);
        }
    }
    public function copyPertanyaan($bagianId, $bagianIdCopy)
    {
        DB::beginTransaction();

        try {
            //code...
            $data = SurveiBagian::with(['pertanyaan.pilihanJawaban'])->find($bagianId);
            // return $data;
            foreach ($data->pertanyaan as $pertanyaan) {
                $pertanyaanCopy = SurveiPertanyaan::create([
                    'bagian_id' => $bagianIdCopy,
                    'pertanyaan' => $pertanyaan->pertanyaan,
                    'pertanyaan_urutan' => $pertanyaan->pertanyaan_urutan,
                    'pertanyaan_jenis_jawaban' => $pertanyaan->pertanyaan_jenis_jawaban,
                    'required' => $pertanyaan->required,
                    'lainnya' => $pertanyaan->lainnya
                ]);
                $pilihanJawaban = [];
                if ($pertanyaan->pertanyaan_jenis_jawaban != "Text" || $pertanyaan->pertanyaan_jenis_jawaban != "Text Panjang") {
                    foreach ($pertanyaan->pilihanJawaban as $pilihan) {
                        $pilihanJawaban[] = [
                            'pertanyaan_id' => $pertanyaanCopy->id,
                            'pilihan_jawaban' => $pilihan->pilihan_jawaban,
                            'urutan' => $pilihan->urutan
                        ];
                    }
                    PilihanJawaban::insert($pilihanJawaban);
                }
            }
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Data dicopy',
                'details' => $pilihanJawaban,
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            return;
            return response()->json([
                'status' => true,
                'message' => 'Data gagal dicopy',
                'details' => $th,
            ], 500);
        }
    }
}
