<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveiBagiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survei_bagians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survei_id');
            $table->string('bagian_nama');
            $table->string('bagian_kode');
            $table->integer('bagian_urutan');
            $table->unsignedBigInteger('bagian_parent')->nullable();
            $table->timestamps();

            $table->foreign('survei_id')->references('id')->on('surveis')->onDelete('cascade');
            $table->foreign('bagian_parent')->references('id')->on('survei_bagians')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('survei_bagians');
    }
}
