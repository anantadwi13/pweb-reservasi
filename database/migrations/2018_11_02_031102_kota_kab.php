<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class KotaKab extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kota_kab', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama',50);
            $table->unsignedSmallInteger('kode_kota');
            $table->unsignedSmallInteger('id_provinsi');
            $table->timestamps();
            $table->foreign('id_provinsi')->references('id')->on('provinsi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kota_kab');
    }
}
