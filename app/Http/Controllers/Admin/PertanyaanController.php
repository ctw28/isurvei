<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Survei;
use App\Models\SurveiBagian;
use App\Http\Requests\PertanyaanRequest;
use App\Models\SurveiPertanyaan;
use App\Models\SurveiTextAtribut;
use App\Models\PilihanJawaban;

class PertanyaanController extends Controller
{
    //
    public function index($surveiId, $bagianId)
    {
        $title = "Pertanyaan";
        $data = Survei::find($surveiId);
        $bagian = SurveiBagian::with(['pertanyaan' => function ($pertanyaan) {
            $pertanyaan->orderBy('pertanyaan_urutan', 'ASC');
        }])->find($bagianId);
        return view('admin.survei-bagian-pertanyaan', compact('title', 'data', 'bagian'));
    }

    public function add($surveiId, $bagianId)
    {
        $title = "Tambah Pertanyaan";
        $data = Survei::find($surveiId);
        $bagian = SurveiBagian::find($bagianId);
        return view('admin.pertanyaan-tambah', compact('title', 'data', 'bagian'));
    }

    public function store(Request $request, $surveiId, $id)
    {
        $jenisJawaban = $request->pertanyaan_jenis_jawaban;

        $dataPertanyaan = [
            "bagian_id" => $request->bagian_id,
            "pertanyaan" => $request->pertanyaan,
            "pertanyaan_urutan" => $request->pertanyaan_urutan,
            "pertanyaan_jenis_jawaban" => $request->pertanyaan_jenis_jawaban
        ];
        if (isset($request->addLainnya))
            $dataPertanyaan['lainnya'] = "1";
        if (!isset($request->isRequired))
            $dataPertanyaan['required'] = "0";
        // return $dataPertanyaan;
        $dataJawaban = [];
        $pertanyaan = SurveiPertanyaan::create($dataPertanyaan);
        // return $pertanyaan;
        if ($jenisJawaban == "Text") {
            $dataTextProperties = [
                "pertanyaan_id" => $pertanyaan->id,
                "jenis" => $request->text_jenis
            ];
            $pertanyaan = SurveiTextAtribut::create($dataTextProperties);
        } else if ($jenisJawaban != "Text" && $jenisJawaban != "Text Panjang") {
            $i = 1;
            foreach ($request->jawaban as $index => $jawaban) {
                $data =
                    [
                        'pertanyaan_id' => $pertanyaan->id,
                        'pilihan_jawaban' => $jawaban,
                        'urutan' => $index,

                    ];
                $dataJawaban[] = $data;
                $i++;
            }
            if (isset($request->addLainnya)) {
                $lainnya =
                    [
                        'pertanyaan_id' => $pertanyaan->id,
                        'pilihan_jawaban' => "lainnya",
                        'urutan' => $i,

                    ];
                $dataJawaban[] = $lainnya;
            }
            // return $dataJawaban;
            PilihanJawaban::insert($dataJawaban);
        }

        return redirect()->route('admin.pertanyaan.data', [$surveiId, $id]);
    }

    public function edit($bagianId, $pertanyaanId)
    {
        $data['title'] = "Edit Pertanyaan";

        $data['stepData'] = SurveiBagian::with(['pertanyaan' => function ($pertanyaan) use ($pertanyaanId) {
            $pertanyaan->with(['pilihanJawaban', 'textProperties'])->where('id', $pertanyaanId);
        }])->find($bagianId);
        // return $data;
        return view('admin.pertanyaan-edit', $data);
    }

    public function update(Request $request, $surveiId, $bagianId, $pertanyaanId)
    {
        $pertanyaan = PilihanJawaban::where('pertanyaan_id', $pertanyaanId);
        $pertanyaan->delete();

        $jenisJawaban = $request->pertanyaan_jenis_jawaban;

        $dataJawaban = [];

        $pertanyaan = SurveiPertanyaan::find($pertanyaanId);
        // $pertanyaan = Pertanyaan::where(["step_id" => $request->step_id])->first();

        $pertanyaan->pertanyaan = $request->pertanyaan;
        $pertanyaan->pertanyaan_urutan = $request->pertanyaan_urutan;
        $pertanyaan->pertanyaan_jenis_jawaban = $request->pertanyaan_jenis_jawaban;
        if (!isset($request->isRequired))
            $pertanyaan->required = "0";
        else
            $pertanyaan->required = $request->isRequired;
        if (!isset($request->addLainnya))
            $pertanyaan->lainnya = "0";
        else
            $pertanyaan->lainnya = $request->addLainnya;

        $pertanyaan->save();
        // return $pertanyaan;
        if ($jenisJawaban == "Text") {
            $textProperties = SurveiTextAtribut::where('pertanyaan_id', $pertanyaanId);
            $textProperties->delete();
            $dataTextProperties = [
                "pertanyaan_id" => $pertanyaanId,
                "jenis" => $request->text_jenis
            ];
            $pertanyaan = SurveiTextAtribut::create($dataTextProperties);
        } else if ($jenisJawaban != "Text" && $jenisJawaban != "Text Panjang") {
            $i = 1;
            foreach ($request->jawaban as $index => $jawaban) {
                $data =
                    [
                        'pertanyaan_id' => $pertanyaanId,
                        'pilihan_jawaban' => $jawaban,
                        'urutan' => $index,

                    ];
                $dataJawaban[] = $data;
                $i++;
            }
            if (isset($request->addLainnya)) {
                $lainnya =
                    [
                        'pertanyaan_id' => $pertanyaan->id,
                        'pilihan_jawaban' => "lainnya",
                        'urutan' => $i,

                    ];
                $dataJawaban[] = $lainnya;
            }
            // return $dataJawaban;
            PilihanJawaban::insert($dataJawaban);
        }

        return redirect()->route('admin.pertanyaan.data', [$surveiId, $bagianId]);
    }

    public function delete($surveiId, $bagianId, $pertanyaanId)
    {
        try {
            SurveiPertanyaan::find($pertanyaanId)->delete();
            return redirect()->route('admin.pertanyaan.data', [$surveiId, $bagianId]);
        } catch (\Throwable $e) {
            return array("status" => $e);
        }
    }

    public function directJawaban($surveiId, $bagianId, $pertanyaanId)
    {
        // return $bagianId;
        $data['title'] = "Pengaturan Pilihan Jawaban";
        $data['bagianData'] = SurveiBagian::whereHas('pertanyaan', function ($pertanyaan) use ($pertanyaanId) {
            $pertanyaan->where('id', $pertanyaanId);
        })->with('pertanyaan', function ($pertanyaan) use ($pertanyaanId) {
            $pertanyaan->with('pilihanJawaban', function ($pilihanJawaban) {
                $pilihanJawaban->with('directJawaban', function ($jawabanRedirect) {
                    $jawabanRedirect->with('bagian');
                });
            })->where('id', $pertanyaanId);
        })->find($bagianId);
        $data['bagianList'] = SurveiBagian::where('survei_id', $surveiId)->get();
        return view('admin.direct-jawaban', $data);
        return $data;
    }
}
