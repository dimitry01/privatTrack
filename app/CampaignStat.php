<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignStat extends Model
{
    public $timestamps = false;
    
    public function campaign(){
        $this->belongsTo('App\Campaign');
    }
}
