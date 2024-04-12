<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jawaban;
use App\Models\Mahasiswa;
use App\Models\MitraJawaban;
use App\Models\Organisasi;
use App\Models\SurveiPertanyaan;

class JawabanController extends Controller
{
    //
    public function index($surveiId, $pertanyaanId)
    {
        $pertanyaan = SurveiPertanyaan::with(['pilihanJawaban', 'jawaban'])->find(($pertanyaanId));
        // return $pertanyaan->pilihanJawaban;
        $pertanyaan->pilihanJawaban->map(function ($data) use ($pertanyaanId) {
            $total = Jawaban::where(['pertanyaan_id' => $pertanyaanId, 'jawaban' => $data->pilihan_jawaban])
                ->count();
            $data->total = $total;
        });
        // return $pertanyaan;
        return response()->json([
            'status' => true,
            'data' => $pertanyaan,
            'pesan' => 'data ditemukan',
        ]);
    }
    public function filter(Request $request, $pertanyaanId)
    {
        // return $request->all();

        if ($request->filter == "semua") {
            $pertanyaan = SurveiPertanyaan::with(['pilihanJawaban', 'jawaban'])->find(($pertanyaanId));
            // return $pertanyaan->pilihanJawaban;
            $pertanyaan->pilihanJawaban->map(function ($data) use ($pertanyaanId) {
                $total = Jawaban::where(['pertanyaan_id' => $pertanyaanId, 'jawaban' => $data->pilihan_jawaban])
                    ->count();
                $data->total = $total;
            });
            return response()->json([
                'status' => true,
                'data' => $pertanyaan,
                'pesan' => 'data ditemukan',
            ]);
        } else if ($request->filter == "mitra") {
            // return response()->json([
            //     'status' => true,
            //     'data' => [],
            //     'pesan' => 'kwkwkwlklwk',
            // ]);
            $pertanyaan = SurveiPertanyaan::with(['pilihanJawaban', 'jawabanMitra'])->find(($pertanyaanId));
            $pertanyaan->pilihanJawaban->map(function ($data) use ($pertanyaanId) {
                $total = MitraJawaban::where(['pertanyaan_id' => $pertanyaanId, 'jawaban' => $data->pilihan_jawaban])
                    ->count();
                $data->total = $total;
            });
            return response()->json([
                'status' => true,
                'data' => $pertanyaan,
                'pesan' => 'data ditemukan',
            ]);
        } else if ($request->filter == "fakultas") {
            $id = $request->id;
            $mahasiswa = Organisasi::with(['mahasiswa.prodi' => function ($prodi) use ($id) {
                $prodi->where('organisasi_parent_id', $id);
            }])
                ->whereHas('mahasiswa.prodi', function ($prodi) use ($id) {
                    $prodi->where('organisasi_parent_id', $id);
                })
                ->where('organisasi_parent_id', $id)
                ->get();
        } else {
            $id = $request->id;
            $mahasiswa = Organisasi::with(['mahasiswa.prodi' => function ($prodi) use ($id) {
                // $prodi->where('id', $id);
            }])
                ->whereHas('mahasiswa.prodi', function ($prodi) use ($id) {
                    // $prodi->where('id', $id);
                })
                ->where('id', $id)
                ->get();
        }

        $mahasiswaIDs = $mahasiswa->pluck('mahasiswa.*.id')->flatten()->toArray();

        // return $mahasiswaIDs;
        $pertanyaan = SurveiPertanyaan::with(['pilihanJawaban', 'jawaban'])->find(($pertanyaanId));
        // return $pertanyaan->pilihanJawaban;
        if ($pertanyaan->pertanyaan_jenis_jawaban == "Text" || $pertanyaan->pertanyaan_jenis_jawaban == "Text Panjang") {
            $pertanyaan = SurveiPertanyaan::with(['pilihanJawaban', 'jawaban' => function ($jawaban) use ($mahasiswaIDs, $pertanyaanId) {
                $jawaban->with(['sesi.user.userMahasiswa.mahasiswa'])
                    ->whereHas('sesi.user.userMahasiswa.mahasiswa', function ($query) use ($mahasiswaIDs) {
                        $query->whereIn('id', $mahasiswaIDs);
                    });
            }])
                ->find(($pertanyaanId));
        } else {
            $pertanyaan->pilihanJawaban->map(function ($data) use ($pertanyaanId, $mahasiswaIDs) {
                // $total = Jawaban::with(['sesi.user.userMahasiswa.mahasiswa'])
                $total = Jawaban::with(['sesi.user.userMahasiswa.mahasiswa'])
                    ->whereHas('sesi.user.userMahasiswa.mahasiswa', function ($query) use ($mahasiswaIDs) {
                        $query->whereIn('id', $mahasiswaIDs);
                    })
                    ->where(['pertanyaan_id' => $pertanyaanId, 'jawaban' => $data->pilihan_jawaban])
                    ->count();
                $data->total = $total;
            });
        }
        // return $pertanyaan;
        return response()->json([
            'status' => true,
            'data' => $pertanyaan,
            'pesan' => 'data ditemukan',
        ]);
    }
}
