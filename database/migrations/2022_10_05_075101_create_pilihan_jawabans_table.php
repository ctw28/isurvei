<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePilihanJawabansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survei_pilihan_jawabans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pertanyaan_id');
            $table->string('pilihan_jawaban');
            $table->integer('urutan');
            $table->timestamps();

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
        Schema::dropIfExists('survei_pilihan_jawabans');
    }
}
