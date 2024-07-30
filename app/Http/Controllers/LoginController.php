<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PplPendaftar;
use App\Models\MasterProdi;
use App\Models\User;
use App\Models\AplikasiUserRole;
use JWTAuth;
use Illuminate\Support\Facades\Session;


class LoginController extends Controller
{
    public function loginAs($userAplikasiRoleId)
    {
        // return env('APP_LIST_ID');
        // session()
        if ($userAplikasiRoleId == "default") {
            $role = Auth::user()->userRole->role->nama_role;
            $session = [
                'default_role' => $role,
                'role_aktif' => (object)[
                    'role' => $role,
                    'detail' => []
                ],
            ];
            session(['session_role' => (object) $session]);
            if ($role == "administrator" || $role == "admin_fakultas" || $role == "admin") {
                return redirect()->intended(route('admin.dashboard'));
            } else if ($role == "mahasiswa" || $role == "dosen" || $role == "tenaga_kependidikan") {
                return redirect()->intended(route('user.dashboard'));
            }
        }
        $data = AplikasiUserRole::with('userLevel')->find($userAplikasiRoleId);
        $role = $data->userLevel->level_nama;
        // return $role;
        $session = [
            'default_role' => $role,
            'role_aktif' => (object)[
                'role' => $data->userLevel->level_nama,
                'detail' => (object) [
                    'id' => $data->id,
                    'role_aplikasi_nama' => $data->user_role_nama,
                ]
            ],
        ];
        session(['session_role' => (object)$session]);
        if ($role == "administrator" || $role == "admin_fakultas" || $role == "admin") {
            return redirect()->intended(route('admin.dashboard'));
        } else if ($role == "mahasiswa" || $role == "dosen" || $role == "tenaga_kependidikan") {
            return redirect()->intended(route('user.dashboard'));
        }
        // return env('APP_LIST_ID');
    }

    public function index()
    {
        return view('login');
    }

    public function index2($id)
    // public function index2(Request $request)
    {
        // return "ggwp";
        // return Auth::user();
        if (Auth::check())
            return redirect()->route('user.dashboard');

        // $tokenParts = explode(".", $token);
        // $tokenPayload = base64_decode($tokenParts[1]);
        // $jwtPayload = json_decode($tokenPayload);
        // $user = User::where('username', $jwtPayload->id)->first();
        $user = User::where('username', $id)->first();
        // return $user;
        if ($user) {
            Auth::login($user);
            // $request->session()->regenerate();
            $role = Auth::user()->userRole->role->role_nama;
            $session = [
                'default_role' => $role,
                'role_aktif' => (object)[
                    'role' => $role,
                    'detail' => []
                ],
            ];
            session(['session_role' => (object) $session]);
            // return $session('session_role')[0];
            if ($role == "admin_organisasi") {
                return redirect()->intended(route('admin.dashboard'));
            } else if ($role == "mahasiswa" || $role == "pegawai") {
                return redirect()->intended(route('user.dashboard'));
            }
            // return Auth::user();
            return redirect()->route('user.dashboard');
        } else {
            return redirect()->back();
        }
        // return $jwtPayload;
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
        // return redirect()->intended(route('confirm.user', [$request->username, $request->password]));
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
                $role = Auth::user()->userRole->role->role_nama;
                $session = [
                    'default_role' => $role,
                    'role_aktif' => (object)[
                        'role' => $role,
                        'detail' => []
                    ],
                ];
                session(['session_role' => (object) $session]);
                // return $session('session_role')[0];
                if ($role == "admin_organisasi") {
                    return redirect()->intended(route('admin.dashboard'));
                } else if ($role == "mahasiswa" || $role == "pegawai") {
                    return redirect()->intended(route('user.dashboard'));
                }
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
        Session::flush();
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
