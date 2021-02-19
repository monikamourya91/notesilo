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

class BackgroundController extends Controller
{
    //
    public function __construct(){
        auth()->setDefaultDriver('api');
    }

    public function subscriberData(Request $request)
    {
    	
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            $error = $e->getMessage(); 
            return response()->json([
                'status' => 404,
                'msg' => $e->getMessage()
            ], 401);
        }

        $user_id = auth()->user()->id;

        $user_data = DB::table('users')
        ->join('subscriptions','subscriptions.id','=','users.subscription_id')
        ->where('users.id',$user_id)
        ->select('users.status','subscriptions.expired_on')
        ->get()->first();

        if($user_data->status == 0){
            return response()->json([
                'status'    =>  404,
                'msg'       =>'Your license is cancelled. Please contact administrator.'
            ]);
        }elseif($user_data->expired_on != null && (strtotime($user_data->expired_on) < strtotime(date('Y-m-d')))){
            return response()->json([
                'status'    =>  404,
                'msg'       =>'Your license has expired. Please contact administrator.'
            ]);
        }else{
			
			
         $subscriber_data = DB::table('users as t1')
        ->join('user_settings as t2','t2.user_id','=','t1.id')
        ->select('t1.id',
                't1.name',
                't1.email',
                't1.status',
                't1.created_at',
                't2.unique_hash',
                't2.global_autoresponder_status',
                't2.enable_googlesheet',
                't2.global_decline_message_status'
            )->where('t1.id','=',$user_id)
        ->get()->first();



           // $subscriber_data = User::find($user_id);
           // $subscriber_data->makeHidden(['updated_at']);
            
            $auto_responder_data = Autoresponder_list::all();
            $auto_responder_data->makeHidden(['updated_at']);

            $plans_data = DB::table('users')
            ->join('subscriptions','subscriptions.id','=','users.subscription_id')
            ->join('plans','plans.id','=','subscriptions.plan_id')
            ->where('users.id',$user_id)
            ->select('subscriptions.plan_id','plans.fb_groups')
            ->get()->first();
            

            

            $return['status'] = "200";
            $return['user'] = $subscriber_data;
            $return['autoResponderList'] = $auto_responder_data;
            $return['planConfig'] = $plans_data;
            $return['msg'] = "Login Success";
            return response()->json($return);
        }
    }
    
     public function subscriberDataV2(Request $request)
    {
        
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            $error = $e->getMessage(); 
            return response()->json([
                'status' => 404,
                'msg' => $e->getMessage()
            ], 401);
        }

        $user_id = auth()->user()->id;

        $user_data = DB::table('users')
        ->join('subscriptions','subscriptions.id','=','users.subscription_id')
        ->where('users.id',$user_id)
        ->select('users.status','subscriptions.expired_on')
        ->get()->first();

        if($user_data->status == 0){
            return response()->json([
                'status'    =>  404,
                'msg'       =>'Your license is cancelled. Please contact administrator.'
            ]);
        }elseif($user_data->expired_on != null && (strtotime($user_data->expired_on) < strtotime(date('Y-m-d')))){
            return response()->json([
                'status'    =>  404,
                'msg'       =>'Your license has expired. Please contact administrator.'
            ]);
        }else{
			
			
         $subscriber_data = DB::table('users as t1')
        ->join('user_settings as t2','t2.user_id','=','t1.id')
        ->select('t1.id',
                't1.name',
                't1.email',
                't1.status',
                't1.created_at',
                't2.unique_hash',
                't2.global_autoresponder_status',
                't2.enable_googlesheet',
                't2.global_decline_message_status'
            )->where('t1.id','=',$user_id)
        ->get()->first();


            $auto_responder_data = Autoresponder_list::all();
            $auto_responder_data->makeHidden(['updated_at']);

            $plans_data = DB::table('users')
            ->join('subscriptions','subscriptions.id','=','users.subscription_id')
            ->join('plans','plans.id','=','subscriptions.plan_id')
            ->where('users.id',$user_id)
            ->select('subscriptions.plan_id','plans.fb_groups')
            ->get()->first();

            $return['status'] = "200";
            $return['user'] = $subscriber_data;
            $return['autoResponderList'] = $auto_responder_data;
            $return['planConfig'] = $plans_data;
            $return['jwttoken'] = auth()->tokenById($user_id);
            $return['msg'] = "Login Success";
            return response()->json($return);
        }
    }


    public function checkUserActivation(Request $request)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            $error = $e->getMessage(); 
            return response()->json([
                'status' => 404,
                'msg' => $e->getMessage()
            ], 401);
        }
        $user_id = auth()->user()->id;
        $user_status = auth()->user()->status;

        if($user_status == 1){
            $plan_data = DB::table('users')
            ->join('subscriptions','subscriptions.id','=','users.subscription_id')
            ->join('plans','plans.id','=','subscriptions.plan_id')
            ->where('users.id',$user_id)
            ->select('users.reseller_id','plans.id as plan_id','plans.fb_groups')
            ->get()
            ->first();

            if($request->has('extVersion')){
                DB::table('user_settings')
                ->where('user_id',$user_id)
                ->update(['ext_version'=>$request->input('extVersion')]);
            }

            return response()->json([
                'status'=>200,
                'msg'=>'Active User', 
                'planConfig' => $plan_data
            ]);
        }else{
            return response()->json([
                'status'=>404,
                'msg'=>'Inactive User', 
                'planConfig' => []
            ]);
        }
    }

    public function saveAllLeads(Request $request)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            $error = $e->getMessage(); 
            return response()->json([
                'status' => 404,
                'msg' => $e->getMessage()
            ], 401);
        }

        $validator = Validator::make($request->all(), [
           'all_leads' => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'msg' => $validator->errors()->first()
            ]);
        }
        $all_leads = $request->input('all_leads');
        $user_id = auth()->user()->id;
        foreach($all_leads as $lead){
            if(isset($lead['ans_one']) && isset($lead['ans_two']) && isset($lead['ans_three']) && $lead['ans_one'] != "" && $lead['ans_two'] != "" && $lead['ans_three'] != ""){
                if(isset($lead['location'])){
                    DB::table('all_leads')->insert([
                        'user_id'       => $user_id,//$lead['user_id'],
                        'group_id'      => $lead['group_id'], 
                        'full_name'     => $lead['full_name'], 
                        'first_name'    => $lead['first_name'], 
                        'last_name'     => $lead['last_name'], 
                        'profile_url'   => $lead['profile_url'], 
                        'joined_date'   => $lead['joined_date'], 
                        'ques_one'      => $lead['ques_one'], 
                        'ans_one'       => $lead['ans_one'], 
                        'ques_two'      => $lead['ques_two'], 
                        'ans_two'       => $lead['ans_two'], 
                        'ques_three'    => $lead['ques_three'], 
                        'ans_three'     => $lead['ans_three'], 
                        'location'      => $lead['location'], 
                        'works_at'      => $lead['works_at'], 
                        'created_at'  => date('Y-m-d h:i:s')
                    ]);
                }else{
                    DB::table('all_leads')->insert([
                        'user_id'       => $user_id,//$lead['user_id'],
                        'group_id'      => $lead['group_id'], 
                        'full_name'     => $lead['full_name'], 
                        'first_name'    => $lead['first_name'], 
                        'last_name'     => $lead['last_name'], 
                        'profile_url'   => $lead['profile_url'], 
                        'joined_date'   => $lead['joined_date'], 
                        'ques_one'      => $lead['ques_one'], 
                        'ans_one'       => $lead['ans_one'], 
                        'ques_two'      => $lead['ques_two'], 
                        'ans_two'       => $lead['ans_two'], 
                        'ques_three'    => $lead['ques_three'], 
                        'ans_three'     => $lead['ans_three'], 
                        'created_at'  => date('Y-m-d h:i:s')
                    ]);
                }
            }
        } 

        /******* Add tag users to chatsilo *******/
        if(!($request->has('email') && $request->has('fb_id'))){
            return response()->json([
                'status'=>200, 
                'msg'=>'Leads saved.'
            ]);
            die();
        }
        
        $email = $request->input('email');
        $fb_id = $request->input('fb_id');
        $POSTDATA = json_encode($request->all());
            
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://chatsilo.com/secret/api/thirdpartyintegration.php/?action=updateTaggedUserFromGroupleads",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS =>array('data'=>$POSTDATA),
        ));
        
        $response = curl_exec($curl);        
        curl_close($curl);
        return response()->json([
            'status'=>200, 
            'msg'=>'Leads saved and users tagged in chatsilo'
        ]);
    }

    /*public function addSubscribers(Request $request)
    {   
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            $error = $e->getMessage(); 
            return response()->json([
                'status' => 404,
                'msg' => $e->getMessage()
            ], 401);
        }

        $validator = Validator::make($request->all(), [
           'subscribers' => 'required',
           'groupId'     => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'msg' => $validator->errors()->first()
            ]);
        }

        $user_id = auth()->user()->id;
        $group_id = $request->input('groupId');
        $subscribers = json_decode($request->input('subscribers'),true);

        $autoresponder_data = DB::table('fb_group_settings as t1')
        ->join('autoresponders as t2', function($join){
                $join->on('t1.autoresponder_id', '=', 't2.autoresponder_list_id')
                ->on('t2.group_id', '=','t1.group_id');
        })
        ->join('autoresponder_list as t3','t3.id','=','t2.autoresponder_list_id')
        ->where('t1.group_id',$group_id)
        ->where('t1.userId',$user_id)
        ->select('t2.*','t3.responder_key as type')
        ->get()->first();

        $autoresponder_data = json_decode(json_encode($autoresponder_data), true);
        //return addSubscriber($subscribers,$autoresponder_data);
        if(!empty($autoresponder_data)){
            if( addSubscriber($subscribers,$autoresponder_data) ){
                return response()->json([
                    'status' => 200,
                    'msg' => 'Emails added successfully.'
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'msg' => 'Something went wrong'
                ]);
            }
        } else {
            return response()->json([
                'status' => 404,
                'msg' => 'No autoresponder selected'
            ]);
        }
    }*/
}
