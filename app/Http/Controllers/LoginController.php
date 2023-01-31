<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PplPendaftar;
use App\Models\MasterProdi;
use App\Models\User;
use JWTAuth;

class LoginController extends Controller
{

    public function index()
    {
        return view('login');
    }

    public function index2(Request $request)
    // public function index2(Request $request)
    {
        // $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9
        // .eyJncm91cHMiOlsiVmVyaWZpa2F0b3IiLCJQZW5nZ3VuYSIsIlBlbmFuZ2d1bmcgSmF3YWIiLCJOb24gRG9zZW4iLCJNYWhhc2lzd2EiLCJGcm9udCBPZmZpY2UiLCJEb3NlbiIsIkJhY2sgT2ZmaWNlIiwiQWRtaW4gUGVtaWxtYSIsIkFkbWluaXN0cmFzaSIsIkFkbWluIl0sImdyb3VwX2FjdGl2ZSI6IlZlcmlmaWthdG9yIiwibG9naW5fdGltZSI6MTY3NTA2NDc5MCwibG9naW5fZXhwaXJlZCI6MzYwMCwiZW1haWwiOiJtYmFuZHJpZ29AaWFpbmtlbmRhcmkuYWMuaWQiLCJ0eXBlIjoibm9uIG1haGFzaXN3YSIsImlkZW50aXR5IjoiMTk5MjA2MjgyMDIwMTIxMDExIn0
        // .i6ml6SRLSVW2zK_qppEto8PWS14-eOcnHAOjE4J75Xs";
        // $token = JWTAuth::getToken();
        // $apy = JWTAuth::getPayload($token)->toArray();
        // return $apy;
        // return JWTAuth::parseToken()->authenticate();

        return $request->all();
        try {
            // attempt to verify the credentials and create a token for the user
            $token = JWTAuth::getToken();
            $apy = JWTAuth::getPayload($token)->toArray();
            return $apy;
            // if (JWTAuth::parseToken()->authenticate())
            //     return $apy;
            // else
            //     return "ggwp";
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], 500);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], 500);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent' => $e->getMessage()], 500);
        }
    }

    public function index3()
    {
        return csrf_token();
    }
    public function konfirmasi($username, $password)
    {
        return view('konfirmasi-akun', [
            'username' => $username,
            'password' => $password,
        ]);
    }

    public function authenticate(Request $request)
    {
        $user = User::where('username', $request->username)->first();
        if (empty($user)) {
            return redirect()->intended(route('confirm.user', [$request->username, $request->password]));
        } else {
            $credentials = $request->validate([
                'username' => ['required'],
                'password' => ['required'],
            ]);
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return Auth::user();
                $role  = Auth::user()->roleDefault()->role->nama_role;
                if ($role == "administrator" || $role == "admin_fakultas")
                    return redirect()->intended(route('admin.dashboard'));
                else if ($role == "mahasiswa" || $role == "dosen" || $role == "tenaga_kependidikan")
                    return redirect()->intended(route('user.dashboard'));
                else
                    return $role;
            }
            return back()->withInput()->with('fail', 'Login Gagal, pastikan username dan password sesuai');
        }
    }

    public function username()
    {
        return 'username';
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login-page');
    }
    // public function indexApi()
    // {
    //     return view('login-api');
    // }



    // public function sessionDirect(Request $request, $role)
    // {
    // return $request->all();
    // return redirect()->route('create.session');
    // return array('datanya inimi' => );
    // $check = PplPendaftar::where([
    //     'iddata' => $request->iddata,
    //     // 'is_update' => 0
    //     // ])->first();
    // ])->first();
    // return $check;
    // if ($role == "mahasiswa") {
    //     $credentials = [
    //         'email' => 'mhs@mail.com',
    //         'password' => '1234qwer'
    //     ];
    //     if (Auth::attempt($credentials)) {

    // $request->session()->regenerate();
    // $prodi = MasterProdi::with('masterFakultas')->where('prodi_kode', $request->idprodi)->first();
    // session(
    //     [
    //         'iddata' => $request->iddata,
    //         'fakultas_id' => $prodi->masterFakultas->id
    //     ]
    // );
    //             $check = PplPendaftar::where([
    //                 'iddata' => $request->iddata,
    //                 // 'is_update' => 0
    //                 // ])->first();
    //             ]);
    //             return $check;
    //             if (!empty($check)) {
    //                 if ($check->is_update == 0)

    //                     return redirect()->intended(route('mahasiswa.dashboard'));
    //             }
    //             // return $request->session()->get('role');

    //             return redirect()->intended(route('mahasiswa.dashboard'));
    //         }
    //     } else {
    //         $credentials = [
    //             'email' => 'pembimbing@mail.com',
    //             'password' => '1234qwer'
    //         ];
    //         if (Auth::attempt($credentials)) {
    //             $request->session()->regenerate();
    //             session(
    //                 [
    //                     'role' => "pembimbing",
    //                     'data' => $id
    //                 ]
    //             );
    //             return redirect()->intended(route('pembimbing.dashboard'));
    //         }
    //     }
    //     // return $request->session()->get('role');
    //     // return Auth::user()->userRole->nama_role;
    // }
    // public function createSession(Request $request)
    // {
    //     return redirect()->route('create.session');
    //     // return array('datanya inimi' => );
    //     if ($request->role == "mahasiswa") {
    //         $credentials = [
    //             'email' => 'mhs@mail.com',
    //             'password' => '1234qwer'
    //         ];
    //         if (Auth::attempt($credentials)) {
    //             $request->session()->regenerate();
    //             session(
    //                 [
    //                     'role' => "mahasiswa",
    //                     'data' => json_decode($request->data)
    //                 ]
    //             );

    //             // return redirect()->intended(route('dashboard'));
    //         }
    //     } else {
    //         session(
    //             [
    //                 'role' => "pembimbing",
    //             ]
    //         );
    //     }
    //     return $request->session()->get('data');
    //     return Auth::user()->userRole->nama_role;
    // }


}
