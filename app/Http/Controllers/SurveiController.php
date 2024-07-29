<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SurveiRequest;
use App\Models\Survei;
use App\Models\BagianAwalAkhir;
use App\Models\Jawaban;
use App\Models\MitraJawaban;
use App\Models\MitraSesi;
use App\Models\SurveiBagian;
use App\Models\SurveiSesi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class SurveiController extends Controller
{
    public function index()
    {
        $title = "Survei";
        // $data = Survei::where('survei_oleh', Auth::user()->id)->orderBy('created_at', "DESC")->get();
        $organisasiId = Auth::user()->adminOrganisasi->organisasi_id;
        $data = Survei::with(['organisasi', 'bagianAwalAkhir'])
            ->where('organisasi_id', $organisasiId)
            ->orderBy('created_at', "DESC")->get();
        foreach ($data as $item) {
            if ($item->survei_untuk == "mitra")
                $item->decrypt_id = Crypt::encrypt($item->id);
            $item->id_encrypt = Crypt::encrypt($item->id);
            $item->bagianAwalAkhir->bagian_id_first_encrypt = Crypt::encrypt($item->bagianAwalAkhir->bagian_id_first);
        }

        // return $data;
        return view('admin.survei-data', compact('title', 'data'));
    }

    public function add()
    {
        $title = "Tambah Survei";
        return view('admin.survei-tambah', compact('title'));
    }

    public function store(SurveiRequest $request)
    {
        try {
            $data = Survei::create($request->validated());
            BagianAwalAkhir::create([
                'survei_id' => $data->id
            ]);
            return redirect()->route('admin.survei.data')->with(['status' => 'success', 'pesan' => 'Data berhasil diupdate', 'label' => 'success']);
        } catch (\Throwable $th) {
            return $th;
            return redirect()->back()->with(['status' => 'success', 'pesan' => 'Data gagal diupdate', 'label' => 'danger']);
        }
    }

    public function edit($id)
    {
        $title = "Tambah Survei";
        $data = Survei::find($id);

        return view('admin.survei-edit', compact('title', 'data'));
    }

    public function update(Request $request, $id)
    {
        $survei = Survei::find($id);
        $survei->survei_nama = $request->survei_nama;
        $survei->survei_deskripsi = $request->survei_deskripsi;
        $survei->survei_untuk = $request->survei_untuk;
        $is_wajib = false;
        if (!empty($request->is_wajib))
            $is_wajib = true;
        $survei->is_wajib = $is_wajib;
        $survei->save();

        return redirect()->route('admin.survei.data');
    }

    public function delete($id)
    {
        $bagian = Survei::find($id);
        $bagian->delete();
        return redirect()->route('admin.survei.data');
    }

    public function cetak($surveiId)
    {
        $limit = 100;
        $survei = Survei::find($surveiId);
        // return $survei;
        if ($survei->survei_untuk == "mahasiswa")
            $jawaban = SurveiSesi::with(['user.userMahasiswa.mahasiswa.dataDiri', 'user.userMahasiswa.mahasiswa.prodi'])->where('survei_id', $surveiId)->paginate($limit);
        else if ($survei->survei_untuk == "dosen" || $survei->survei_untuk == "pegawai")
            $jawaban = SurveiSesi::with(['user.userPegawai.pegawai.dataDiri'])->where('survei_id', $surveiId)->paginate($limit);
        else
            $jawaban = MitraSesi::with(['mitra'])->where('survei_id', $surveiId)->paginate($limit);
        $bagian = SurveiBagian::with(['pertanyaan', 'survei'])->where('survei_id', $surveiId)->get();
        $data = [];
        foreach ($jawaban as $sesinya) {
            foreach ($bagian as $part) {
                foreach ($part->pertanyaan as $tanya) {
                    // $jawab = Jawaban::with('sesi.user.userMahasiswa.mahasiswa.dataDiri')
                    if ($survei->survei_untuk == "mitra") {
                        $jawab = MitraJawaban::where(['pertanyaan_id' => $tanya->id, 'mitra_sesi_id' => $sesinya->id])
                            ->get();
                        if (count($jawab) == 0) {
                            $data[] = "-";
                        } else {
                            $word = "";
                            if (count($jawab) == 1) {
                                $data[] = $jawab[0]->jawaban;
                            } else {
                                foreach ($jawab as $index => $item) {
                                    $word .= $item->jawaban;
                                    if (count($jawab) != $index + 1)
                                        $word .= ", ";
                                }
                                $data[] = $word;
                            }
                        }
                    } else {

                        $jawab = Jawaban::where(['pertanyaan_id' => $tanya->id, 'sesi_id' => $sesinya->id])
                            ->get();
                        if (count($jawab) == 0) {
                            $data[] = "-";
                        } else {
                            $word = "";
                            if (count($jawab) == 1) {
                                $data[] = $jawab[0]->jawaban;
                            } else {
                                foreach ($jawab as $index => $item) {
                                    $word .= $item->jawaban;
                                    if (count($jawab) != $index + 1)
                                        $word .= ", ";
                                }
                                $data[] = $word;
                            }
                        }
                    }
                }
            }
            $sesinya->jawaban = $data;
            $data = [];
        }
        // return $jawaban;
        return view('admin.cetak', compact(['survei', 'bagian', 'jawaban']));
    }
}
