<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveiDirectJawabansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survei_direct_jawabans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pilihan_jawaban_id')->nullable();
            $table->unsignedBigInteger('bagian_id');
            $table->timestamps();

            $table->foreign('pilihan_jawaban_id')->references('id')->on('survei_pilihan_jawabans')->onDelete('cascade');
            $table->foreign('bagian_id')->references('id')->on('survei_bagians');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('survei_direct_jawabans');
    }
}
