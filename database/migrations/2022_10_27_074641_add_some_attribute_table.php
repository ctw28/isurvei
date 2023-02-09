<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeAttributeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('survei_jawabans', function (Blueprint $table) {
        //     $table->unsignedBigInteger('sesi_id')->default(1);
        //     $table->foreign('sesi_id')->references('id')->on('survei_sesis');
        // });
        Schema::table('surveis', function (Blueprint $table) {
            // $table->boolean('harus_diisi')->default(false);
            $table->boolean('survei_status')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('survei_jawabans', function (Blueprint $table) {
            //
        });
    }
}
