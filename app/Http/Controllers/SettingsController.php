<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\User;

class SettingsController extends Controller
{
    public function getSettings(){
        $token = config('app.agent_token');
        return ['token' => $token];
    }

	public function createUser(Request $request){
        $user = new User();
        $user->name = "admin";
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
		$user->save();
		return '1';
	}
	
	public function checkUser(){
        $user = User::all();
        return [$user];
    }
    public function updateToken(Request $request){
        $this->updateDotEnv('AGENT_TOKEN', $request->token);
    }

    public function updateDotEnv($key, $newValue, $delim=''){

	    $path = base_path('.env');
	    // get old value from current env
	    $oldValue = env($key);

	    // was there any change?
	    if ($oldValue === $newValue) {
	        return;
	    }

	    // rewrite file content with changed data
	    if (file_exists($path)) {
	        // replace current value with new value 
	        file_put_contents(
	            $path, str_replace(
	                $key.'='.$delim.$oldValue.$delim, 
	                $key.'='.$delim.$newValue.$delim, 
	                file_get_contents($path)
	            )
	        );
	    }
	}
}
