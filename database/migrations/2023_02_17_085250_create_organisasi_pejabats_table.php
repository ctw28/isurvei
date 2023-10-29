<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganisasiPejabatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organisasi_pejabats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organisasi_id');
            $table->unsignedBigInteger('pegawai_id');
            $table->unsignedBigInteger('jabatan_pegawai_id');
            $table->string('sk_nomor')->nullable();
            $table->string('sk_tanggal')->nullable();
            $table->string('sk_file')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->boolean('is_aktif')->default(false);
            $table->timestamps();

            $table->foreign('organisasi_id')->references('id')->on('organisasis');
            $table->foreign('pegawai_id')->references('id')->on('pegawais');
            $table->foreign('jabatan_pegawai_id')->references('id')->on('master_jabatan_pegawais');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organisasi_pejabats');
    }
}
