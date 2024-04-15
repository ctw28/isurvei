<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveiPertanyaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survei_pertanyaans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bagian_id');
            $table->text('pertanyaan');
            $table->integer('pertanyaan_urutan');
            $table->enum('pertanyaan_jenis_jawaban', ["Text", "Text Panjang", "Pilihan", "Lebih Dari Satu Jawaban", "Select"]);
            $table->boolean('required')->default(true);
            $table->boolean('lainnya')->default(false);
            $table->timestamps();

            $table->foreign('bagian_id')->references('id')->on('survei_bagians')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('survei_pertanyaans');
    }
}
