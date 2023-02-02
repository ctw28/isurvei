<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAplikasiListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aplikasi_lists', function (Blueprint $table) {
            $table->id();
            $table->string('aplikasi_nama');
            $table->string('aplikasi_singkatan');
            $table->string('aplikasi_url');
            $table->string('aplikasi_keterangan')->nullable();
            $table->boolean('is_aktif')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aplikasi_lists');
    }
}
