<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBagianDirectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survei_bagian_directs', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('survei_id')->nullable();
            $table->unsignedBigInteger('bagian_id');
            $table->unsignedBigInteger('bagian_id_direct')->nullable();
            $table->unsignedBigInteger('bagian_id_direct_back')->nullable();
            $table->enum('is_direct_by_jawaban', ['0', '1'])->default('0');
            $table->enum('is_first', ['0', '1'])->default('0');
            $table->enum('is_last', ['0', '1'])->default('0');
            $table->timestamps();

            // $table->foreign('survei_id')->references('id')->on('surveis');
            $table->foreign('bagian_id')->references('id')->on('survei_bagians')->onDelete('cascade');
            $table->foreign('bagian_id_direct')->references('id')->on('survei_bagians')->onDelete('cascade');
            $table->foreign('bagian_id_direct_back')->references('id')->on('survei_bagians')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('survei_bagian_directs');
    }
}
