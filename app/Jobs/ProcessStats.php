<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Campaign;

class ProcessStats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $case;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($case)
    {
        $this->case = $case;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->case == 'countries'){
            $this->setCountries();
        }else if($this->case == 'isps'){
            $this->setIsps();
        }else if($this->case == 'os'){
            $this->setOs();
        }else if($this->case == 'openers'){
            $this->setOpeners();
        }else if($this->case == 'clickers'){
            $this->setClickers();
        }
    }

    function setCountries(){
        $campaigns = Campaign::all();
        foreach($campaigns as $campaign){
            $countries = \DB::table('actions')
                ->join('visitors', 'visitors.id', '=', 'actions.visitor_id')
                ->where('actions.campaign_id','=',$campaign->id)
                ->select(\DB::raw('CONVERT(SUM(actions.click),UNSIGNED INTEGER) as clicks, CONVERT(SUM(actions.open),UNSIGNED INTEGER) as opens, visitors.country'))
                ->groupBy('visitors.country')
                ->orderBy('opens', 'desc')
                ->take(25)->get();
            $campaign->stats->countries = json_encode($countries);
            $campaign->stats->save();
        }
    }

    function setIsps(){
        $campaigns = Campaign::all();
        foreach($campaigns as $campaign){
            $isps = \DB::table('actions')
                ->join('visitors', 'visitors.id', '=', 'actions.visitor_id')
                ->where('actions.campaign_id','=',$campaign->id)
                ->select(\DB::raw('CONVERT(SUM(actions.click),UNSIGNED INTEGER) as clicks, CONVERT(SUM(actions.open),UNSIGNED INTEGER) as opens, visitors.isp'))
                ->groupBy('visitors.isp')
                ->take(25)->get();
            $campaign->stats->isps = json_encode($isps);
            $campaign->stats->save();
        }
    }

    function setOs(){
        $campaigns = Campaign::all();
        foreach($campaigns as $campaign){
            $os = \DB::table('actions')
                ->join('visitors', 'visitors.id', '=', 'actions.visitor_id')
                ->where('actions.campaign_id','=',$campaign->id)
                ->select(\DB::raw('CONVERT(SUM(actions.click),UNSIGNED INTEGER) as clicks, CONVERT(SUM(actions.open),UNSIGNED INTEGER) as opens, visitors.os'))
                ->groupBy('visitors.os')
                ->take(25)->get();
            $campaign->stats->os = json_encode($os);
            $campaign->stats->save();
        }
    }

    function setOpeners(){
        $campaigns = Campaign::all();
        foreach($campaigns as $campaign){
            $openers = \DB::table('actions')
                ->join('visitors', 'visitors.id', '=', 'actions.visitor_id')
                ->whereRaw('actions.campaign_id = '.$campaign->id.' and actions.open = 1')
                ->select('visitors.*')
                ->get();
            $campaign->opens = sizeof($openers);
            $campaign->save();
            $campaign->stats->openers = json_encode($openers);
            $campaign->stats->save();
        }
    }

    function setClickers(){
        $campaigns = Campaign::all();
        foreach($campaigns as $campaign){
            $clickers = \DB::table('actions')
                ->join('visitors', 'visitors.id', '=', 'actions.visitor_id')
                ->whereRaw('actions.campaign_id = '.$campaign->id.' and actions.click = 1')
                ->select('visitors.*')
                ->get();
            $campaign->clicks = sizeof($clickers);
            $campaign->save();
            $campaign->stats->clickers = json_encode($clickers);
            $campaign->stats->save();
        }
    }
}
