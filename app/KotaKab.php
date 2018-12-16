<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KotaKab extends Model
{
    protected $table = "kota_kab";

    public function provinsi(){
        return $this->belongsTo('App\Provinsi', 'id_provinsi', 'id');
    }

    public function kecamatan(){
        return $this->hasMany('App\Kecamatan','id_kota','id');
    }
}
