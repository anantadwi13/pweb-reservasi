<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Report extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_pelapor');
            $table->unsignedBigInteger('id_dilapor');
            $table->string('subject');
            $table->text('isi');
            $table->tinyInteger('status');
            $table->timestamps();
            $table->foreign('id_pelapor')->references('id')->on('users');
            $table->foreign('id_dilapor')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
