<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    public function visitor(){
        return $this->belongsTo('App\Visitor');
    }

    public function campaign(){
        return $this->belongsTo('App\Campaign');
    }
}
