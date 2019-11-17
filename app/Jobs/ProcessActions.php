<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Jenssegers\Agent\Agent;
use App\Visitor;
use App\Campaign;
use App\Action;
use App\Lead;

class ProcessActions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $campaign;
    protected $hash;
    protected $action;
    protected $serv_data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($action, $campaign, $hash, $serv_data)
    {
        $this->action = $action;
        $this->campaign = $campaign;
        $this->hash = $hash;
        $this->serv_data = $serv_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
        if($this->action == "open"){
            $campaign = Campaign::find($this->campaign);
            if($campaign){
                $visitor = Visitor::where('hash_md5','=',$this->hash)->first();
                if($visitor){
                    $action = Action::whereRaw('visitor_id = '.$visitor->id.' and campaign_id = '.$campaign->id)->first();
                    if(!$action)
                        $action = new Action();
                    if($action->open == 0){
                        $campaignstats = $campaign->stats;
                        $campaignstats->opens = $campaignstats->opens + 1;
                        $campaignstats->save();
                        $action->open = 1;
                        $action->visitor_id = $visitor->id;
                        $action->campaign_id = $campaign->id;
                        $action->save();
                    }
                    if($visitor->country == null) {
                        $this->updateVisitor($visitor,$this->serv_data);
                    }
                }
            }
        }else if($this->action == "click"){
            $campaign = Campaign::find($this->campaign);
            if($campaign){
                $visitor = Visitor::where('hash_md5','=',$this->hash)->first();
                if($visitor){
                    $action = Action::whereRaw('visitor_id = '.$visitor->id .' and campaign_id = '.$campaign->id)->first();
                    if(!$action)
                        $action = new Action();
                    if($action->click == 0){
                        $campaignstats = $campaign->stats;
                        $campaignstats->clicks = $campaignstats->clicks + 1;
                        $campaignstats->save();
                        $action->click = 1;
                        $action->visitor_id = $visitor->id;
                        $action->campaign_id = $campaign->id;
                        $action->save();
                    }
                    $this->updateVisitor($visitor,$this->serv_data);
                }
            }
        }
    }

    public function updateVisitor($visitor, $serv_data){
        $visitor->ip = $serv_data['ip'] != null ? $serv_data['ip'] : 'none';
        $location = $this->getUserLocation($serv_data['ip']);
        $visitor->country = $location['country'];
        $visitor->isp = $location['isp'];
        $visitor->user_agent = $serv_data['user_agent'] != null ? $serv_data['user_agent'] : 'none';
        //$agent = $this->getAgentData($serv_data['user_agent']);
        $agent = $this->getAgentDevice($server['user_agent']);
       /* if($agent){
            if(!array_key_exists("error",$agent)){
                $visitor->device = $agent['device']['type'];
                $visitor->os = $agent['os']['family'];
                $visitor->browser = $agent['browser']['name'];
            }
        }*/
        if($agent){
            if(!array_key_exists("error",$agent)){
                $visitor->device = $agent['device'];
                $visitor->os = $agent['os'];
                $visitor->browser = $agent['browser'];
            }
        }
        $visitor->refer = $server['refer'] != null ? $server['refer'] : 'none';
        $visitor->save();
    }

    public function getAgentData($data_agent){
        $query = http_build_query([
            'access_key' => config('app.agent_token'),
            'ua' => $data_agent,
        ]);
        
        $ch = curl_init('http://api.userstack.com/detect?' . $query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $json = curl_exec($ch);
        curl_close($ch);
        
        $res = json_decode($json, true);
        
        return $res;

    }

    function getAgentDevice($data_agent){
        $agent = new Agent();
        $agent->setUserAgent($data_agent);

        $browser = $agent->browser();
        if ($agent->isMobile())
            $device = "mobile";
        else if ($agent->isDesktop())
            $device = "desktop";
        else if ($agent->isTablet())
            $device = "tablet";
        else
            $device = "other";
        $platform = $agent->platform();
        $data = ['os' => $platform, 'device' => $device, 'browser' => $browser];
        return $data;
    }

    function getUserLocation($ip) {

        $Visitor_IP =$ip;
    
        $API_Key = "u5q34x-6ez770-7c7d29-472766"; // Supply your API key between the quotes if you have one
        $VPN = "1"; // Change this to 1 if you wish to perform VPN Checks on your visitors
        $TLS = "0"; // Change this to 1 to enable transport security, TLS is much slower though!
        $TAG = "0"; // Change this to 1 to enable tagging of your queries (will show within your dashboard)
        
        // If you would like to tag this traffic with a specific description place it between the quotes.
        // Without a custom tag entered below the domain and page url will be automatically used instead.
        $Custom_Tag = ""; // Example: $Custom_Tag = "My Forum Signup Page";
    
        // ------------------------------
        // END OF SETTINGS
        // ------------------------------
    
        // Setup the correct querying string for the transport security selected.
        if ( $TLS == 1 ) {
          $Transport_Type_String = "https://";
        } else {
          $Transport_Type_String = "http://";
        }
        
        // By default the tag used is your querying domain and the webpage being accessed
        // However you can supply your own descriptive tag or disable tagging altogether above.
        if ( $TAG == 1 && $Custom_Tag == "" ) {
          $Post_Field = "tag=" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        } else if ( $TAG == 1 && $Custom_Tag != "" ) {
          $Post_Field = "tag=" . $Custom_Tag;
        } else {
          $Post_Field = "";
        }
        
        // Performing the API query to proxycheck.io/v2/ using cURL
        $ch = curl_init($Transport_Type_String . 'proxycheck.io/v2/' . $Visitor_IP . '?key=' . $API_Key . '&vpn=' . $VPN . '&asn=1');
        
        $curl_options = array(
          CURLOPT_CONNECTTIMEOUT => 30,
          CURLOPT_POST => 1,
          CURLOPT_POSTFIELDS => $Post_Field,
          CURLOPT_RETURNTRANSFER => true
        );
        
        curl_setopt_array($ch, $curl_options);
        $API_JSON_Result = curl_exec($ch);
        curl_close($ch);
        
        // Decode the JSON from our API
        $Decoded_JSON = json_decode($API_JSON_Result,True);
        //print_r($Decoded_JSON);
        // data 
        $country = $Decoded_JSON[$ip]['country'];
        $isp = $Decoded_JSON[$ip]['provider'];
        $proxy = $Decoded_JSON[$ip]['proxy'];
        return ['country' => $country, 'isp' => $isp];
    }
}
