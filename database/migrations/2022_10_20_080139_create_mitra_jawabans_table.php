<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMitraJawabansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survei_mitra_jawabans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mitra_sesi_id');
            $table->unsignedBigInteger('pertanyaan_id');
            $table->string('jawaban');

            $table->timestamps();
            $table->foreign('mitra_sesi_id')->references('id')->on('survei_mitra_sesis');
            $table->foreign('pertanyaan_id')->references('id')->on('survei_pertanyaans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('survei_mitra_jawabans');
    }
}
