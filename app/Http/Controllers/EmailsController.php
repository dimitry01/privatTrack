<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Jobs\ProcessFiles;
use App\Jobs\ProcessAudience;
use Carbon\Carbon;
use App\Visitor;
use App\User;
use App\File;
use App\Action;
use Date;

class EmailsController extends Controller
{
    function __construct(){
       // $this->middleware('auth');
    }

    function uploadFile(Request $request){
        if($request->file){
            
            $name = pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_FILENAME) . '_' . str_replace(["-"," ",":"], "_", Carbon::now()->toDateTimeString());
            $path = Storage::putFileAs('/files', $request->file('file'), "$name.txt");
            $file = new File();
            $file->name = $name;
            $file->state = 0;
            $file->emails = 0;
            $file->description = $request->description;
            $file->save();
            ProcessFiles::dispatch($path,$file->id);
            //$this->handleFile($path,$file);
            return response()->json(['file' => $file]);
        }
    }

    function filesInt(){
        return view('file');
    }

    function getFiles(){
        $files = File::all();
        return response()->json(['files' => $files]);
    }

    function deleteFile($id){
        $file = File::find($id);
        $file->visitors()->delete();
        $file->campaigns()->detach();
        $file->delete();
    }

    function exportVisitors($id){
        if ($id == -1)
        {
            $files = File::all();
            return response()->json(['files' => $files]);
        }
        else
        {
            $file = File::find($id);
            $visitors = $file->exp_visitors;
            return ['type' => 'visitors', 'result' => $visitors];
        }
    }

    function getFileEmails($id){
        $file = File::find($id);
        $visitors = Visitor::where('file_id', '=', $id)->get()->count();
        if($visitors == $file->emails){
            $file->state = 1;
            $file->save();
        }else{
            $file->emails = $visitors;
            $file->save();
        }
        
        return ['file' => $id, 'state' => $file->state,'emails' => $visitors];
    }
}
