<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GantiNullableKolomTabelRuangan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ruangan', function (Blueprint $table) {
            $table->string('kode')->nullable()->change();
            $table->string('alamat_jalan')->nullable()->change();
            $table->unsignedInteger('alamat_kecamatan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ruangan', function (Blueprint $table) {
            $table->string('kode')->nullable(false)->change();
            $table->string('alamat_jalan')->nullable(false)->change();
            $table->unsignedInteger('alamat_kecamatan')->nullable(false)->change();
        });
    }
}
