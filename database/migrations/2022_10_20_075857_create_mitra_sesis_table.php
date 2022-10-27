<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMitraSesisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survei_mitra_sesis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mitra_id');
            $table->unsignedBigInteger('survei_id');
            $table->date('sesi_tanggal');
            $table->enum('sesi_status', ['0', '1'])->default('0');

            $table->timestamps();
            $table->foreign('mitra_id')->references('id')->on('survei_mitras')->onDelete('cascade');
            $table->foreign('survei_id')->references('id')->on('surveis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('survei_mitra_sesis');
    }
}
