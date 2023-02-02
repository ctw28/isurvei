<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SurveiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BagianController;
use App\Http\Controllers\Admin\PertanyaanController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\MaintenanceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::group(['middleware' => 'auth'], function () {

// Route::get('/sesi', [DashboardController::class, 'setsesi'])->middleware('guest');

Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance')->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
// Route::group(['middleware' => 'role.pegawai'], function () {

// Route::post('/', [LoginController::class, 'index2'])->middleware('guest');
Route::get('/csrf', [LoginController::class, 'index3'])->middleware('guest');
Route::get('/', [LoginController::class, 'index'])->name('login-page')->middleware('guest');
Route::get('/{token}', [LoginController::class, 'index2']);

Route::get('/konfirmasi-akun/{username}/{password}', [LoginController::class, 'konfirmasi'])->name('confirm.user')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login');
// Route::get('/bagian/{id}/get-pertanyaan', [ApiController::class, 'getPertanyaan'])->name('get.pertanyaan.bagian');

Route::get('/survei/mitra/', [UserController::class, 'mitra'])->name('mitra.index')->middleware('guest');
Route::get('/mitra/survei/{id}', [UserController::class, 'mitraRegistrasi'])->name('mitra.registrasi')->middleware('guest');
Route::post('/survei/mitra/simpan', [UserController::class, 'mitraStore'])->name('mitra.store')->middleware('guest');

Route::get('mitra/bagian/{bagianId}', [UserController::class, 'mitraShowPertanyaan'])->name('mitra.show.pertanyaan');
Route::post('mitra/survei/{surveiId}/bagian/simpan-jawaban/{bagianId}', [UserController::class, 'mitraStoreJawaban'])->name('mitra.store.jawaban');

Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => 'admin', 'middleware' => 'role.admin'], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/survei', [SurveiController::class, 'index'])->name('admin.survei.data');
        // Route::get('/survei/{id}/kelola', [SurveiController::class, 'bagian'])->name('admin.survei.bagian');
        Route::get('/survei/tambah', [SurveiController::class, 'add'])->name('admin.survei.add');
        Route::post('/survei/simpan', [SurveiController::class, 'store'])->name('admin.survei.store');
        Route::get('/survei/{id}/edit', [SurveiController::class, 'edit'])->name('admin.survei.edit');
        Route::post('/survei/{id}/update', [SurveiController::class, 'update'])->name('admin.survei.update');
        Route::get('/survei/{id}/hapus', [SurveiController::class, 'delete'])->name('admin.survei.delete');

        //BAGIAN
        Route::get('/survei/{id}/bagian', [BagianController::class, 'index'])->name('admin.bagian.data');
        Route::get('/survei/{id}/bagian/tambah', [BagianController::class, 'add'])->name('admin.bagian.add');
        Route::post('/survei/{id}/bagian/simpan', [BagianController::class, 'store'])->name('admin.bagian.store');
        Route::get('/survei/{id}/bagian/{bagianId}/edit', [BagianController::class, 'edit'])->name('admin.bagian.edit');
        Route::post('/survei/{id}/bagian/{bagianId}/update', [BagianController::class, 'update'])->name('admin.bagian.update');
        Route::get('/survei/{id}/bagian/{bagianId}/delete', [BagianController::class, 'delete'])->name('admin.bagian.delete');

        //BAGIAN AWAL AKHIR
        Route::get('/survei/{surveiId}/bagian/set-awal-akhir', [BagianController::class, 'awalAkhir'])->name('admin.bagian.awal.akhir');
        //BAGIAN DIRECT
        Route::get('/survei/{surveiId}/bagian/atur-direct', [BagianController::class, 'direct'])->name('admin.bagian.direct');

        //PERTANYAAN
        Route::get('/survei/{id}/bagian/{bagianId}/pertanyaan', [PertanyaanController::class, 'index'])->name('admin.pertanyaan.data');
        Route::get('/survei/{id}/bagian/{bagianId}/pertanyaan/tambah', [PertanyaanController::class, 'add'])->name('admin.pertanyaan.add');
        Route::post('/survei/{id}/bagian/{bagianId}/pertanyaan/simpan', [PertanyaanController::class, 'store'])->name('admin.pertanyaan.store');
        Route::get('bagian/{bagianId}/pertanyaan/{pertanyaanId}/edit', [PertanyaanController::class, 'edit'])->name('admin.pertanyaan.edit');
        Route::post('/survei/{id}/bagian/{bagianId}/pertanyaan/{pertanyaanId}/update', [PertanyaanController::class, 'update'])->name('admin.pertanyaan.update');
        Route::get('/survei/{id}/bagian/{bagianId}/pertanyaan/{pertanyaanId}/hapus', [PertanyaanController::class, 'delete'])->name('admin.pertanyaan.delete');
        Route::get('/survei/{id}/bagian/{bagianId}/pertanyaan/{pertanyaanId}/direct', [PertanyaanController::class, 'directJawaban'])->name('admin.set.jawaban.redirect');

        //HASIL_BAGIAN

        Route::get('/survei/partisipan', [DashboardController::class, 'participant'])->name('admin.survei.participants');
        Route::get('/survei/{id}/bagian/hasil', [DashboardController::class, 'hasilBagian'])->name('admin.bagian.hasil');
    });

    //mahasiswa.dashboard
    Route::group(['prefix' => 'user', 'middleware' => 'role.user'], function () {
        Route::get('/dashboard', [UserController::class, 'index'])->name('user.dashboard');
        Route::get('/riwayat-survei', [UserController::class, 'riwayat'])->name('user.survei.riwayat');
        // Route::get('/dashboard', [UserController::class, 'index'])->name('mahasiswa.survei.list');
        Route::get('survei/{id}/bagian/{bagianId}', [UserController::class, 'showPertanyaan'])->name('user.show.pertanyaan');
        Route::post('/survei/{surveiId}/bagian/simpan-jawaban/{bagianId}', [UserController::class, 'storeJawaban'])->name('user.store.jawaban');
    });
});
// });
