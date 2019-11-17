<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public function campaigns(){
        return $this->hasMany('App\Campaign');
    }
}
