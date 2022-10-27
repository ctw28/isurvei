<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMitraJawabanLainnyasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survei_mitra_jawaban_lainnyas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mitra_jawaban_id');
            $table->string('jawaban');

            $table->timestamps();
            $table->foreign('mitra_jawaban_id')->references('id')->on('survei_mitra_jawabans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('survei_mitra_jawaban_lainnyas');
    }
}
