<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\User;
use App\Language;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendLicense;

class APIController extends Controller
{
    //

    /*public function __construct(){
        auth()->setDefaultDriver('api');
    }
    public function login(Request $request)
    {
    	$validator = Validator::make($request->all(), [
           'license_key' => 'required'
      	]);
        
		if ($validator->fails()) {
			return response()->json([
				'status' => 200,
				'error' => true,
				'msg' => $validator->errors()->first()
			]);
			//return json_encode($validator->errors());
		}
    	
    	$subscriber_data = User::where('license',$request->input('license_key'))
    							->where('status','1')->get()->first();
    	if(empty($subscriber_data))
    	{
    		return response()->json([
				'status' => 200,
				'error'  => true,
				'msg' => "Invalid License Key."
			]);
    	}

    	$languages = Language::where('status','1')->get(['id','language_type','language_key','status']);
    	$plans_data = DB::table('users')
    	->join('subscriptions','subscriptions.id','=','users.subscription_id')
    	->where('users.id',$subscriber_data->id)
    	->select('subscriptions.plan_id')
    	->get()->first();

    	$return['status'] = "200";
    	$return['error'] = false;
    	$return['user'] = $subscriber_data;
    	$return['planConfig'] = $plans_data;
    	$return['languages'] = $languages;
    	$return['msg'] = "Login Success";
    	return response()->json($return);
    }

    public function sendLicense(Request $request)
    {
    	$validator = Validator::make($request->all(), [
           'email' => 'required|email'
      	]);
        
		if ($validator->fails()) {
			return response()->json([
				'status' => 200,
				'error' => true,
				'msg' => $validator->errors()->first()
			]);
		}
    	
    	$subscriber_data = User::where('email',$request->input('email'))
    							->where('status','1')->get()->first();
    	if(empty($subscriber_data))
    	{
    		return response()->json([
				'status' => 200,
				'error'  => true,
				'msg' => "Email is not registered with us."
			]);
    	}

    	$data['license'] = $subscriber_data->license;
    	$data['subject'] = "Test subject";//$subscriber_data->license"";    	
    	//Mail::to($request->input('email'))->send(new SendLicense($data));

    	$return['status'] = "200";
    	$return['error'] = false;
    	$return['msg'] = "Email sent successfully.";
    	return response()->json($return);
    }

    /*public function generateToken(){
        //token generate
        $data['email'] = 'user1@yopmail.com';
        $data['password'] = 'test1234';
        if (! $token = auth()->attempt($data)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }
    public function checkToken(Request $request){
        try {
            $user = auth()->userOrFail();
            //return response()->json(['Token' => 'Valid','user' => $user]);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            $error = $e->getMessage(); 
            return response()->json(['error' =>$e->getMessage()], 401);
        }
        return 123;
    }
    public function deleteToken(){
        auth()->logout();
        return response()->json([
            'message' => "Logged out successfully."
        ]);
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }*/
}
