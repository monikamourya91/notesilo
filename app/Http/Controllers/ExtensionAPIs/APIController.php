<?php

namespace App\Http\Controllers\ExtensionAPIs;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\User;
use App\Password_reset;
use App\Autoresponder_list as allAutoresponders;
use App\Autoresponder as userAutoresponder;
use App\All_lead;
use App\Subscription;
use App\User_setting;

class APIController extends Controller
{

    public function __construct(){
        auth()->setDefaultDriver('api');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'email' => 'required',
           'password' => 'required'
      	]);
        
		if ($validator->fails()) {
			return response()->json([
				'status' => false,
				'message' => $validator->errors()->first()
			]);
		}

        $credentials['email']       = $request->input('email');
        $credentials['password']    = $request->input('password');
        $token = auth()->attempt($credentials);

        if(empty($token)){
            return response()->json([
                'status' => false,
                'message' => "Wrong email and password."
            ]);
        }

        $user = auth()->user();

        if($user->status == 0){
            return response()->json([
                'status' => false,
                'message' => "Your account is not activated."
            ]);
        }
        
        if($user->first_login == 0){
            User::where('id',$user->id)->update([
                'first_login' => 1
            ]);
        }
        
        /*$plan_data = DB::table('users as u')
        ->join('subscriptions as s','s.id','=','u.subscription_id')
        ->join('plans as p','p.id','=','s.plan_id')
        ->where('u.id',$user->id)
        ->select('p.name as plan','s.expired_on','p.price','p.type','p.id as plan_id')
        ->get()
        ->first();
        
        $highest_plan_data = DB::table('plans')
        ->orderBy('leads_limit','desc')
        ->limit(1)
        ->get(['id'])->first();
        $highest_plan_id = $highest_plan_data->id;
        
        $upgrade = true;
        if($plan_data->plan_id == $highest_plan_id){
            $upgrade = false;
        }*/
        
        return response()->json([
            'status' => true,
            'user' => $user,
            'token' => $token,
            //'plan' => $plan_data,
            //'upgradeBtn' => $upgrade,
            'message' => "Login Successfully."
        ]);
    }
    
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'email' => 'required',
           'password' => 'required'
      	]);
        
		if ($validator->fails()) {
			return response()->json([
				'status' => false,
				'message' => $validator->errors()->first()
			]);
		}

        $email      = $request->input('email');
        $password   = $request->input('password');
        $name = explode('@',$email)[0];
        
        $existing_user = User::where('email',$email)->count();
        if($existing_user > 0){
        	return response()->json([
				'status' => false,
				'message' => "Email id already exists."
			]);
        }
        $subscription = new Subscription;
        $subscription->plan_id = 1;
        $subscription->started_on = Carbon::now();
        $subscription->expired_on = null;
        $subscription->save();
        $subscription_id = $subscription->id;

        $user = new User;
        $user->name = $name;
        $user->email  = $email;
        $user->password = Hash::make($password);
        $user->subscription_id = $subscription_id;
        $user->status = 1;
        $user->save();
        
        $newUser_id = $user->id;

        //create settings
        $uniqueHash = generateRandomString(19);
        $newUser_setting = new User_setting;
        $newUser_setting->unique_hash   = $uniqueHash;
        $newUser_setting->user_id       = $newUser_id;
        $newUser_setting->save();
        
        //sendWelcomeMailNew($email, $name);
        return response()->json([
            'status' => true,
            'message' => "Signup Successfully."
        ]);
    }
        
    public function logout()
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            $error = $e->getMessage(); 
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
        auth()->logout();
        return response()->json([
            'status' => true,
            'message' => "Logged Out Successfully."
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'email' => 'required|email'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $email = $request->input('email');

        $user = User::where('email',$email)->get()->first();

        if(empty($user)){
            return response()->json([
                'status'=>false,
                'message'=>"Email is not registered with us."
            ]);
        }
        $tempPassword = strtolower(generateRandomString(8));
        User::where('email',$email)->update([
            'password' => Hash::make($tempPassword) 
        ]);

        //$reset_token = generateRandomString(20);
        //$user->password = Hash::make($password);
        
        // Password_reset::insert([
        //     'email' => $email,
        //     'token' => $reset_token,
        //     'created_at' => date('Y-m-d h:i:s')
        // ]);

        sentResetPasswordLink($user->name,$email,$tempPassword);
        // if($mail_sent){
        return response()->json([
            'status'=>true, 
            'message'=>'Reset link sent to your email.'
        ]);          
        // } else {
        //     return response()->json([
        //         'status'=>false,
        //         'message'=>"Error in sending mail."
        //     ]);          
        // } 
    }

    public function changePassword(Request $request)
    {   
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            $error = $e->getMessage(); 
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
        
        $user_id = auth()->user()->id;

        $validator = Validator::make($request->all(), [
           'old_password' => 'required',
           'password' => 'required',
           'cpassword' => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);
        }
        
        $old_password = $request->input('old_password');
        $password = $request->input('password');
        $cpassword = $request->input('cpassword');
        
        $user_password = User::where('id',$user_id)->get(['password'])->first();
        if(!Hash::check($old_password, $user_password->password)){
            return response()->json([
                'status' => false,
                'message' => "Incorrect old Password"
            ]);
        }
        
        if($password != $cpassword){
            return response()->json([
                'status' => false,
                'message' => "Password & Confirm Password must be same."
            ]);
        }

        User::where('id',$user_id)->update([
            'password' => Hash::make($password)
        ]); 

        return response()->json([
            'status' => true,
            'message' => "Password changed successfully."
        ]);
    }

    public function getAllAutoresponders(){
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            $error = $e->getMessage(); 
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
        $logo_base_url = url('/public/logos').'/';
        $user_id = $user->id;
        $allActiveAutoresponders = DB::table('autoresponder_list')
        ->leftJoin('autoresponders', function($join) use ($user_id){
            $join->on('autoresponders.autoresponder_list_id', '=', 'autoresponder_list.id')
                 ->where('autoresponders.userId', '=', $user_id);
        })
        ->where('autoresponder_list.status',1)
        ->orderBy('autoresponder_list.id')
        ->get([
            'autoresponder_list.id as autoresponder_id','responder_type as name','responder_key as slug',DB::raw('CONCAT("'.$logo_base_url.'",logo) as logo'),'field_one_validation','field_two_validation','field_three_validation',
            'field_one_value','field_two_value','field_three_value','autoresponders.status as status'
        ]);

        return response()->json([
            'status' => true,
            'message' => "",
            'autoresponders' => $allActiveAutoresponders
        ]);
    }

    /*public function getAutoresponderFields(Request $request){
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            $error = $e->getMessage(); 
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }

        $validator = Validator::make($request->all(), [
           'autoresponderId' => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $autoresponderId = $request->input('autoresponderId');


        $autoresponderFields = allAutoresponders::where('id',$autoresponderId)->where('status',1)->get([
            'field_one_validation','field_two_validation','field_three_validation'
        ])->first();

        return response()->json([
            'status' => true,
            'message' => "",
            'fields' => $autoresponderFields
        ]);
    }*/

    public function updateAutoresponderCredentials(Request $request){
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            $error = $e->getMessage(); 
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }

        $validator = Validator::make($request->all(), [
           'aId' => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);
        }
        
        $autoresponder_id = $request->input('aId');
        $user_id = $user->id;
        $field_one = $field_two = $field_three = "";

        if($request->has('field_one_validation') && $request->input('field_one_validation') != ""){
            $field_one = $request->input('field_one_validation');
        }
        if($request->has('field_two_validation') && $request->input('field_two_validation') != ""){
            $field_two = $request->input('field_two_validation');
        }
        if($request->has('field_three_validation') && $request->input('field_three_validation') != ""){
            $field_three = $request->input('field_three_validation');
        }

        $count = userAutoresponder::where('autoresponder_list_id',$autoresponder_id)
                                ->where('userId',$user_id)
                                ->count();
        $type = allAutoresponders::where('id',$autoresponder_id)
                                ->get(['responder_key'])->first();;

        $postCredentials = [
            'field_one_value'   => $field_one, 
            'field_two_value'   => $field_two , 
            'field_three_value' => $field_three, 
            'type'              => $type->responder_key
        ];

        if(verifyAutoresponderCredentials($postCredentials)){
            if($count > 0){
                $executed = userAutoresponder::where('autoresponder_list_id',$autoresponder_id)
                    ->where('userId',$user_id)
                    ->update([
                        'field_one_value'       => $field_one,
                        'field_two_value'       => $field_two,
                        'field_three_value'     => $field_three
                    ]);
            }else{
                $executed = userAutoresponder::insert([
                    'autoresponder_list_id' => $autoresponder_id,
                    'userId'                => $user_id,
                    'field_one_value'       => $field_one,
                    'field_two_value'       => $field_two,
                    'field_three_value'     => $field_three
                ]);
            }

            if($executed){
                return response()->json([
                    'status' => true,
                    'message' => 'Credentials updated.'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Some error occured.'
                ]);
            }
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Invalid Credentials.'
            ]);
        }
    }

    public function updateAutoresponderStatus(Request $request){
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            $error = $e->getMessage(); 
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }

        $validator = Validator::make($request->all(), [
            'aId' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $updated = userAutoresponder::where('userId',$user->id)
                        ->where('autoresponder_list_id',$request->input('aId'))
                        ->update([
                            'status' => $request->input('status')
                        ]);

        if($updated){
            return response()->json([
                'status' => true,
                'message' => 'Status Updated.'
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Some error occured.'
            ]);    
        }
    }
    
    public function getProfileData(Request $request){
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            $error = $e->getMessage(); 
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }

        $user_data = DB::table('users as u')
        ->join('subscriptions as s','s.id','=','u.subscription_id')
        ->join('plans as p','p.id','=','s.plan_id')
        ->join('user_settings as us','us.user_id','=','u.id')
        ->where('u.id',$user->id)
        ->select('us.unique_hash','u.name','u.email','p.name as plan','s.expired_on','p.price','p.type','p.id as plan_id')
        ->get()
        ->first();
        
        $plan_data = DB::table('plans')
        ->orderBy('leads_limit','desc')
        ->limit(1)
        ->get(['id'])->first();
        $highet_plan_id = $plan_data->id;
        
        $upgrade = true;
        if($user_data->plan_id == $highet_plan_id){
            $upgrade = false;
        }
        
        return response()->json([
            'status' => true,
            'message' => '',
            'data' => $user_data,
            'upgradeBtn' => $upgrade
        ]);
    }
    
    public function updateProfileData(Request $request){
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            $error = $e->getMessage(); 
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);
        }
    
        $name = $request->input('name');
        User::where('id',$user->id)->update([
            'name' => $name
        ]); 

        return response()->json([
            'status' => true,
            'message' => 'Profile Updated.'
        ]);
        
    }

    public function getActiveAutorespondersCount(Request $request){
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            $error = $e->getMessage(); 
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }

        $count = DB::table('autoresponders')
        ->where('userId',$user->id)
        ->where('status',1)
        ->count();


        /*Notice if a specific limit is pending*/
        $limit = DB::table('users as u')
        ->join('subscriptions as s','s.id','=','u.subscription_id')
        ->join('plans as p','p.id','=','s.plan_id')
        ->where('u.id',$user->id)
        ->select('p.leads_limit')
        ->get()->first();
        $total_leads = $limit->leads_limit;

        $current_month = Carbon::now()->month;
        $current_year = Carbon::now()->year;
        $added_leads = All_lead::where("user_id",$user->id)->whereMonth('created_at', $current_month)->whereYear('created_at', $current_year)->distinct('email')->count('email');
        
        /*$plan_limit = DB::table('users')
        ->join('subscriptions','subscriptions.id','=','users.subscription_id')
        ->join('plans','subscriptions.plan_id','=','plans.id')
        ->where('users.id',$user->id)
        ->select('plans.leads_limit')
        ->get()->first();
        $plan_limit = $plan_limit->leads_limit;
        $current_month = Carbon::now()->month;
        $added_leads = All_lead::where("user_id",$user->id)->whereMonth('created_at', $current_month)->count();*/
        
        $leads_can_add = $total_leads-$added_leads;
        $pending_limit = 50;
        $notice = "";
        if($leads_can_add < $pending_limit){
            $notice = "Only ".$leads_can_add." leads are left.";
        }
        
        return response()->json([
            'status' => true,
            'message' => '',
            'data' => $count,
            'notice' => $notice,
            'total_leads' => $total_leads,
            'used_leads' => $added_leads
        ]);
    }

    public function addLeadsToAutoresponder(Request $request){
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            $error = $e->getMessage(); 
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }

        $validator = Validator::make($request->all(), [
            'leads' => 'required',
            'url' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $current_month = Carbon::now()->month;
        $added_leads = All_lead::where("user_id",$user->id)->whereMonth('created_at', $current_month)->count();
        $plan_limit = DB::table('users')
        ->join('subscriptions','subscriptions.id','=','users.subscription_id')
        ->join('plans','subscriptions.plan_id','=','plans.id')
        ->where('users.id',$user->id)
        ->select('plans.leads_limit')
        ->get()->first();

        $plan_limit = $plan_limit->leads_limit;
        $new_leads = count($request->input('leads'));

        if($added_leads >= $plan_limit){
            return response()->json([
                'status' => false,
                'message' => "Plan limit Exceeded."
            ]);
        }

        $leads_can_add = $plan_limit-$added_leads;

        if($leads_can_add < $new_leads){
            return response()->json([
                'status' => false,
                'message' => "Can add maximum ".$leads_can_add." lead(s)."
            ]);
        }

        $autoresponders = DB::table('autoresponders')
        ->join('autoresponder_list','autoresponder_list.id','=','autoresponders.autoresponder_list_id')
        ->where('autoresponders.userId',$user->id)
        ->where('autoresponders.status',1)
        ->select("autoresponder_list.responder_key","autoresponders.*")
        ->get();

        $autoresponders_count = $autoresponders->count();
        if($autoresponders_count == 0){
            return response()->json([
                'status' => false,
                'message' => "No active autoresponders."
            ]);
        }

        $leads =$request->input('leads');
        $url =$request->input('url');
        $date = date("Y-m-d H:i:s");
        foreach($leads as $lead){
            $already_added = All_lead::where("user_id",$user->id)->where("email",$lead['email'])->count();
            if($already_added == 0){
                All_lead::insert([
                    'user_id' => $user->id,
                    'name' => $lead['name'],
                    'email' => $lead['email'],
                    'url' => $url,
                    'created_at' => $date,
                    'updated_at' => $date
                ]);
            }
        }

        // $autoresponders = json_decode(json_encode($autoresponders),true);
        // foreach($autoresponders as $autoresponder){
        //     addSubscriber($leads,$autoresponder); 
        // }

        return response()->json([
            'status' => true,
            'message' => 'Leads Added.'
        ]);

    }
    
    public function getAccountData(Request $request){
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            $error = $e->getMessage(); 
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
        
        $user_id = $user->id;
        
        /*$limit = DB::table('users as u')
        ->join('subscriptions as s','s.id','=','u.subscription_id')
        ->join('plans as p','p.id','=','s.plan_id')
        ->where('u.id',$user_id)
        ->select('p.leads_limit')
        ->get()->first();*/
        
        $current_plan_data = DB::table('users as u')
        ->join('subscriptions as s','s.id','=','u.subscription_id')
        ->join('plans as p','p.id','=','s.plan_id')
        ->join('user_settings as us','us.user_id','=','u.id')
        ->where('u.id',$user->id)
        ->select('us.unique_hash','p.id as plan_id','p.name as plan','p.price','p.type','p.leads_limit','s.expired_on')
        ->get()
        ->first();
        $total_leads = $current_plan_data->leads_limit;

        $current_month = Carbon::now()->month;
        $current_year = Carbon::now()->year;
        $added_leads = All_lead::where("user_id",$user_id)->whereMonth('created_at', $current_month)->whereYear('created_at', $current_year)->distinct('email')->count('email');

        $leadsOverview = [
            'total_leads' => $total_leads,
            'used_leads' => $added_leads,
            'used_percentage' => ($added_leads/$total_leads)*100
        ];
        
        $highest_plan_data = DB::table('plans')
        ->orderBy('leads_limit','desc')
        ->limit(1)
        ->get(['id'])->first();
        $highest_plan_id = $highest_plan_data->id;
        
        $upgrade = true;
        if($current_plan_data->plan_id == $highest_plan_id){
            $upgrade = false;
        }
        $current_plan_data->upgrade = $upgrade;
        return response()->json([
            'status' => true,
            'message' => "",
            'leadsOverview' => $leadsOverview,
            'planDetails' => $current_plan_data
        ]);
        
        /* For force logout */
        // return response()->json([
        //     'status' => true,
        //     'message' => "",
        //     'leadsOverview' => $leadsOverview,
        //     'planDetails' => $current_plan_data
        // ],205);
    }
    
    public function getUserDataForSite($hash){
        //$hash = base64_decode($hash);
        
        $user_details = DB::table('users')
        ->join('subscriptions','subscriptions.id','=','users.subscription_id')
        ->join('plans','plans.id','=','subscriptions.plan_id')
        ->join('user_settings','user_settings.user_id','=','users.id')
        ->where('user_settings.unique_hash',$hash)
        //->where('users.id',$hash)
        ->select('users.id','subscriptions.plan_id','subscriptions.payment_subscription_id','plans.live_plan_id','plans.type')
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
    
    public function upgradeUserPlan(Request $request){
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
        ->select('plans.live_plan_id','plans.type','plans.leads_limit')
        ->get()->first();

        $newPlanData = DB::table('plans')
        ->where('live_plan_id',$paddlePlanId)
        ->select('plans.live_plan_id','plans.type','plans.leads_limit')
        ->get()->first();

        if(!empty($user) && !empty($newPlanData)){
                
             $payment_mode = DB::table('payment_gateway_settings')
            ->where('id',1)
            ->get(['credentials'])->first();
            
            $credentials = json_decode($payment_mode->credentials , true);

            $data = [];
            $data['vendor_id'] =   $credentials['vendor_id'];
            $data['vendor_auth_code'] =  $credentials['api_key'];
            $data['subscription_id'] =  $sub_paddle_id;
            $data['plan_id'] = $paddlePlanId;
            
            if($user->leads_limit < $newPlanData->leads_limit){
                $action = "upgraded";
                $data['bill_immediately'] = true;
            }else{
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
    
    function exportLeads(Request $request){
        echo "<pre>";
        print_r($request->all());
        echo "</pre>";
    }


}
