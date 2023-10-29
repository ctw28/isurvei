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
        Schema::table('aplikasi_user_roles', function (Blueprint $table) {
            $table->string('user_role_nama');
            // $table->unsignedBigInteger('created_by')->nullable();
            // $table->unsignedBigInteger('updated_by')->nullable();
            // $table->foreign('created_by')->references('id')->on('aplikasi_users');
            // $table->foreign('updated_by')->references('id')->on('aplikasi_users');
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
