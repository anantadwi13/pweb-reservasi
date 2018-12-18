<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = "kecamatan";

    public function kotakab(){
        return $this->belongsTo('App\KotaKab', 'id_kota', 'id');
    }

    public function provinsi(){
        return $this->kotakab()->first()->provinsi();
    }
}
