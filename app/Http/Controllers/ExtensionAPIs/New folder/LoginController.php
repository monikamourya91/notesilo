<?php

namespace App\Http\Controllers\ExtensionAPIs;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\User;
use App\Language;
use App\Autoresponder_list;

class LoginController extends Controller
{
    //
    public function __construct(){
        auth()->setDefaultDriver('api');
    }

    public function login(Request $request)
    {
    	
        $validator = Validator::make($request->all(), [
           'license_key' => 'required'
      	]);
        
		if ($validator->fails()) {
			return response()->json([
				'status' => 404,
				'msg' => $validator->errors()->first()
			]);
			//return json_encode($validator->errors());
		}

    	$subscriber_exist = User::where('license',$request->input('license_key'))
    							->count();
    	if($subscriber_exist <= 0)
    	{
    		return response()->json([
				'status' => 404,
				'msg' => "Invalid key"
			]);
    	}


        $subscriber_data = DB::table('users as t1')
        ->join('user_settings as t2','t2.user_id','=','t1.id')
        ->join('subscriptions as t3','t3.id','=','t1.subscription_id')
        ->where('t1.license',$request->input('license_key'))
        ->select('t1.id','t1.name','t1.email','t1.status','t1.license as license_key','t1.created_at','t1.updated_at','t2.unique_hash','t2.global_autoresponder_status','t2.enable_googlesheet','t2.is_email_send','t2.ext_version','t3.plan_id','t3.started_on','t3.expired_on','t3.is_trial as trial','t3.payment_subscription_id as paddle_subscription_id','t3.cancellation_effective_date')
        ->get()->first();

        if($subscriber_data->status == 0){
            return response()->json([
                'status' => 404,
                'msg' => "Account is cancelled. Contact Administrator."
            ]);
            die();
        }
        elseif( $subscriber_data->expired_on != NULL && (strtotime($subscriber_data->expired_on) < strtotime(date('Y-m-d')))){
            return response()->json([
                'status' => 404,
                'msg' => "License is expired."
            ]);
            die();
        }

        $token = auth()->tokenById($subscriber_data->id);

        $auto_responder_data = Autoresponder_list::all();
        $auto_responder_data->makeHidden(['updated_at']);

    	$plans_data = DB::table('users')
    	->join('subscriptions','subscriptions.id','=','users.subscription_id')
        ->join('plans','plans.id','=','subscriptions.plan_id')
    	->where('users.id',$subscriber_data->id)
    	->select('users.reseller_id','subscriptions.plan_id','plans.fb_groups')
    	->get()->first();
        
        $return['status'] = "200";
        $return['user'] = $subscriber_data;
        $return['apiToken'] = $token;
        $return['autoResponderList'] = $auto_responder_data;
    	$return['planConfig'] = $plans_data;
    	$return['msg'] = "Login Successfully";
    	return response()->json($return);
    }

    public function logout(){
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            $error = $e->getMessage(); 
            return response()->json([
                'status' => 404,
                'msg' => $e->getMessage()
            ]);
        }
        auth()->logout();
        return response()->json([
            'status' => 200,
            'msg' => "Logged Out Successfully."
        ]);
    }


}
