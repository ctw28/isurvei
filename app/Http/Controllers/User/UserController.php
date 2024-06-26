<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SurveiBagian;
use App\Models\Jawaban;
use App\Models\User;
use App\Models\BagianDirect;
use Illuminate\Support\Facades\DB;
use App\Models\BagianAwalAkhir;
use App\Models\JawabanLainnya;
use App\Models\SurveiSesi;
use App\Models\MasterProdi;
use App\Models\DataDiri;
use App\Models\Mahasiswa;
use App\Models\UserMahasiswa;
use App\Models\Pegawai;
use App\Models\PegawaiDosen;
use App\Models\UserRole;
use App\Models\UserPegawai;
use App\Models\Survei;
use App\Models\Mitra;
use App\Models\MitraSesi;
use App\Models\MitraJawaban;
use App\Models\MitraJawabanLainnya;
use App\Models\Organisasi;
use App\Models\PilihanJawaban;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;


class UserController extends Controller
{
    public function index()
    {
        // return "ggwp";
        // return session('session_role')->role_aktif->role;
        // return session('session_role')['role_aktif']['role'];
        $data['title'] = "Dashboard";
        // $data['iddata'] = session('iddata');
        $role = 'mahasiswa';
        $pegawaiId = "";
        if (session('session_role')->role_aktif->role == "pegawai") {
            $pegawaiId = Auth::user()->userPegawai->pegawai_id;
            $role = "pegawai";
            $dosen = PegawaiDosen::where('pegawai_id', $pegawaiId)->first();
            if ($dosen !== null)
                $role = "dosen";
        }
        $data['survei'] = Survei::with(['sesi' => function ($sesi) {
            $sesi->where('user_id', Auth::user()->id);
        }, 'bagianAwalAkhir'])->where([
            'survei_untuk' => $role,
            'is_aktif' => 1,
        ])->orderBy('is_wajib', 'DESC')->get();
        // $data['first'] = BagianAwalAkhir::where('survei_id', 4)->first();
        $data['user_id'] = Auth::user()->id;
        $data['role'] = $role;
        $data['pegawai_id'] = $pegawaiId;
        // return $data;
        return view('user.dashboard', $data);
    }

    public function changePegawai($pegawaiId)
    {
        $dosen = PegawaiDosen::create([
            'pegawai_id' => $pegawaiId,
            'nidn' => '-',
            'dosen_status' => 'tetap',
        ]);
        // return redirect()->route('user.dashboard');
        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diubah',
            'details' => $dosen,
        ], 200);
    }
    public function changeDosen($pegawaiId)
    {
        $dosen = PegawaiDosen::where('pegawai_id', $pegawaiId)->first();
        // $dosen->delete();
        // return redirect()->route('user.dashboard');
        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diubah',
            'details' => $dosen,
        ], 200);
    }
    //

    public function mitra()
    {
        $data['title'] = "Dashboard";
        // $data['iddata'] = session('iddata');
        $role = 'mitra';
        $data['survei'] = Survei::with('bagianAwalAkhir')->where([
            'survei_untuk' => $role,
            'is_aktif' => 1,
        ])->get();
        $data['first'] = BagianAwalAkhir::where('survei_id', 4)->first();
        // return $data;
        return view('user.mitra-dashboard', $data);
    }

    public function mitraRegistrasi($surveiId)
    {
        $data['title'] = "Dashboard";
        // $data['data'] = Survei::find(Crypt::decrypt($surveiId));
        $data['data'] = Survei::find(Crypt::decrypt($surveiId));
        return view('user.mitra-registrasi', $data);
    }

    public function mitraStore(Request $request)
    {
        // return $request->all();
        DB::beginTransaction();

        try {
            $mitra = Mitra::create([
                'mitra_nama' => $request->mitra_nama,
                'mitra_instansi' => $request->mitra_instansi,
                'mitra_jabatan' => $request->mitra_jabatan,
            ]);

            $mitraSesi = MitraSesi::create([
                'mitra_id' => $mitra->id,
                'survei_id' => $request->survei_id,
                'sesi_tanggal' => \Carbon\Carbon::now(),
                'sesi_status' => '0',
            ]);

            session()->put('mitra_id', $mitra->id);
            session()->put('mitra_sesi', $mitraSesi->id);

            $survei = Survei::with('bagianAwalAkhir')->find($request->survei_id);
            DB::commit();
            return redirect()->route('mitra.show.pertanyaan', $survei->bagianAwalAkhir->bagian_id_first);
        } catch (\Throwable $th) {
            DB::rollback();
            return $th;
            return redirect()->back();
        }
    }

    public function mitraShowPertanyaan($bagianId)
    {
        // $check = Jawaban::with(['jawabanLainnya'])->where([
        //     'user_id' => Auth::user()->id,
        //     'pertanyaan_id' => '1',
        // ])->get();
        // return $check[0]->jawabanLainnya->jawaban;
        $data['title'] = "Survei";
        // $data['iddata'] = session('iddata');

        $data['bagianData'] = SurveiBagian::with(['pertanyaan' => function ($pertanyaan) {
            $pertanyaan->with(['pilihanJawaban', 'textProperties'])->orderBy('pertanyaan_urutan', 'ASC');;
        }, 'bagianDirect'])->where('id', $bagianId)->first();

        // return $data;
        $type = "text";
        foreach ($data['bagianData']->pertanyaan as $row) {
            $jawaban = "";
            $required = "";
            if ($row->required == 1)
                $required = "required";
            $dataJawaban = MitraJawaban::where(['mitra_sesi_id' => session('mitra_sesi'), 'pertanyaan_id' => $row->id])->get();
            $gg[] = $dataJawaban;
            if (count($dataJawaban) > 0) {
                $jawaban = $dataJawaban;
            }
            if ($row->pertanyaan_jenis_jawaban == "Text") {
                if ($row->textProperties->jenis == "text-email")
                    $type = "email";
                else if ($row->textProperties->jenis == "text-angka")
                    $type = "number";
                else if ($row->textProperties->jenis == "text-desimal")
                    $type = "number";
                else if ($row->textProperties->jenis == "text-tanggal")
                    $type = "date";
                else
                    $type = "text";
                $content = '<div class="mb-3 position-relative form-group">';
                $content .= '<label class="form-label">' . $row->pertanyaan_urutan . '. ' . $row->pertanyaan . '</label>';
                if (count($dataJawaban) > 0)
                    $content .= "<input step='any' " . $required . " type='" . $type . "' name='input[" . $row->id . "]' class='form-control' value='" . $jawaban[0]->jawaban . "'>";
                else
                    $content .= "<input step='any' " . $required . "  type='" . $type . "' name='input[" . $row->id . "]' class='form-control' value=''>";
                $content .= '</div>';
                $row->form = $content;
            } else if ($row->pertanyaan_jenis_jawaban == "Text Panjang") {
                $content = '<div class="mb-3 position-relative form-group">';
                $content .= '<label class="form-label">' . $row->pertanyaan_urutan . '. ' . $row->pertanyaan . '</label>';
                if (count($dataJawaban) > 0)
                    $content .= "<textarea " . $required . "  name='input[" . $row->id . "]' class='form-control'>" . $jawaban[0]->jawaban . "</textarea>";
                else
                    $content .= "<textarea " . $required . "  name='input[" . $row->id . "]' class='form-control'></textarea>";

                $content .= '</div>';
                $row->form = $content;
            } else if ($row->pertanyaan_jenis_jawaban == "Lebih Dari Satu Jawaban") {
                $content = '<div class="mb-3 position-relative form-group">';
                $content .= '<label class="form-label">' . $row->pertanyaan_urutan . '. ' . $row->pertanyaan . '</label>';
                foreach ($row->pilihanJawaban as $index => $item) {
                    $checked = "";
                    if (count($dataJawaban) > 0) {
                        foreach ($jawaban as $jawab) {
                            if ($jawab->jawaban == $item->pilihan_jawaban) {
                                $checked = "checked";
                                break;
                            }
                        }
                    }
                    if ($item->pilihan_jawaban != "lainnya")

                        $content .= '<div class="form-check">
                    <input class="form-check-input" type="checkbox" name="input[' . $row->id . '][]" id="input' . $index . '" value="' . $item->pilihan_jawaban . '" ' . $checked . '/>
                    <label class="form-check-label" for="input' . $index . '">' . $item->pilihan_jawaban . '</label>
                  </div>';
                }
                if ($row->lainnya == "1") {
                    if (count($dataJawaban) > 0) {
                        foreach ($dataJawaban as $jawab) {
                            $checked = ($jawab->jawaban == "lainnya") ? "checked" : '';
                        }
                    }
                    $content .= '<div class="form-check">
                    <input onclick="showTextInput(event, ' . $row->id . ')" class="form-check-input" type="checkbox" name="input[' . $row->id . '][]" id="input' . $row->id . '" value="lainnya" ' . $checked . '/>
                    <label class="form-check-label" for="input' . $row->id . '">Lainnya</label>
                  </div>';
                    $check = MitraJawaban::with(['mitraJawabanLainnya'])->where([
                        'mitra_sesi_id' => session('mitra_sesi'),
                        'pertanyaan_id' => $row->id,
                        'jawaban' => 'lainnya',
                    ])->get();
                    if (!empty($check[0]->jawabanLainnya))
                        $content .= "<input required name='lainnya[" . $row->id . "]' id='lainnya_" . $row->id . "' type='text' class='form-control' value='" . $check[0]->jawabanLainnya->jawaban . "'>";
                }
                $content .= '</div>';
                $row->form = $content;
            } else if ($row->pertanyaan_jenis_jawaban == "Select") {
                $content = '<div class="mb-3 position-relative form-group">';
                $content .= '<label class="form-label">' . $row->pertanyaan_urutan . '. ' . $row->pertanyaan . '</label>';
                $content .= '<select onchange="showTextInput(event, ' . $row->id . ')"  ' . $required . '  class="form-select" name="input[' . $row->id . ']" required>';
                $content .= '<option value="">Pilih</option>';
                foreach ($row->pilihanJawaban as $index => $item) {
                    $selected = "";
                    if ($item->pilihan_jawaban != 'lainnya') {
                        if (count($dataJawaban) > 0)
                            $selected = ($jawaban[0]->jawaban == $item->pilihan_jawaban) ? "selected" : '';
                        $content .= '<option value="' . $item->pilihan_jawaban . '" ' . $selected . '>' . $item->pilihan_jawaban . '</option>';
                    }
                }
                if ($row->lainnya == "1") {
                    $checked = '';
                    if (count($dataJawaban) > 0)

                        $checked = ($jawaban[0]->jawaban == "lainnya") ? "selected" : '';
                    $content .= '<option value="lainnya" ' . $checked . '>Lainnya</option>';

                    $check = MitraJawaban::with(['mitraJawabanLainnya'])->where([
                        'mitra_sesi_id' => session('mitra_sesi'),
                        'pertanyaan_id' => $row->id,
                        'jawaban' => 'lainnya',
                    ])->get();
                    if (!empty($check[0]->mitraJawabanLainnya))
                        $content .= "<input required name='lainnya[" . $row->id . "]' id='lainnya_" . $row->id . "' type='text' class='form-control mt-2' value='" . $check[0]->mitraJawabanLainnya->jawaban . "'>";
                }
                $content .= '</select>';
                $content .= '</div>';
                $row->form = $content;
            } else if ($row->pertanyaan_jenis_jawaban == "Pilihan") {
                $content = '<div class="mb-3 position-relative form-group">';
                $content .= '<label class="form-label">' . $row->pertanyaan_urutan . '. ' . $row->pertanyaan . '</label>';
                foreach ($row->pilihanJawaban as $index => $item) {
                    $checked = '';
                    if ($item->pilihan_jawaban != 'lainnya') {

                        if (count($dataJawaban) > 0)
                            $checked = ($jawaban[0]->jawaban == $item->pilihan_jawaban) ? "checked" : '';
                        $content .= '<div class="form-check">
                        <input onclick="removeTextInput(event, ' . $row->id . ')" ' . $required . ' class="form-check-input" type="radio" name="input[' . $row->id . ']" id="input' . $row->id . '' . $index . '" value="' . $item->pilihan_jawaban . '" ' . $checked . '/>
                        <label class="form-check-label" for="input' . $row->id . '' . $index . '">' . $item->pilihan_jawaban . '</label>
                      </div>';
                    }
                }
                if ($row->lainnya == "1") {
                    if (count($dataJawaban) > 0)
                        $checked = ($jawaban[0]->jawaban == "lainnya") ? "checked" : '';
                    $content .= '<div class="form-check">
                        <input onclick="showTextInput(event, ' . $row->id . ')" class="form-check-input" type="radio" name="input[' . $row->id . ']" id="inputlainnya' . $row->id . '" value="lainnya" ' . $checked . '/>
                        <label class="form-check-label" for="inputlainnya' . $row->id . '">Lainnya</label>
                    </div>';
                    // $check = JawabanLainnya::where('pertanyaan_id', $row->id)->get();
                    $check = MitraJawaban::with(['mitraJawabanLainnya'])->where([
                        'mitra_sesi_id' => session('mitra_sesi'),
                        'pertanyaan_id' => $row->id,
                        'jawaban' => 'lainnya',
                    ])->get();
                    if (!empty($check[0]->jawabanLainnya))
                        $content .= "<input required name='lainnya[" . $row->id . "]' id='lainnya_" . $row->id . "' type='text' class='form-control' value='" . $check[0]->jawabanLainnya->jawaban . "'>";
                }
                $content .= '</div>';

                $row->form = $content;
            }
        }
        // return $gg;
        $data['akhir'] = false;
        $data['awal'] = false;
        $awal = BagianAwalAkhir::where('bagian_id_first', $bagianId)->count();
        if ($awal > 0)
            $data['awal'] = true;
        $akhir = BagianAwalAkhir::where('bagian_id_last', $bagianId)->count();
        if ($akhir > 0)
            $data['akhir'] = true;

        return view('user.mitra-show-pertanyaan', $data);
        return $data;
    }

    public function mitraStoreJawaban(Request $request, $surveiId, $bagianId)
    {
        // return $request->all();
        try {
            if ($request->awal == 1) {
                $surveiSesi = MitraSesi::where(['survei_id' => $surveiId, 'mitra_id' => session('mitra_id'), 'sesi_status' => "1"])->count();
                if ($surveiSesi == 0) {
                    MitraSesi::updateOrCreate(
                        [
                            'mitra_id' => session('mitra_id'),
                            'survei_id' => $surveiId,
                        ],
                        [
                            'sesi_tanggal' => \Carbon\Carbon::now(),
                            'sesi_status' => "0"
                        ]
                    );
                }
            } else if ($request->akhir == 1) {
                $surveiSesi = MitraSesi::where('mitra_id', session('mitra_id'))->first();
                $surveiSesi->sesi_status = "1";
                $surveiSesi->save();
            }

            foreach ($request->input as $key => $value) {
                if (gettype($value) == "array") {  //ini untuk jawaban yang pilihan lebih dari satu
                    $jawaban = MitraJawaban::where([
                        'mitra_sesi_id' => session('mitra_sesi'),
                        'pertanyaan_id' => $key
                    ])->delete(); //hapus dulu semua jawaban yang sudah ada dari pertanyaan ini supaya tidak duplikat karena mau diinsert ulang dan jawaban lainnya terhapus memang jg

                    foreach ($value as $row) { // ini diinsertmi semua pilihan2 yang sudah dipilih
                        MitraJawaban::create( // kenapa nda pakai update or create karena bisa jadi sudah nda sama pilihannya, jadi nda bisa diupdate
                            [
                                'mitra_sesi_id' => session('mitra_sesi'),
                                'pertanyaan_id' => $key,
                                'jawaban' => $row
                            ]
                        );
                    }
                } else { // ini untuk simpan jawaban selain yang bukan pilihan lebih dari satu seperti text biasa, pilihan salah satu, dll
                    $jawaban = MitraJawaban::updateOrCreate(
                        [
                            'mitra_sesi_id' => session('mitra_sesi'),
                            'pertanyaan_id' => $key
                        ],
                        [
                            'jawaban' => $value
                        ]
                    );
                    MitraJawabanLainnya::where('mitra_jawaban_id', $jawaban->id)->delete(); //ini dihapus dulu jawaban lainnya kalau memang dia punya jawaban "lainnnya" nanti diinsert baru lagi
                }
                if (isset($request->lainnya)) { // ini untuk cek apakah ada jawaban "lainnya" yang diisi
                    if (isset($request->lainnya[$key]) && !empty($request->lainnya[$key])) { // cek ada atau tidak yang khusus pertanyaan ini punya jawaban "lainnya"
                        $jawaban = MitraJawaban::where([
                            'mitra_sesi_id' => session('mitra_sesi'),
                            'pertanyaan_id' => $key,
                            'jawaban' => 'lainnya'
                        ])->first();
                        MitraJawabanLainnya::create(
                            [
                                'mitra_jawaban_id' => $jawaban->id,
                                'jawaban' => $request->lainnya[$key]
                            ]
                        );
                    }
                }
            }

            $direct = BagianDirect::where('bagian_id', $bagianId)->first();
            // return $direct;
            $akhir = BagianAwalAkhir::where('bagian_id_last', $bagianId)->count();
            if ($akhir > 0) {
                $data['title'] = "Selesai";
                // $data['iddata'] = Auth::user()->name;

                return view('user.mitra-selesai', $data);
            }
            // return $direct;
            // if ($direct->is_direct_by_jawaban == 0) { //jika tidak direct berdasarkan jawaban 
            return redirect()->route('mitra.show.pertanyaan', $direct->bagian_id_direct);
            // } else { // jika direct
            foreach ($request->input as $key => $value) {
                $pilihanJawaban = PilihanJawaban::with('jawabanRedirect')->where([
                    'pertanyaan_id' => $key,
                    'pilihan_jawaban' => $value
                ])->first();
            }
            // return $pilihanJawaban;
            return redirect()->route('mitra.show.pertanyaan', $pilihanJawaban->jawabanRedirect->bagian_id_redirect);
            // }
        } catch (\Throwable $th) {
            throw $th;
        }
    }












    public function riwayat()
    {
        $title = "Riwayat Survei";
        return view('user.riwayat', compact('title'));
    }
    public function login()
    {
        return view('user.login');
    }

    public function sesi($iddata)
    {
        // $data = json_decode($request->input('data'), true);
        // $sesi = [
        //     'iddata' => $data['iddata'],
        //     'nim' => $data['nim'],
        //     'nama' => $data['nama'],
        //     'idprodi' => $data['idprodi']
        // ];
        $user =
            [
                'user_role_id' => 2,
                'name' => $iddata,
                'email' => $iddata . "@mail.com",
                'password' => $iddata,
                'created_at' => \Carbon\Carbon::now(),
            ];
        $checkUser = User::where('name', $iddata)->first();
        if ($checkUser == null) {
            $user = DB::table('users')->insert($user);
        } else {
            if ($checkUser->created_at == null) {
                $checkUser->created_at = \Carbon\Carbon::now();
                $checkUser->save();
            }
        }
        // else {
        // }

        // session(['data_alumni', $sesi]);
        // $request->session(['data_alumni' => $sesi]);

        // Session::put('data_alumni', $sesi);

        // session(['data_alumni' => $sesi]);

        session()->put('iddata', $iddata);
        session()->put('userData', User::where('name', $iddata)->first());
        return redirect()->route('user.index');
        // return $request->session('data_alumni');
        // return $data['iddata'];
        // return $user;
    }

    public function logout()
    {
        session()->forget('iddata');

        return redirect()->route('user.login');
    }
    public function showPertanyaan($surveiId, $bagianId, $sesiId)
    {
        $data['title'] = "Survei";
        if ($sesiId == "baru") {
            $sesi = SurveiSesi::create([
                'user_id' => Auth::user()->id,
                'survei_id' => $surveiId,
                'sesi_tanggal' => \Carbon\Carbon::now(),
                'sesi_status' => "0"
            ]);
            return redirect()->route('user.show.pertanyaan', [$surveiId, $bagianId, $sesi->id]);
        } else {
            // $sesi = SurveiSesi::where(['user_id' => Auth::user()->id, 'survei_id' => $surveiId])->first();
            $sesi = SurveiSesi::find($sesiId);

            // return $sesi->id;
            if (empty($sesi))
                $sesi = SurveiSesi::create([
                    'user_id' => Auth::user()->id,
                    'survei_id' => $surveiId,
                    'sesi_tanggal' => \Carbon\Carbon::now(),
                    'sesi_status' => "0"
                ]);
        }
        $data['survei_id'] = $surveiId;
        $data['sesi_id'] = $sesi->id;
        // return $bagianId;
        $data['bagianData'] = SurveiBagian::with(['pertanyaan' => function ($pertanyaan) {
            $pertanyaan->with(['pilihanJawaban', 'textProperties'])->orderBy('pertanyaan_urutan', 'ASC');;
        }, 'bagianDirect'])->where('id', $bagianId)->first();
        // return $data;
        $type = "text";
        foreach ($data['bagianData']->pertanyaan as $row) {
            $jawaban = "";
            $required = "";
            if ($row->required == 1)
                $required = "required";
            $dataJawaban = Jawaban::where(['sesi_id' => $sesi->id, 'pertanyaan_id' => $row->id])->get();
            // return $dataJawaban;
            $gg[] = $dataJawaban;
            if (count($dataJawaban) > 0) {
                $jawaban = $dataJawaban;
            }
            if ($row->pertanyaan_jenis_jawaban == "Text") {
                if ($row->textProperties->jenis == "text-email")
                    $type = "email";
                else if ($row->textProperties->jenis == "text-angka")
                    $type = "number";
                else if ($row->textProperties->jenis == "text-desimal")
                    $type = "number";
                else if ($row->textProperties->jenis == "text-tanggal")
                    $type = "date";
                else
                    $type = "text";
                $content = '<div class="mb-3 position-relative form-group">';
                $content .= '<label class="form-label">' . $row->pertanyaan_urutan . '. ' . $row->pertanyaan . '</label>';
                if (count($dataJawaban) > 0)
                    $content .= "<input step='any' " . $required . " type='" . $type . "' name='input[" . $row->id . "]' class='form-control' value='" . $jawaban[0]->jawaban . "'>";
                else
                    $content .= "<input step='any' " . $required . "  type='" . $type . "' name='input[" . $row->id . "]' class='form-control' value=''>";
                $content .= '</div>';
                $row->form = $content;
            } else if ($row->pertanyaan_jenis_jawaban == "Text Panjang") {
                $content = '<div class="mb-3 position-relative form-group">';
                $content .= '<label class="form-label">' . $row->pertanyaan_urutan . '. ' . $row->pertanyaan . '</label>';
                if (count($dataJawaban) > 0)
                    $content .= "<textarea " . $required . "  name='input[" . $row->id . "]' class='form-control'>" . $jawaban[0]->jawaban . "</textarea>";
                else
                    $content .= "<textarea " . $required . "  name='input[" . $row->id . "]' class='form-control'></textarea>";

                $content .= '</div>';
                $row->form = $content;
            } else if ($row->pertanyaan_jenis_jawaban == "Lebih Dari Satu Jawaban") {
                $content = '<div class="mb-3 position-relative form-group">';
                $content .= '<label class="form-label">' . $row->pertanyaan_urutan . '. ' . $row->pertanyaan . '</label>';
                foreach ($row->pilihanJawaban as $index => $item) {
                    $checked = "";
                    if (count($dataJawaban) > 0) {
                        foreach ($jawaban as $jawab) {
                            if ($jawab->jawaban == $item->pilihan_jawaban) {
                                $checked = "checked";
                                break;
                            }
                        }
                    }
                    if ($item->pilihan_jawaban != "lainnya")
                        $content .= '<div class="form-check">
                    <input class="form-check-input" type="checkbox" name="input[' . $row->id . '][]" id="input' . $index . '" value="' . $item->pilihan_jawaban . '" ' . $checked . '/>
                    <label class="form-check-label" for="input' . $index . '">' . $item->pilihan_jawaban . '</label>
                  </div>';
                }
                if ($row->lainnya == "1") {
                    if (count($dataJawaban) > 0) {
                        foreach ($dataJawaban as $jawab) {
                            $checked = ($jawab->jawaban == "lainnya") ? "checked" : '';
                            break;
                        }
                    }
                    $content .= '<div class="form-check">
                        <input onclick="showTextInput(event, ' . $row->id . ')" class="form-check-input" type="checkbox" name="input[' . $row->id . '][]" id="input' . $row->id . '" value="lainnya" ' . $checked . '/>
                        <label class="form-check-label" for="input' . $row->id . '">Lainnya</label>
                      </div>';
                    $check = Jawaban::with(['jawabanLainnya'])->where([
                        'sesi_id' => $sesi->id,
                        'pertanyaan_id' => $row->id,
                        'jawaban' => 'lainnya',
                    ])->get();
                    if (!empty($check[0]->jawabanLainnya))
                        $content .= "<input required name='lainnya[" . $row->id . "]' id='lainnya_" . $row->id . "' type='text' class='form-control' value='" . $check[0]->jawabanLainnya->jawaban . "'>";
                }
                $content .= '</div>';
                $row->form = $content;
            } else if ($row->pertanyaan_jenis_jawaban == "Select") {
                $content = '<div class="mb-3 position-relative form-group">';
                $content .= '<label class="form-label">' . $row->pertanyaan_urutan . '. ' . $row->pertanyaan . '</label>';
                $content .= '<select onchange="showTextInput(event, ' . $row->id . ')"  ' . $required . '  class="form-select" name="input[' . $row->id . ']" required>';
                $content .= '<option value="">Pilih</option>';
                foreach ($row->pilihanJawaban as $index => $item) {
                    $selected = "";
                    if ($item->pilihan_jawaban != 'lainnya') {
                        if (count($dataJawaban) > 0)
                            $selected = ($jawaban[0]->jawaban == $item->pilihan_jawaban) ? "selected" : '';
                        $content .= '<option value="' . $item->pilihan_jawaban . '" ' . $selected . '>' . $item->pilihan_jawaban . '</option>';
                    }
                }
                if ($row->lainnya == "1") {
                    $checked = '';
                    if (count($dataJawaban) > 0)
                        $checked = ($jawaban[0]->jawaban == "lainnya") ? "selected" : '';
                    $content .= '<option value="lainnya" ' . $checked . '>Lainnya</option>';
                    $check = Jawaban::with(['jawabanLainnya'])->where([
                        'sesi_id' => $sesi->id,
                        'pertanyaan_id' => $row->id,
                        'jawaban' => 'lainnya',
                    ])->get();
                    if (!empty($check[0]->jawabanLainnya))
                        $content .= "<input required name='lainnya[" . $row->id . "]' id='lainnya_" . $row->id . "' type='text' class='form-control' value='" . $check[0]->jawabanLainnya->jawaban . "'>";
                }
                $content .= '</select>';
                $content .= '</div>';
                $row->form = $content;
            } else if ($row->pertanyaan_jenis_jawaban == "Pilihan") {
                $content = '<div class="mb-3 position-relative form-group">';
                $content .= '<label class="form-label">' . $row->pertanyaan_urutan . '. ' . $row->pertanyaan . '</label>';
                foreach ($row->pilihanJawaban as $index => $item) {
                    $checked = '';
                    if ($item->pilihan_jawaban != 'lainnya') {

                        if (count($dataJawaban) > 0)
                            $checked = ($jawaban[0]->jawaban == $item->pilihan_jawaban) ? "checked" : '';
                        $content .= '<div class="form-check">
                        <input onclick="removeTextInput(event, ' . $row->id . ')" ' . $required . ' class="form-check-input" type="radio" name="input[' . $row->id . ']" id="input' . $row->id . '' . $index . '" value="' . $item->pilihan_jawaban . '" ' . $checked . '/>
                        <label class="form-check-label" for="input' . $row->id . '' . $index . '">' . $item->pilihan_jawaban . '</label>
                      </div>';
                    }
                }
                if ($row->lainnya == "1") {
                    if (count($dataJawaban) > 0)
                        $checked = ($jawaban[0]->jawaban == "lainnya") ? "checked" : '';
                    $content .= '<div class="form-check">
                        <input onclick="showTextInput(event, ' . $row->id . ')" class="form-check-input" type="radio" name="input[' . $row->id . ']" id="inputlainnya' . $row->id . '" value="lainnya" ' . $checked . '/>
                        <label class="form-check-label" for="inputlainnya' . $row->id . '">Lainnya</label>
                    </div>';
                    $check = JawabanLainnya::where('pertanyaan_id', $row->id)->get();
                    $check = Jawaban::with(['jawabanLainnya'])->where([
                        'sesi_id' => $sesi->id,
                        'pertanyaan_id' => $row->id,
                        'jawaban' => 'lainnya',
                    ])->get();
                    if (!empty($check[0]->jawabanLainnya))
                        $content .= "<input required name='lainnya[" . $row->id . "]' id='lainnya_" . $row->id . "' type='text' class='form-control' value='" . $check[0]->jawabanLainnya->jawaban . "'>";
                }
                $content .= '</div>';

                $row->form = $content;
            }
        }
        // return $gg;
        $data['akhir'] = false;
        $data['awal'] = false;
        $awal = BagianAwalAkhir::where('bagian_id_first', $bagianId)->count();
        if ($awal > 0)
            $data['awal'] = true;
        $akhir = BagianAwalAkhir::where('bagian_id_last', $bagianId)->count();
        if ($akhir > 0)
            $data['akhir'] = true;

        return view('user.show-pertanyaan', $data);
        return $data;
    }

    public function storeJawaban(Request $request, $surveiId, $bagianId)
    {
        // return $request->all();
        // return Auth::user()->id;
        try {
            // if ($request->awal == 1) {
            //     $userSesi = SurveiSesi::where([
            //         'id' => $request->sesi_id,
            //         'sesi_status' => "1"
            //     ])->count();
            //     if ($userSesi == 0) {
            //         SurveiSesi::updateOrCreate(
            //             [
            //                 'id' => $request->sesi_id
            //             ],
            //             [
            //                 'sesi_tanggal' => \Carbon\Carbon::now(),
            //                 'sesi_status' => "0"
            //             ]
            //         );
            //     }
            // } else if ($request->akhir == 1) {
            //     $surveiSesi = SurveiSesi::find($request->sesi_id);
            //     $surveiSesi->sesi_status = "1";
            //     $surveiSesi->save();
            // }
            // return $surveiSesi;
            foreach ($request->input as $key => $value) {
                if (gettype($value) == "array") {  //ini untuk jawaban yang pilihan lebih dari satu
                    $jawaban = Jawaban::where([
                        'sesi_id' => $request->sesi_id,
                        'pertanyaan_id' => $key
                    ])->delete(); //hapus dulu semua jawaban yang sudah ada dari pertanyaan ini supaya tidak duplikat karena mau diinsert ulang dan jawaban lainnya terhapus memang jg

                    foreach ($value as $row) { // ini diinsertmi semua pilihan2 yang sudah dipilih
                        Jawaban::create( // kenapa nda pakai update or create karena bisa jadi sudah nda sama pilihannya, jadi nda bisa diupdate
                            [
                                'sesi_id' => $request->sesi_id,
                                'pertanyaan_id' => $key,
                                'jawaban' => $row
                            ]
                        );
                    }
                } else { // ini untuk simpan jawaban selain yang bukan pilihan lebih dari satu seperti text biasa, pilihan salah satu, dll
                    $jawaban = Jawaban::updateOrCreate(
                        [
                            'sesi_id' => $request->sesi_id,
                            'pertanyaan_id' => $key
                        ],
                        [
                            'jawaban' => $value
                        ]
                    );
                    JawabanLainnya::where('jawaban_id', $jawaban->id)->delete(); //ini dihapus dulu jawaban lainnya kalau memang dia punya jawaban "lainnnya" nanti diinsert baru lagi
                }
                if (isset($request->lainnya)) { // ini untuk cek apakah ada jawaban "lainnya" yang diisi
                    if (isset($request->lainnya[$key]) && !empty($request->lainnya[$key])) { // cek ada atau tidak yang khusus pertanyaan ini punya jawaban "lainnya"
                        $jawaban = Jawaban::where([
                            'sesi_id' => $request->sesi_id,
                            'pertanyaan_id' => $key,
                            'jawaban' => 'lainnya'
                        ])->first();
                        JawabanLainnya::create(
                            [
                                'jawaban_id' => $jawaban->id,
                                'jawaban' => $request->lainnya[$key]
                            ]
                        );
                    }
                }
            }

            $direct = BagianDirect::where('bagian_id', $bagianId)->first();
            // return $direct;
            $akhir = BagianAwalAkhir::where('bagian_id_last', $bagianId)->count();
            if ($akhir > 0) {
                $data['title'] = "Selesai";

                return view('user.selesai', $data);
            }
            // return $direct;
            if ($direct->is_direct_by_jawaban == 0) { //jika tidak direct berdasarkan jawaban 
                return redirect()->route('user.show.pertanyaan', [$surveiId, $direct->bagian_id_direct, $request->sesi_id]);
            } else { // jika direct
                foreach ($request->input as $key => $value) {
                    $pilihanJawaban = PilihanJawaban::with('directJawaban')->where([
                        'pertanyaan_id' => $key,
                        'pilihan_jawaban' => $value
                    ])->first();
                }
                // return $pilihanJawaban;
                return redirect()->route('user.show.pertanyaan', [$surveiId, $pilihanJawaban->directJawaban->bagian_id, $request->sesi_id]);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = json_decode($request->data);
            // return $data;
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

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil ditambahkan',
                'details' => $user,
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            // return;

            return response()->json([
                'status' => false,
                'message' => $th,
                'details' => [],
            ], 500);
        }
    }
}
