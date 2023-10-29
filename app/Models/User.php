<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function userRole()
    {
        return $this->hasOne('App\Models\UserRole');
    }
    public function userRoleApp()
    {
        return $this->hasMany('App\Models\UserRoleApp');
    }

    public function userMahasiswa()
    {
        return $this->hasOne('App\Models\UserMahasiswa');
    }

    public function userPegawai()
    {
        return $this->hasOne('App\Models\UserPegawai');
    }

    public function userAplikasi()
    {
        return $this->hasMany('App\Models\AplikasiUser');
    }
    public function userAplikasiRole()
    {
        // return $this->userAplikasi()->aplikasiUserRole()->where('aplikasi_id', env('APP_LIST_ID'));
        return $this->userAplikasi()->with('aplikasiUserRole', function ($aplikasiUserRole) {
            $aplikasiUserRole->where('aplikasi_id', env('APP_LIST_ID'));
        })->whereHas('aplikasiUserRole', function ($aplikasiUserRole) {
            $aplikasiUserRole->where('aplikasi_id', env('APP_LIST_ID'));
        });
    }
    public function userAplikasiRoleAdmin()
    {
        // return $this->userAplikasi()->aplikasiUserRole()->where('aplikasi_id', env('APP_LIST_ID'));
        return $this->userAplikasi()->with('aplikasiUserRole', function ($aplikasiUserRole) {
            $aplikasiUserRole->where(['id' => session('session_role')->role_aktif->detail->id, 'aplikasi_id' => env('APP_LIST_ID')]);
        })->whereHas('aplikasiUserRole', function ($aplikasiUserRole) {
            $aplikasiUserRole->where(['id' => session('session_role')->role_aktif->detail->id, 'aplikasi_id' => env('APP_LIST_ID')]);
        });
    }
}
