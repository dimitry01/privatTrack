<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{   
    protected $appends = ['file_ids'];

    public function country(){
        return $this->belongsTo('App\Country');
    }

    public function stats(){
        return $this->hasOne('App\CampaignStat');
    }

    public function files(){
        return $this->belongsToMany('App\File','groups','campaign_id','file_id');
    }

    public function actions(){
        return $this->hasMany('App\Action');
    }

    public function getFileIdsAttribute(){
        return $this->files()->pluck('file_id')->toArray();
    }
}
