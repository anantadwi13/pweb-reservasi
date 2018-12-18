<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = "report";

    const STATUS_UNREAD = 0;
    const STATUS_READ = 1;

    public function pelapor(){
        return $this->belongsTo('App\User','id_pelapor','id');
    }

    public function dilapor(){
        return $this->belongsTo('App\User','id_dilapor','id');
    }
}
