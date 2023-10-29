<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganisasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organisasis', function (Blueprint $table) {
            $table->id();
            $table->string('organisasi_nama', 200);
            $table->string('organisasi_singkatan', 200);
            $table->unsignedBigInteger('organisasi_grup_id');
            $table->unsignedBigInteger('organisasi_parent_id')->nullable();
            $table->text('organisasi_keterangan')->nullable();
            $table->timestamps();

            $table->foreign('organisasi_parent_id')->references('id')->on('organisasis');
            $table->foreign('organisasi_grup_id')->references('id')->on('organisasi_grups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organisasis');
    }
}
