<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveis', function (Blueprint $table) {
            $table->id();
            $table->string('survei_nama', 500);
            $table->text('survei_deskripsi');
            $table->string('survei_untuk');
            $table->boolean('is_aktif')->default(false);
            $table->boolean('harus_diisi')->default(false);
            $table->unsignedBigInteger('survei_oleh')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('survei_oleh')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surveis');
    }
}
