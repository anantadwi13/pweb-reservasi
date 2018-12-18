<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = "ruangan";

    const STATUS_AVAILABLE = 1;
    const STATUS_MAINTENANCE = 0;

    public function user(){
        return $this->belongsTo('App\User','id_user','id');
    }

    public function kategori(){
        return $this->belongsTo('App\Kategori', 'id_kategori', 'id');
    }

    public function reservasi(){
        return $this->hasMany('App\Reservasi','id_ruangan','id');
    }

    public function kecamatan(){
        return $this->belongsTo('App\Kecamatan', 'alamat_kecamatan','id');
    }
}
