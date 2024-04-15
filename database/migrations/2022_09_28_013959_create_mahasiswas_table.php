<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMahasiswasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('iddata', 100);
            $table->string('nim', 100);
            $table->unsignedBigInteger('data_diri_id');
            $table->unsignedBigInteger('organisasi_id');
            $table->timestamps();
            $table->foreign('data_diri_id')->references('id')->on('data_diris');
            $table->foreign('organisasi_id')->references('id')->on('organisasis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mahasiswas');
    }
}
