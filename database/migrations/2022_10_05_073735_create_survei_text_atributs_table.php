<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveiTextAtributsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survei_text_atributs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pertanyaan_id');
            $table->string('jenis');

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
        Schema::dropIfExists('survei_text_atributs');
    }
}
