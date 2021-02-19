<?php

namespace App\Http\Controllers\AppAPIs;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AppAPIController extends Controller
{
    //
    public function __construct(){
        auth()->setDefaultDriver('api');
    }

    public function login(Request $request)
    {   
        //jwt custom expiry not set
        $validator = Validator::make($request->all(), [
           'email' => 'required|email',
           'password' => 'required'
      	],[
            'email.required' => "Email is required.",
            'email.email' => "Please enter a valid email.",
            'password.required' => "Password is required.",
        ]);
        
		if ($validator->fails()) {
			return response()->json([
				'status' => 200,
				'msg' => $validator->errors()->first()
			]);
		}
        $credentials['email'] = $request->input('email');
        $credentials['password'] = $request->input('password');
        if($token = auth()->setTTL(10080)->attempt($credentials)){
            $user_id = auth()->user()->id;

            $group_list = DB::table('linked_fb_groups')
                        ->where('userId',$user_id)
                        ->get(['id','group_name']);

            $groupConfig = array();
            foreach($group_list as $group){
                $leads_count = DB::table('all_leads')
                    ->where('user_id',$user_id)
                    ->where('group_id',$group->id)
                    ->count();

                $last_update_data = DB::table('all_leads')
                    ->where('user_id',$user_id)
                    ->where('group_id',$group->id)
                    ->orderBy('created_at','desc')
                    ->limit('1')
                    ->get(['created_at']);
                if($last_update_data->isEmpty()){
                    $last_update = null;
                }else{
                    $last_update = $last_update_data[0]->created_at;
                }
                $data['group_id'] = $group->id;
                $data['group_name'] = $group->group_name;
                $data['count'] = $leads_count;
                $data['lastUpdate'] = $last_update;
                $groupConfig[] = $data;
            }

            foreach ($groupConfig as $key => $value) {
                $groupConfig[$key]['group_name'] = mb_convert_encoding($groupConfig[$key]['group_name'], "UTF-8", "UTF-8");
            }
         
            /*$jwtData = array('userid'=>$user['id'],
            'iat'=> time(), 
            'exp' => time() + 60*60);
            $jwtToken = $jwtObject->GenerateToken($jwtData);*/
            $authData = auth()->user();
            $userData = (object) ['name' => $authData->name,'status' => $authData->status, 'email' => $authData->email];
            return response()->json([
                'status'=>200,
                'user'=>$userData,
                'token'=> $token,   
                'groupList'=> $groupConfig,  
                'message'=>'User logged in.'
            ]);
        }else{
            return response()->json([
                'status'=>404,
                'message'=>"Invalid email or password."
            ]);
        }
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'email' => 'required|email'
        ],[
            'email.required' => "Email is required.",
            'email.email' => "Please enter a valid email."
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 200,
                'msg' => $validator->errors()->first()
            ]);
        }

        $user = DB::table('users')->where('email',$request->input('email'))->get()->first();

        if(!empty($user)){
            $reset_token = generateRandomString(20);
            DB::table('passwordreset')->insert([
                'email' => $user->email,
                'token' => $reset_token,
                'created_at' => date('Y-m-d h:i:s')

            ]);
            $reset_mail_sent = $this->sentResetPasswordLink($user->name,$user->email,$reset_token);
            if($reset_mail_sent){
                return response()->json([
                    'status'=>200, 
                    'message'=>'Reset link has been sent to your email.',
                    'passwordToken' => $reset_token
                ]);          
            } else {
                return response()->json([
                    'status'=>404,
                    'message'=>"Email not found."
                ]);          
            } 
        }else{ 
            return response()->json([
                'status'=>404,
                'message'=>"Email not found."
            ]);
        }
    }

    function sentResetPasswordLink($name,$email,$token){  
        $link = "https://app.groupleads.net/resetPassword/:".$token;
        $template = "Hi ".$name.",<br/><br/>";
        $template .= " <a href='".$link."'>Click here to reset password</a><br/><br/>";
        $template .= "Thank you<br/>Group Leads Team";
        sendGridApi($email, "Reset password", $template);
        return true;
    }

    function resetPassword(Request $request)
    {   
        $validator = Validator::make($request->all(), [
           'passwordToken' => 'required',
           'password' => 'required|min:5'
        ],[
            'passwordToken.required' => "Password token  is required.",
            'password.required' => "Password is required.",
            'password.min' => "Password length must be greater than 4."
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'msg' => $validator->errors()->first()
            ]);
        }

        $valid_token = DB::table('passwordreset')
                        ->where('token',$request->input('passwordToken'))
                        ->orderBy('created_at', 'asc')
                        ->get()
                        ->first();

        if(!empty($valid_token)){
            $seconds = strtotime(date('Y-m-d h:i:s')) - strtotime($valid_token->created_at);
            $hours = $seconds / 60 / 60;
            
            if($hours < 3){
                
                DB::table('users')->where('email',$valid_token->email)->update([
                    'password' => Hash::make($request->input('password')),
                    'updated_at' => date('Y-m-d h:i:s')
                ]);

                DB::table('passwordreset')->where('id', $valid_token->id)->delete();
                
                return response()->json([
                    'status'=>200, 
                    'message'=>'Password is updated successfuly. '
                ]);
            }else{
                return response()->json([
                    'status'=>404, 
                    'message'=>'Token is expired.'
                ]);
            }

        }else{
            return response()->json([
                'status'=>404, 
                'message'=>'something went wrong.'
            ]);
        }
    }

    function getGroupLeads(Request $request){
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            $error = $e->getMessage(); 
            return response()->json([
                'status' => 404,
                'msg' => 'Api token is required.'
            ]);
        }
        
        $user_id = auth()->user()->id;

        if(isset($_GET['group_id'])){
            $groupId = 0;
            if($_GET['group_id'] == 'undefined'){
               $groupId = 0; 
            }else{
                $groupId = (int) $_GET['group_id'];            
            }
            
            //undefined check
            if(strlen($_GET['group_id']) == 9){
                $groupId = 0; 
            }
            
            if($groupId == 0){
                $allLeads = DB::table('all_leads')
                    ->where('user_id',$user_id)
                    ->orderBy('created_at', 'asc')
                    ->get();
            }else{
                $allLeads = DB::table('all_leads')
                    ->where('user_id',$user_id)
                    ->where('group_id',$groupId)
                    ->get();
            }

            return response()->json([
                'status'=>200, 
                'allLeads'=>$allLeads
            ]);
        }else{
            return response()->json([
                'status'=>404, 
                'message'=>'something went wrong.'
            ]);
        }
    }

    function getUserDetails(Request $request)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            $error = $e->getMessage(); 
            return response()->json([
                'status' => 404,
                'msg' => 'Api token is required.'
            ]);
        }

        $user_id = auth()->user()->id;

        $user_hash = DB::table('user_settings')
                    ->where('user_id',$user_id)
                    ->get(['unique_hash'])
                    ->first();

        $userData = DB::table('users as t1')
                    ->join('subscriptions as t2','t2.id','=','t1.subscription_id')
                    ->join('plans as t3','t3.id','=','t2.plan_id')
                    ->where('t1.id',$user_id)
                    ->select('t1.id as userid','t1.name','t1.email','t1.reseller_id','t2.is_trial as trial','t2.plan_id','t2.expired_on','t3.type','t3.price','t3.fb_groups','t3.name')
                    ->get()->first();
        $reseller_id = $userData->reseller_id;
        $plan_type = $userData->type;
        $fb_groups = $userData->fb_groups;
        $plan_name = $userData->name;
        unset($userData->reseller_id);
        unset($userData->type);
        unset($userData->fb_groups);
        unset($userData->name);
        
     //   $userData->expired_on = "2020-08-25";

        $active_autoresponders = DB::table('autoresponder_list')->where('status',1)->count();

        $features[] = "Easy To Use";
        if($fb_groups == null){
            $features[] = "Unlimited Facebook Groups";
        }else{
            $features[] = "Up to {$fb_groups} facebook groups";
        }
        $features[] = "Lifetime Software Updates";
        $features[] = "Grow Email List On AUTOPILOT";
        $features[] = "Facebook Group Automatic Approval";
        $features[] = "No Monthly Zapier Fees";
        $features[] = "Google Sheet Integration";
        $features[] = "Grab All Members At A Go";
        $features[] = "{$active_autoresponders} Autoresponders & CRMs";
        $features[] = "Email Support";
        $features[] = "Facebook Group Lead Generation";
        $features[] = "Auto Message Declined Group Members";
        $features[] = "Send Group Members’ Data To Three Places";

        $userData->features = $features;
        return response()->json([
            'status'=>200, 
            'hash' => $user_hash->unique_hash,
            'reseller_id' => $reseller_id,
            'plan_name' => $plan_name,
            'trial_days' => 7,
            'type' => $plan_type,
            'userDetails'=>$userData,
        ]);
    }

    function deleteLead(Request $request){
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            $error = $e->getMessage(); 
            return response()->json([
                'status' => 404,
                'msg' => 'Api token is required.'
            ]);
        }

        $validator = Validator::make($request->all(), [
           'id' => 'required'
        ],[
            'id.required' => "Invalid lead id."
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'msg' => $validator->errors()->first()
            ]);
        }

        $id = $request->input('id');
        $user_id = auth()->user()->id;

        DB::table('all_leads')
        ->where('id', $id)
        ->where('user_id', $user_id)
        ->delete();
        
        return response()->json([
            'status'=>200, 
            'message'=>'Lead deleted successfully.'
        ]);      
    }

    function getGroupList(Request $request)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            $error = $e->getMessage(); 
            return response()->json([
                'status' => 404,
                'msg' => 'Api token is required.'
            ]);
        }
        $user_id = auth()->user()->id;
        $group_list = DB::table('linked_fb_groups')
                        ->where('userId',$user_id)
                        ->get(['id','group_name']);

        $groupConfig = array();
        foreach($group_list as $group){
            $leads_count = DB::table('all_leads')
                ->where('user_id',$user_id)
                ->where('group_id',$group->id)
                ->count();

            $last_update_data = DB::table('all_leads')
                ->where('user_id',$user_id)
                ->where('group_id',$group->id)
                ->orderBy('created_at','desc')
                ->limit('1')
                ->get(['created_at']);
            if($last_update_data->isEmpty()){
                $last_update = null;
            }else{
                $last_update = $last_update_data[0]->created_at;
            }
            $data['group_id'] = $group->id;
            $data['group_name'] = $group->group_name;
            $data['count'] = $leads_count;
            $data['lastUpdate'] = $last_update;
            $groupConfig[] = $data;
        }
        
        foreach ($groupConfig as $key => $value) {
            $groupConfig[$key]['group_name'] = mb_convert_encoding($groupConfig[$key]['group_name'], "UTF-8", "UTF-8");
        }
        
        return response()->json([ 
            'status'=>200, 
            'groupList'=> $groupConfig,  
            'message'=>'Group list'
        ]);
    }

    function refreshToken(Request $request){
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            $error = $e->getMessage(); 
            return response()->json([
                'status' => 404,
                'msg' => 'Api token is required.'
            ]);
        }
        $newToken = auth()->setTTL(10080)->refresh();
        return response()->json([ 
            'status'=>200, 
            'token'=>$newToken
        ]); 
    }
}
