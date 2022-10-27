<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBagianAwalAkhirsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survei_bagian_awal_akhirs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survei_id')->nullable();
            $table->unsignedBigInteger('bagian_id_first')->nullable();
            $table->unsignedBigInteger('bagian_id_last')->nullable();
            $table->timestamps();

            $table->foreign('survei_id')->references('id')->on('surveis')->onDelete('cascade');
            $table->foreign('bagian_id_first')->references('id')->on('survei_bagians')->onDelete('cascade');
            $table->foreign('bagian_id_last')->references('id')->on('survei_bagians')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('survei_bagian_awal_akhirs');
    }
}
