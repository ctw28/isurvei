<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAplikasiUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aplikasi_user_roles', function (Blueprint $table) {
            $table->id();
            $table->string('user_role_nama');
            $table->unsignedBigInteger('aplikasi_id');
            $table->unsignedBigInteger('user_level_id');
            $table->unsignedBigInteger('organisasi_id');
            $table->timestamps();

            $table->foreign('aplikasi_id')->references('id')->on('aplikasi_lists')->onDelete('cascade');
            $table->foreign('user_level_id')->references('id')->on('user_levels');
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
        Schema::dropIfExists('aplikasi_user_roles');
    }
}
