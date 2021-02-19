<?php

namespace App\Http\Controllers\ExtensionAPIs;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\User;
use App\User_setting;
use App\Linked_fb_group;
use App\Auto_approve_setting;
use Illuminate\Support\Facades\Mail;
use App\Mail\ExtensionShareWithFriends;
use App\Fb_group_setting;

class SettingsController extends Controller
{
    // 
    public function __construct(){
        auth()->setDefaultDriver('api');
    }

    public function globalAutoresponderStatus(Request $request)
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
           'global_auto_status' => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'msg' => $validator->errors()->first()
            ]);
        }
        $user_id = auth()->user()->id;
        $global_auto_status = $request->input('global_auto_status');

        $count = User_setting::where('user_id',$user_id)->count();
        if($count == '0')
        {
            $user_setting = new User_setting;
            $user_setting->user_id = $user_id;
            $user_setting->global_autoresponder_status = $global_auto_status;
            $user_setting->save();
        }else{
            User_setting::where('user_id',$user_id)
            ->update([
                "global_autoresponder_status" => $global_auto_status
            ]);
        }
        return response()->json([
            "status" => 200,
            "msg" => 'Settings updated.'
        ]);
    }

    public function enableOneGooglesheet(Request $request)
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
           'enable_status' => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'msg' => $validator->errors()->first()
            ]);
        }
        $user_id = auth()->user()->id;
        $enable_status = $request->input('enable_status');

        $count = User_setting::where('user_id',$user_id)->count();
        if($count == '0')
        {
            $user_setting = new User_setting;
            $user_setting->user_id = $user_id;
            $user_setting->enable_googlesheet = $enable_status;
            $user_setting->save();
        }else{
            User_setting::where('user_id',$user_id)
            ->update([
                "enable_googlesheet" => $enable_status
            ]);
        }
       
       return response()->json([
            "status" => 200,
            "msg" => 'Settings updated.'
        ]);
    }

    public function shareWithFriends(Request $request)
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
           'emails' => 'required',
           'sharemessagetext' => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'msg' => $validator->errors()->first()
            ]);
        }

        if($request->has('emails')){
            $emails_array = explode(',', $request->input('emails'));
            //checked all emails are valid or not.
            foreach($emails_array as $email){
                $email = trim($email);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return response()->json([
                        "status" => 404,
                        "msg" => "{$email} is an invalid email."
                    ]);
                }
            }
            //trigger email 
            foreach($emails_array as $email){
                $email = trim($email);
                $subject = "Learn how to collect data and email leads from your facebook group!";
                $message = $request->input('sharemessagetext');
                sendGridApi($email, $subject, $message);
                //Mail::to($email)->send(new ExtensionShareWithFriends($data));
            }
        }
        return response()->json([
            "status" => 404,
            "msg" => "Shared with friends successfully."
        ]);
    }

    public function getUserDetailsWordpress($hash){
        $user_details = DB::table('users')
        ->join('subscriptions','subscriptions.id','=','users.subscription_id')
        ->join('plans','plans.id','=','subscriptions.plan_id')
        ->join('user_settings','user_settings.user_id','=','users.id')
        ->where('user_settings.unique_hash',$hash)
        ->select('users.id','subscriptions.plan_id','subscriptions.payment_subscription_id','plans.live_plan_id')
        ->get()->first();

        if(!empty($user_details)){
            return response()->json([
                'status'=>200,
                'userDetails' =>$user_details
            ]);
        }else{
            return response()->json([
                'status'=>404,
                'userDetails' =>[]
            ]);
        }
    }
    
    public function changePlanWordpress(Request $request){


        $userId = $request->input('user_id');
        $selectedPlanId = $request->input('selected_plan_id'); // existing plan id 
        $paddlePlanId = $request->input('paddle_plan_id'); //new plan id
        $sub_paddle_id = $request->input('sub_paddle_id'); //subscription id

        $user = DB::table('users')
        ->join('subscriptions','subscriptions.id','=','users.subscription_id')
        ->join('plans','plans.id','=','subscriptions.plan_id')
        ->where('users.id',$userId)
        ->select('plans.live_plan_id','plans.type')
        ->get()->first();

        $newPlanData = DB::table('plans')
        ->where('live_plan_id',$paddlePlanId)
        ->select('plans.live_plan_id','plans.type')
        ->get()->first();

        if(!empty($user)){
                
            /*if($selectedPlanId == $user['plan_id'] || $selectedPlanId > 3 || $user['paddle_subscription_id'] == "" ){
                 echo '{"success":false,"error":{"code":116,"message":"Invalid request"}}';
                 die();
            }*/
             $payment_mode = DB::table('payment_gateway_settings')
            ->where('id',1)
            ->get(['credentials'])->first();
            
            $credentials = json_decode($payment_mode->credentials , true);

            $data = [];
            $data['vendor_id'] =   $credentials['vendor_id'];
            $data['vendor_auth_code'] =  $credentials['api_key'];
            $data['subscription_id'] =  $sub_paddle_id;
            $data['plan_id'] = $paddlePlanId;
            


            if($user->type == 'monthly' && $newPlanData->type == 'yearly'){
                $action = "upgraded";
                  $data['bill_immediately'] = true;
            }elseif($user->type == 'monthly' && $newPlanData->type == 'life_time'){
                $action = "upgraded";
                  $data['bill_immediately'] = true;
            }elseif($user->type == 'yearly' && $newPlanData->type == 'monthly'){
                $action = "downgraded";
                  $data['bill_immediately'] = false;
            }elseif($user->type == 'yearly' && $newPlanData->type == 'life_time'){
                $action = "upgraded";
                  $data['bill_immediately'] = true;
            }elseif($user->type == 'life_time' && $newPlanData->type == 'monthly'){
                $action = "downgraded";
                  $data['bill_immediately'] = false;
            }elseif($user->type == 'life_time' && $newPlanData->type == 'yearly'){
                $action = "downgraded";
                  $data['bill_immediately'] = false;
            }

            // $upgradeMsg = "upgraded";
            // if($selectedPlanId > $user['plan_id']){
            //     $data['bill_immediately'] = true;
            // } else {
            //     $data['bill_immediately'] = false;
            //     $upgradeMsg = "downgraded";                 
            // }

         
           
            $response = $this->callVendorAPI($data);
            //$response = '{"success":true,"response":{"plan_id":0,"subscription_id":"0"}}';
            if($response){
                echo $response;
                die();
            } else {
                echo '{"success":false,"error":{"code":116,"message":"Request failed"}}';
                die();
            }
        } else {
            echo '{"success":false,"error":{"code":116,"message":"Invalid request"}}';
            die();
        }

    }

    function callVendorAPI($data){
        //  echo json_encode($data); die();
        $url = "https://vendors.paddle.com/api/2.0/subscription/users/move";
        $ch = curl_init($url);
        $json = json_encode($data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($httpCode == 200){
            //$result = json_decode($result);
            return $result; 
        } else {
            return false;
        }
    }
    
    /*public function getResellerDetailsWordpress($hash){
        $reseller_details = DB::table('resellers')
        ->join('subscriptions','subscriptions.id','=','resellers.subscription_id')
        ->join('plans','plans.id','=','subscriptions.plan_id')
        ->where('resellers.reseller_hash',$hash)
        ->select('resellers.id as reseller_id','subscriptions.plan_id','subscriptions.payment_subscription_id','plans.live_plan_id','resellers.package_limit')
        ->get()->first();
        
        $available_packages = DB::table('plans')
        ->where('level',1)
        ->where('is_reseller_package',1)
        ->where('status',1)
        ->where('license_limit','>',$reseller_details->package_limit)
        ->select('id','live_plan_id')
        ->get();

        if(!empty($reseller_details)){
            return response()->json([
                'status'=>200,
                'resellerDetails' =>$reseller_details,
                'available_packages' =>$available_packages
            ]);
        }else{
            return response()->json([
                'status'=>404,
                'resellerDetails' =>[],
                'available_packages' => []
            ]);
        }
    }

    public function changeResellerPlanWordpress(Request $request){
        $reseller_id = $request->input('reseller_id');
        //$selectedPlanId = $request->input('selected_plan_id'); // existing plan id 
        $paddlePlanId = $request->input('paddle_plan_id'); //new plan id
        $sub_paddle_id = $request->input('sub_paddle_id'); //subscription id
        
        $reseller = DB::table('resellers')
        ->join('subscriptions','subscriptions.id','=','resellers.subscription_id')
        ->join('plans','plans.id','=','subscriptions.plan_id')
        ->where('resellers.id',$reseller_id)
        ->select('plans.live_plan_id','plans.type','plans.license_limit')
        ->get()->first();

        $newPlanData = DB::table('plans')
        ->where('live_plan_id',$paddlePlanId)
        ->select('plans.live_plan_id','plans.type','plans.license_limit')
        ->get()->first();
        
        if(!empty($reseller)){
             $payment_mode = DB::table('payment_gateway_settings')
            ->where('id',1)
            ->get(['credentials'])->first();
            
            $credentials = json_decode($payment_mode->credentials , true);

            $data = [];
            $data['vendor_id'] =   $credentials['vendor_id'];
            $data['vendor_auth_code'] =  $credentials['api_key'];
            $data['subscription_id'] =  $sub_paddle_id;
            $data['plan_id'] = $paddlePlanId;

            if($reseller->license_limit < $newPlanData->license_limit){
                $action = "upgraded";
                $data['bill_immediately'] = true;
            }elseif($reseller->license_limit > $newPlanData->license_limit){
                $action = "downgraded";
                $data['bill_immediately'] = false;
            }
            $response = $this->callVendorAPI($data);
            //$response = '{"success":true,"response":{"plan_id":0,"subscription_id":"0"}}';
            if($response){
                echo $response;
                die();
            } else {
                echo '{"success":false,"error":{"code":116,"message":"Request failed"}}';
                die();
            }
        } else {
            echo '{"success":false,"error":{"code":116,"message":"Invalid request"}}';
            die();
        }
    }

    public function getResellerSubsPlanDetails($hash){
        $user_details = DB::table('users')
        ->join('subscriptions','subscriptions.id','=','users.subscription_id')
        ->join('plans','plans.id','=','subscriptions.plan_id')
        ->join('user_settings','user_settings.user_id','=','users.id')
        ->where('user_settings.unique_hash',$hash)
        ->select('users.id','subscriptions.plan_id','subscriptions.payment_subscription_id','plans.live_plan_id','users.reseller_id')
        ->get()->first();

        $reseller_plans = DB::table('resellers as t1')
            ->join('payment_gateway_settings as t2','t1.id','=','t2.reseller_id')
            ->join('plans as t3','t2.id','=','t3.payment_gateway_id')
            ->where('t1.status',1)
            ->where('t2.status',1)
            ->where('t3.status',1)
            ->where('t2.reseller_id',$user_details->reseller_id)
            //->select('t3.*', 't2.credentials')
            ->orderBy('t3.price', 'ASC')
            ->get(['t3.id','t3.live_plan_id']);

        if(!empty($user_details)){
            return response()->json([
                'status'=>200,
                'userDetails' =>$user_details,
                'resellerPlans' =>$reseller_plans
            ]);
        }else{
            return response()->json([
                'status'=>404,
                'userDetails' =>[],
                'resellerPlans' =>[]
            ]);
        }
    }

    public function changeResellerSubscriberPlan(Request $request){
        header('Access-Control-Allow-Origin: *');
	    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    	header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
        $userId = $request->input('user_id');
        $paddlePlanId = $request->input('paddle_plan_id'); //new plan id
        $sub_paddle_id = $request->input('sub_paddle_id'); //subscription id
        $user = DB::table('users')
        ->join('subscriptions','subscriptions.id','=','users.subscription_id')
        ->join('plans','plans.id','=','subscriptions.plan_id')
        ->where('users.id',$userId)
        ->select('plans.live_plan_id','plans.type','users.reseller_id')
        ->get()->first();
        
        $newPlanData = DB::table('plans')
        ->where('live_plan_id',$paddlePlanId)
        ->select('plans.live_plan_id','plans.type')
        ->get()->first();

        if(!empty($user)){
             $payment_mode = DB::table('payment_gateway_settings')
            ->where('reseller_id',$user->reseller_id)
            ->get(['credentials'])->first();

            $credentials = json_decode($payment_mode->credentials , true);

            $data = [];
            $data['vendor_id'] =   $credentials['vendor_id'];
            $data['vendor_auth_code'] =  $credentials['api_key'];
            $data['subscription_id'] =  $sub_paddle_id;
            $data['plan_id'] = $paddlePlanId;
            
            if($user->type == 'monthly' && $newPlanData->type == 'yearly'){
                $action = "upgraded";
                  $data['bill_immediately'] = true;
            }elseif($user->type == 'yearly' && $newPlanData->type == 'monthly'){
                $action = "downgraded";
                  $data['bill_immediately'] = false;
            }

            $response = $this->callVendorAPI($data);
            //$response = '{"success":true,"response":{"plan_id":0,"subscription_id":"0"}}';
            if($response){
                echo $response;
                die();
            } else {
                echo '{"success":false,"error":{"code":116,"message":"Request failed"}}';
                die();
            }
        } else {
            echo '{"success":false,"error":{"code":116,"message":"Invalid request"}}';
            die();
        }

    }*/
    
    public function getUserDetailsWordpressV2($hash){
        $user_details = DB::table('users')
        ->join('subscriptions','subscriptions.id','=','users.subscription_id')
        ->join('plans','plans.id','=','subscriptions.plan_id')
        ->join('user_settings','user_settings.user_id','=','users.id')
        ->where('user_settings.unique_hash',$hash)
        ->select('users.id','subscriptions.plan_id','subscriptions.payment_subscription_id','plans.live_plan_id','plans.type','plans.fb_groups')
        ->get()->first();

        if(!empty($user_details)){
            return response()->json([
                'status'=>200,
                'userDetails' =>$user_details
            ]);
        }else{
            return response()->json([
                'status'=>404,
                'userDetails' =>[]
            ]);
        }
    }
    
    public function changePlanWordpressV2(Request $request){
        header('Access-Control-Allow-Origin: *');
	    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    	header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With'); 
        $userId = $request->input('user_id');
        $paddlePlanId = $request->input('paddle_plan_id'); //new plan id
        $sub_paddle_id = $request->input('sub_paddle_id'); //subscription id
        
        $user = DB::table('users')
        ->join('subscriptions','subscriptions.id','=','users.subscription_id')
        ->join('plans','plans.id','=','subscriptions.plan_id')
        ->where('users.id',$userId)
        ->select('plans.live_plan_id','plans.type','plans.fb_groups')
        ->get()->first();

        $newPlanData = DB::table('plans')
        ->where('live_plan_id',$paddlePlanId)
        ->select('plans.live_plan_id','plans.type','plans.fb_groups')
        ->get()->first();

        if(!empty($user) && !empty($newPlanData)){
                
            /*if($selectedPlanId == $user['plan_id'] || $selectedPlanId > 3 || $user['paddle_subscription_id'] == "" ){
                 echo '{"success":false,"error":{"code":116,"message":"Invalid request"}}';
                 die();
            }*/
             $payment_mode = DB::table('payment_gateway_settings')
            ->where('id',1)
            ->get(['credentials'])->first();
            
            $credentials = json_decode($payment_mode->credentials , true);

            $data = [];
            $data['vendor_id'] =   $credentials['vendor_id'];
            $data['vendor_auth_code'] =  $credentials['api_key'];
            $data['subscription_id'] =  $sub_paddle_id;
            $data['plan_id'] = $paddlePlanId;
            
            if($user->type == 'monthly' && $newPlanData->type == 'yearly'){
                $action = "upgraded";
                  $data['bill_immediately'] = true;
            }elseif($user->type == 'monthly' && $newPlanData->type == 'life_time'){
                $action = "upgraded";
                  $data['bill_immediately'] = true;
            }elseif($user->type == 'yearly' && $newPlanData->type == 'monthly'){
                $action = "downgraded";
                  $data['bill_immediately'] = false;
            }elseif($user->type == 'yearly' && $newPlanData->type == 'life_time'){
                $action = "upgraded";
                  $data['bill_immediately'] = true;
            }elseif($user->type == 'life_time' && $newPlanData->type == 'monthly'){
                $action = "downgraded";
                  $data['bill_immediately'] = false;
            }elseif($user->type == 'life_time' && $newPlanData->type == 'yearly'){
                $action = "downgraded";
                  $data['bill_immediately'] = false;
            }elseif($user->fb_groups == '2' && $newPlanData->fb_groups == '4'){
                $action = "upgraded";
                  $data['bill_immediately'] = true;
            }elseif($user->fb_groups == '2' && $newPlanData->fb_groups == null){
                $action = "upgraded";
                  $data['bill_immediately'] = true;
            }elseif($user->fb_groups == '4' && $newPlanData->fb_groups == '2'){
                $action = "downgraded";
                  $data['bill_immediately'] = false;
            }elseif($user->fb_groups == '4' && $newPlanData->fb_groups == null){
                $action = "upgraded";
                  $data['bill_immediately'] = true;
            }elseif($user->fb_groups == null && $newPlanData->fb_groups == '2'){
                $action = "downgraded";
                  $data['bill_immediately'] = false;
            }elseif($user->fb_groups == null && $newPlanData->fb_groups == '3'){
                $action = "downgraded";
                  $data['bill_immediately'] = false;
            }
            $response = $this->callVendorAPI($data);
            //$response = '{"success":true,"response":{"plan_id":0,"subscription_id":"0"}}'; 
            if($response){
                echo $response; 
                die();
            } else {
                echo '{"success":false,"error":{"code":116,"message":"Request failed"}}';
                die();
            }
        } else {
            echo '{"success":false,"error":{"code":116,"message":"Invalid request"}}';
            die();
        }

    }
}
