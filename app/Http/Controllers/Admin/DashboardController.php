<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Survei;
use App\Models\SurveiBagian;
use App\Models\MitraJawaban;
use App\Models\SurveiSesi;
use App\Models\Jawaban;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $title = 'Dashboard';
        // return $title;
        // $survei = Survei::where('survei_oleh', Auth::user()->id)->count();
        $organisasiId = Auth::user()->adminOrganisasi->organisasi_id;
        $survei = Survei::with(['organisasi'])
            ->where('organisasi_id', $organisasiId)
            ->orderBy('created_at', "DESC")->count();
        $surveiAktif = Survei::where(['organisasi_id' => Auth::user()->adminOrganisasi->organisasi_id, 'is_aktif' => 1])->count();
        $surveiSelesai = Survei::where(['organisasi_id' => Auth::user()->adminOrganisasi->organisasi_id, 'survei_status' => 1])->count();
        return view('admin.dashboard', compact('title', 'survei', 'surveiAktif', 'surveiSelesai'));
    }

    public function hasilBagian($survei_id)
    {
        $data['bagian'] = SurveiBagian::where('survei_id', $survei_id)->get();
        // return $data;
        $data['title'] = "Statistik Bagian";
        // $data['dataUser'] = User::where('user_role_id', 2)->get();

        // $data['stepData'] = Step::with('stepChild')->whereNull('step_parent')->orderBy('step_urutan', 'ASC')->get();
        // return $data;
        return view('admin.hasil-bagian', $data);
    }

    public function participant()
    {
        // $data = SurveiBagian::where('survei_id', $survei_id)->get();
        // return $data;
        $organisasiId = Auth::user()->adminOrganisasi->organisasi_id;

        $title = "Hasil Survei";
        $data = Survei::with(['organisasi'])
            ->where('organisasi_id', $organisasiId)
            ->orderBy('created_at', "DESC")->get();
        // $data['dataUser'] = User::where('user_role_id', 2)->get();

        // $data['stepData'] = Step::with('stepChild')->whereNull('step_parent')->orderBy('step_urutan', 'ASC')->get();
        // return $data;
        return view('admin.survei-participant', compact('title', 'data'));
    }

    public function setsesi()
    {
        $sesi = SurveiSesi::all();
        $sesi->map(function ($item) {
            $jawaban = Jawaban::where('user_id', $item->user_id)->update([
                'sesi_id' => $item->id
            ]);
        });
    }

    public function surveiHasil()
    {
        $title = "Partisipan Survei";
        $data = Survei::all();
        return view('admin.survei-hasil', compact('title', 'data'));
    }

    public function excel()
    {
        $data = MitraJawaban::with('pertanyaan')->get();
        return $data;
    }
}
