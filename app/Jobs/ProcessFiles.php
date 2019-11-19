<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Visitor;
use App\File;

class ProcessFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;
    protected $path;
    public $tries = 1;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path,$id)
    {
        $this->path = $path;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
        $file = File::find($this->id);
        //$path = $this->path . ".txt";
        //$handle = fopen("/home/vinnapposs/public_html/storage/files/".$path, "r");
        //file_put_contents('/home/vinnapposs/public_html/storage/files/dd.txt',$this->path);
        // this one below for panel
        $handle = fopen("/home/vinnapposs/public_html/storage/".$this->path, "r");
        //$handle = fopen("public/storage/".$this->path, "r");
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                if (strlen($buffer) > 5){
                    $buffer = rtrim($buffer);
                    if(strpos($buffer, ';') !== false){
                        $line = explode(";", $buffer);
                        if($line[0] != ""){
                            $line[0] = strtolower(rtrim($line[0]));
                            if($line[1] == "")
                                $line[1] = "unknown";
                            $visitor = new Visitor();
                            $visitor->email = $line[0];
                            $visitor->hash_md5 = md5($line[0]);
                            $visitor->full_name = $line[1];
                            $visitor->file_id = $file->id;
                            $visitor->save();
                        }
                    }else{
                        $visitor = new Visitor();
                        $visitor->email = strtolower(rtrim($buffer));
                        $visitor->hash_md5 = md5(rtrim($buffer));
                        $visitor->full_name = "unknown";
                        $visitor->file_id = $file->id;
                        $visitor->save();
                    }
                    
                }
            }
            fclose($handle);
            $count = sizeof($file->visitors);
            $file->state = 1;
            $file->emails = $count;
            $file->save();
        }
    }
}
