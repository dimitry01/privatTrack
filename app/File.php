<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    public function campaigns(){
        return $this->belongsToMany('App\Campaign','groups','campaign_id','file_id');
    }

    public function visitors(){
        return $this->hasMany('App\Visitor');
    }

    public function exp_visitors(){
        return $this->hasMany('App\Visitor')->select(['email','hash_md5']);
    }
    
}
