<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Survei;
use App\Models\SurveiBagian;
use App\Models\BagianDirect;
use App\Models\BagianAwalAkhir;
use App\Http\Requests\SurveiBagianRequest;

class BagianController extends Controller
{
    //
    public function index($id)
    {
        $title = "Survei Bagian / Section";
        $data = Survei::with(['bagian' => function ($bagian) use ($id) {
            $bagian
                ->orderBy('bagian_urutan', 'ASC');
        }])
            ->find($id);
        // return $data;
        return view('admin.survei-bagian', compact('title', 'data'));
    }

    public function add($id)
    {
        $title = "Tambah Bagian/Section";
        $data = Survei::find($id);
        $bagian = SurveiBagian::where('survei_id', $id)->whereNull('bagian_parent')->get();
        return view('admin.survei-bagian-tambah', compact('title', 'data', 'bagian'));
    }

    public function store(SurveiBagianRequest $request, $id)
    {
        try {
            SurveiBagian::create($request->validated());
            return redirect()->route('admin.bagian.data', $id)->with(['status' => 'success', 'pesan' => 'Data berhasil diupdate', 'label' => 'success']);
        } catch (\Throwable $th) {
            return redirect()->back()->with(['status' => 'success', 'pesan' => 'Data gagal diupdate', 'label' => 'danger']);
        }
    }

    public function edit($id, $bagianId)
    {
        $title = "Tambah Bagian/Section";
        $data = Survei::find($id);
        $bagian = SurveiBagian::where('survei_id', $id)->whereNull('bagian_parent')->get();
        $bagianEdit = SurveiBagian::find($bagianId);
        return view('admin.survei-bagian-edit', compact('title', 'data', 'bagian', 'bagianEdit'));
    }

    public function update(Request $request, $id, $bagianId)
    {
        $bagian = SurveiBagian::find($bagianId);
        $bagian->bagian_nama = $request->bagian_nama;
        $bagian->bagian_urutan = $request->bagian_urutan;
        $bagian->bagian_parent = $request->bagian_parent;
        $bagian->bagian_kode = $request->bagian_kode;
        $bagian->save();

        return redirect()->route('admin.bagian.data', $id);
    }

    public function delete($id, $bagianId)
    {
        $bagian = SurveiBagian::find($bagianId);
        $bagian->delete();
        return redirect()->route('admin.bagian.data', $id);
    }

    public function awalAkhir($surveiId)
    {
        $title = "Pengaturan Awal Akhir";
        $data = Survei::find($surveiId);
        $firstOrLast = BagianAwalAkhir::with(['bagianFirst', 'bagianLast'])->where('survei_id', $surveiId)->get();
        $bagianList = SurveiBagian::where('survei_id', $surveiId)->get();

        // return $firstOrLast;

        return view('admin.bagian-awal-akhir', compact('title', 'data', 'firstOrLast', 'bagianList'));
    }

    public function awalAkhirUpdate(Request $request)
    {
        try {
            if ($request->jenis == "first")
                $update = ['bagian_id_first' => $request->bagian_id];
            else if ($request->jenis == "last")
                $update = ['bagian_id_last' => $request->bagian_id];
            $bagian = BagianAwalAkhir::updateOrCreate(
                ['survei_id' => $request->id],
                $update
            );
            // $bagian = BagianAwalAkhir::find($request->id);
            // $bagian->bagian_id_last = $request->bagian_id;
            // $bagian->save();
            return array("status" => "sukses");
        } catch (\Throwable $e) {
            return $e;
            return array("status" => "gagal");
        }
    }

    public function direct($surveiId)
    {
        $data = Survei::find($surveiId);

        $title = "Pengaturan Direct Bagian";
        $bagianData = SurveiBagian::with(['bagianDirect' => function ($bagianDirect) {
            $bagianDirect->with(['direct', 'directBack']);
        }])->where('survei_id', $surveiId)->get();
        $bagianList = SurveiBagian::where('survei_id', $surveiId)->get();

        return view('admin.bagian-direct', compact('title', 'data', 'bagianList', 'bagianData'));
    }

    public function directStore(Request $request)
    {
        if ($request->jenis == "selanjutnya") {
            BagianDirect::updateOrCreate(
                ['bagian_id' => $request->bagian_id],
                [
                    'bagian_id_direct' => $request->bagian_id_direct,
                    'is_direct_by_jawaban' => $request->is_direct_by_jawaban,
                ]
            );
        } else if ($request->jenis == "kembali") {
            BagianDirect::updateOrCreate(
                ['bagian_id' => $request->bagian_id],
                [
                    'bagian_id_direct_back' => $request->bagian_id_direct
                ]
            );
        }
        return array("status" => "sukses");
    }
}
