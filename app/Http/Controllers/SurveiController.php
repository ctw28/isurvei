<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SurveiRequest;
use App\Models\Survei;
use App\Models\BagianAwalAkhir;
use App\Models\SurveiBagian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class SurveiController extends Controller
{
    public function index()
    {
        $title = "Survei";
        // $data = Survei::where('survei_oleh', Auth::user()->id)->orderBy('created_at', "DESC")->get();
        $organisasiId = Auth::user()->adminOrganisasi->organisasi_id;
        $data = Survei::with(['organisasi'])
            ->where('organisasi_id', $organisasiId)
            ->orderBy('created_at', "DESC")->get();
        foreach ($data as $item) {
            if ($item->survei_untuk == "mitra")
                $item->decrypt_id = Crypt::encrypt($item->id);
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
}
