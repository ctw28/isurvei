<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BagianController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\API\BagianController as BagianAPI;
use App\Http\Controllers\API\PertanyaanController;
use App\Http\Controllers\API\JawabanController;
use App\Http\Controllers\API\GeneralController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


//GENERAL API


Route::get('organisasi/{flag}', [GeneralController::class, 'getOrganisasi'])->name('get.organisasi');
Route::get('organisasi/{fakultasId}/prodi', [GeneralController::class, 'getProdi'])->name('get.prodi');

Route::get('/bagian/{id}/copy-pertanyaan/{idCopy}', [ApiController::class, 'copyPertanyaan'])->name('copy.pertanyaan');
Route::get('/survei/{id}/copy', [ApiController::class, 'copySurvei'])->name('survei.copy');




Route::get('survei/{id}/partisipan/{userId}/detail-jawaban', [ApiController::class, 'detailJawaban'])->name('get.detail.jawaban');
Route::get('bagian/{id}/get-pertanyaan', [ApiController::class, 'getPertanyaan'])->name('get.pertanyaan.bagian');

Route::post('/bagian/update/', [BagianController::class, 'awalAkhirUpdate'])->name('admin.bagian.update.first.Last');
Route::post('/survei/{surveiId}/bagian/simpan', [BagianController::class, 'directStore'])->name('admin.bagian.direct.store');

Route::post('user', [UserController::class, 'store'])->name('user.store');
//untuk dapatkan pertanyaan
Route::get('jumlah-jawaban', [ApiController::class, 'getCountJawaban'])->name('get.count.jawaban');
Route::get('hasil-angka', [ApiController::class, 'getAngkaResult'])->name('get.angka.result');
Route::get('filter-data', [ApiController::class, 'getfilteredData'])->name('get.filter');


Route::get('survei/{id}/partisipan', [ApiController::class, 'getParticipants'])->name('get.participant');
Route::get('survei/sesi/{surveiId}/{userId}', [GeneralController::class, 'getSesi'])->name('get.sesi');


Route::post('simpan-jawaban-redirect', [ApiController::class, 'storeJawabanRedirect'])->name('admin.store.jawaban.redirect');
Route::post('hapus-jawaban-redirect', [ApiController::class, 'deleteJawabanRedirect'])->name('admin.delete.jawaban.redirect');


Route::post('login', [ApiController::class, 'authenticate']);


Route::get('/survei/{id}/bagian', [BagianAPI::class, 'index'])->name('bagian.by.survei');
Route::get('bagian/{id}/pertanyaan', [PertanyaanController::class, 'index'])->name('pertanyaan.by.bagian');
Route::get('/survei/{id}/pertanyaan/{pertanyaanId}', [JawabanController::class, 'index'])->name('jawaban.count.by.survei.and.pertanyaan');
Route::post('/statistik/pertanyaan/{pertanyaanId}', [JawabanController::class, 'filter'])->name('jawaban.count.by.survei.and.pertanyaan.filter');


Route::post('survei/{id}/update', [ApiController::class, 'surveiUpdate'])->name('api.survei.update');

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::post('logout', [ApiController::class, 'logout']);
});
Route::post('cek-ikut-survei', [ApiController::class, 'isParticipated'])->name('is.participated');
