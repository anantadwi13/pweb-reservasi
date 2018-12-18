<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $table = "provinsi";

    public function kotakab(){
        return $this->hasMany('App\KotaKab', 'id_provinsi', 'id');
    }

    public function kecamatan(){
        return $this->hasManyThrough('App\Kecamatan','App\KotaKab','id_provinsi','id_kota','id','id');
    }
}
