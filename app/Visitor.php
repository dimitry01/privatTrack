<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{

    public function file(){
        return $this->belongsTo('App\File');
    }

    public function action(){
        return $this->hasMany('App\Action');
    }
}
