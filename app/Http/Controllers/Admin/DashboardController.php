<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Survei;
use App\Models\SurveiBagian;
use App\Models\SurveiSesi;
use App\Models\Jawaban;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $title = 'Dashboard';
        return view('admin.dashboard', compact('title'));
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
        $title = "Partisipan Survei";
        $data = Survei::all();
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
}
