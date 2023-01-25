<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJawabansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survei_jawabans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sesi_id');
            $table->unsignedBigInteger('pertanyaan_id');
            $table->string('jawaban');

            $table->timestamps();
            $table->foreign('sesi_id')->references('id')->on('survei_sesis')->onDelete('cascade');
            $table->foreign('pertanyaan_id')->references('id')->on('survei_pertanyaans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('survei_jawabans');
    }
}
