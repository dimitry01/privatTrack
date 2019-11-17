<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Campaign;
use App\CampaignStat;
use App\File;
use App\User;
use App\Visitor;
use App\Country;
use App\Action;
use App\Jobs\ProcessActions;
use App\Jobs\ProcessStats;

class CampaignsController extends Controller
{
    function __construct(){
        // $this->middleware('auth');
    }

    //------------------------------------------------
    //                      GETTERS
    //------------------------------------------------

    function getCampaigns(){
        $campaigns = Campaign::with('country')->orderBy('id', 'DESC')->get();
        return response()->json(['campaigns' => $campaigns]);
    }

    function getCountries(){
        $countries = Country::all();
        return response()->json(['countries' => $countries]);
    }

    function getVisitors($view,$id){
        if($view == "files"){
            $file = File::find($id);
            $visitors = $file->visitors;
            return response()->json(['visitors' => $visitors]);
        }else{
            $campaign = Campaign::find($id);
            $visitors = [];
            return response()->json(['visitors' => $visitors]);
        }
    }

    function exportMode($mode,$id){
        if ($id == -1)
        {
            $campaigns = Campaign::with('country')->orderBy('id', 'DESC')->get();
            return response()->json(['campaigns' => $campaigns]);
        }
        else
        {
            if ($mode == "clicks")
            {
                $clickers = \DB::table('actions')
                    ->join('visitors', 'visitors.id', '=', 'actions.visitor_id')
                    ->whereRaw('actions.campaign_id = '.$id.' and actions.click = 1')
                    ->select('visitors.email','visitors.hash_md5')
                    ->get();
                return ['type' => 'clicks', 'result' => $clickers];
            }
            else if ($mode == "opens")
            {
                $openers = \DB::table('actions')
                    ->join('visitors', 'visitors.id', '=', 'actions.visitor_id')
                    ->whereRaw('actions.campaign_id = '.$id.' and actions.open = 1')
                    ->select('visitors.email','visitors.hash_md5')
                    ->get();
                return ['type' => 'openers', 'result' => $openers];
            }
            else if ($mode == "noopens")
            {
                $ids = Campaign::find($id)->getFileIdsAttribute();
                $noopeners = Visitor::whereIn('file_id',$ids)
                    ->whereNotIn('visitors.id', 
                        function($q) use ($id){
                            $q->select('visitor_id')->from('actions')->where('campaign_id','=', $id);
                        }
                    )
                    ->select('visitors.email','visitors.hash_md5')
                    ->get();
                
                return ['type' => 'noopeners', 'result' => $noopeners];
            }
        }
    }

    function exportAudience(Request $request)
    {
        $visitors = \DB::table('actions')
            ->join('visitors', 'visitors.id', '=', 'actions.visitor_id')
            ->whereRaw('actions.campaign_id = '.$request->campaign.' and actions.'.$request->action.' = 1 and visitors.country = "'.strtolower($request->country).'"')
            ->select('visitors.email')
            ->get();
        return ['type' => 'audience', 'result' => $visitors];
    }
    
    //------------------------------------------------
    //                      SETTERS
    //------------------------------------------------

    function addCampaign(Request $request){
        $campaign = new Campaign();
        $campaign->name = $request->campaign['name'];
        $campaign->country_id = $request->campaign['country'];
        $campaign->save();
        $visitors = 0;
        foreach($request->campaign['files'] as $file){
            $fl = File::find($file['id']);
            $visitors += sizeof($fl->visitors);
            $campaign->files()->attach($file['id']);
        }
        $campaign->emails = $visitors;
        $campaign->save();

        $campaignstats = new CampaignStat();
        $campaignstats->campaign_id = $campaign->id;
        $campaignstats->save();
        
        $campaign->stats = $campaignstats;
        return response()->json(['campaign' => $campaign]);
    }

    function addCountry(Request $request){
        $country = new Country();
        $country->name = $request->country['name'];
        $country->save();
        return response()->json(['country' => $country]);
    }

    function addFile(Request $request){
        $campaign = Campaign::find($request->campaign);
        if(!$campaign->files->contains($request->file['id'])){
            $campaign->files()->attach($request->file['id']);
            $fl = File::find($request->file['id']);
            $visitors = sizeof($fl->visitors);
            $campaign->emails += $visitors;
            $campaign->save(); 
            return response()->json(['message' => 'File added successfully', 'campaign' => $campaign->id, 'file' => $request->file['id']]);
        }
        return response()->json(['message' => 'File already exist to this campaign']);
    }

    function deleteCampaign($id){
        $campaign = Campaign::find($id);
        $campaign->actions()->delete();
        $campaign->stats()->delete();
        $campaign->files()->detach();
        //for performance get actions then 
        // Action::whereIn('id', $actions->pluck('id'))->delete(); 
        $campaign->delete();
        return '1';
    }

    function deleteCountry($id){
        $country = Country::find($id);
        $country->delete();
        return '1';
    }

    //------------------------------------------------
    //                      EVENTS
    //------------------------------------------------

    function saveClick(Request $request){
        $camp = $request->campaign;
        $hash = $request->email;
        $server = $request->data;
        ProcessActions::dispatch('click',$camp,$hash,$server);
    }

    function saveOpen(Request $request){
        $camp = $request->campaign;
        $hash = $request->email;
        $server = $request->data;
        ProcessActions::dispatch('open',$camp,$hash,$server);
    }

    //------------------------------------------------
    //                      STATISTICS
    //------------------------------------------------

    function setCountries(){
        ProcessStats::dispatch('countries');
    }

    function setIsps(){
        ProcessStats::dispatch('isps');
    }

    function setOs(){
        ProcessStats::dispatch('os');
    }

    function setOpeners(){
        ProcessStats::dispatch('openers');
    }

    function setClickers(){
        ProcessStats::dispatch('clickers');
    }

    function getStats($id){
        $campaign = Campaign::find($id);
        $stats = $campaign->stats;
        $countries = json_decode($stats->countries);
        $visitors = $campaign->emails;
        $isp = json_decode($stats->isps);
        $os = json_decode($stats->os);
        $openers = json_decode($campaign->stats->openers);
        $clickers = json_decode($campaign->stats->clickers);
        $load = ['countries' => $countries, 'isp' => $isp, 'os' => $os, 'clickers' => $clickers, 'openers' => $openers, 'visitors' => $visitors];
        return $load;
    }

}
