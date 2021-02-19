<?php

namespace App\Http\Controllers\ExtensionAPIs;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\Subscription;
use App\User_setting;
use App\Plan;
use App\Reseller;

class PaddleNotificationController extends Controller
{
    // 
    public function __construct(){
        //auth()->setDefaultDriver('api');
    }

    public function paddleNotification(Request $request)
    {   
        $data = $request->all();
        
        $admin_plans = DB::table('plans')->where('level',1)->get();
        $adminSubscriberPlansArray = $adminSubscriberPlansMappingArray = $adminResellerPlansArray = $adminResellerPlansMappingArray = array();
        foreach ($admin_plans as $plan_data) {
            if($plan_data->is_reseller_package == 1){
                $adminResellerPlansArray[] = $plan_data->live_plan_id;
                $adminResellerPlansMappingArray[$plan_data->id] = $plan_data->live_plan_id;
            }else{
                $adminSubscriberPlansArray[] = $plan_data->live_plan_id;
                $adminSubscriberPlansMappingArray[$plan_data->id] = $plan_data->live_plan_id;
            }
        }
        
        if(in_array($data["subscription_plan_id"], $adminResellerPlansArray)){
            //reseller plans
            $plan_id = array_search($data["subscription_plan_id"],$adminResellerPlansMappingArray);
            $user_type = "reseller";
        }elseif(in_array($data["subscription_plan_id"], $adminSubscriberPlansArray)){
            //subscriber plans
             $plan_id = array_search($data["subscription_plan_id"],$adminSubscriberPlansMappingArray);
             $user_type = "subscriber";
        }else{
            return "Invalid Plan ids";
        }

        $plan_data = DB::table('plans')->where('id',$plan_id)->get()->first();
        $trial = $plan_data->trial;
        
         switch ($user_type) {
                case "subscriber":
                    if($data["alert_name"] == "subscription_created"){
                        if(isset($data["subscription_id"])){
                            $license_key = generateRandomString(16);
                            if($plan_data->type == 'life_time'){
                                $next_bill_date = null;
                            } else {
                                $next_bill_date = $data["next_bill_date"];
                            }

                            $existingUser = DB::table('users')->where('email',$data['email'])->get()->first();

                            if(!empty($existingUser)){

                                $existingPlanData = Subscription::where('id',$existingUser->subscription_id)->get()->first();
                                User::where('id',$existingUser->id)->update([
                                    'status'     => 1, 
                                    'updated_at' => date('Y-m-d h:i:s')
                                ]);

                                Subscription::where('id',$existingUser->subscription_id)->update([
                                    'payment_subscription_id'       => $data["subscription_id"],
                                    'plan_id'                       => $plan_id, 
                                    'is_trial'                      => $trial, 
                                    'expired_on'                    => $next_bill_date, 
                                    'cancellation_effective_date'   => null,
                                    'updated_at'                    => date('Y-m-d h:i:s')
                                ]); 

                                if($plan_id != $existingPlanData->plan_id){
                                    /*admins payment gateway*/
                                    $credentials_data = DB::table('plans as t1')
                                    ->join('payment_gateway_settings as t2','t2.id','=','t1.payment_gateway_id')
                                    ->where('t1.id',$existingPlanData->plan_id)
                                    ->select('t2.credentials')
                                    ->get()->first();
                                    $credentials = json_decode($credentials_data->credentials,true);
                                    
                                    $cancleData = [];
                                    $cancleData['vendor_id'] = $credentials['vendor_id'];
                                    $cancleData['vendor_auth_code'] = $credentials['api_key'];
                                    $cancleData['subscription_id'] = $existingPlanData->payment_subscription_id;
                                    $this->cancleSubscription($cancleData);
                                }
                            }else { 
                                $userPassword = generateRandomString(5);
                                $uniqueHash = generateRandomString(19);

                                // create subscription
                                $new_subscription = new Subscription;
                                $new_subscription->plan_id      = $plan_id; 
                                $new_subscription->payment_type = 'paddle';
                                $new_subscription->is_trial     = $trial;
                                $new_subscription->started_on   = date('Y-m-d');
                                $new_subscription->expired_on   = $next_bill_date;
                                $new_subscription->payment_subscription_id  = $data["subscription_id"];
                                $new_subscription->created_at   = date('Y-m-d');
                                $new_subscription->save();

                                $newSubscription_id = $new_subscription->id;

                                //create user
                                $new_user = new User;
                                $new_user->email            = $data['email'];
                                $new_user->password         = Hash::make($userPassword);
                                $new_user->license          = $license_key;
                                $new_user->subscription_id  = $newSubscription_id;
                                $new_user->status           = 1;
                                $new_user->created_at       = date('Y-m-d h:i:s');
                                $new_user->updated_at       = date('Y-m-d h:i:s');
                                $new_user->save();

                                $newUser_id = $new_user->id;

                                //create settings
                                $newUser_setting = new User_setting;
                                $newUser_setting->unique_hash   = $uniqueHash;
                                $newUser_setting->is_email_send = 0;
                                $newUser_setting->user_id       = $newUser_id;
                                $newUser_setting->save();

                                if($newUser_id > 0){

                                    $newUser_details = User::where('id',$newUser_id)
                                                    ->get(['name','email'])->first();

                                    sendWelcomeMailNew($newUser_details->email,$userPassword, '', $license_key);
                                    $this->addUserToGetResponse($plan_id, $newUser_details->email, $plan_data->type);
                                }
                            }
                        }
                        echo "Success";
                    }

                    if($data["alert_name"] == "subscription_updated"){
                        if(isset($data["subscription_id"])){
                            
                            if($plan_data->type == 'life_time'){
                                $next_bill_date = null;
                            } else {
                                $next_bill_date = $data["next_bill_date"];
                            }
                            
                            $existing_subscription =  Subscription::where('payment_subscription_id',$data["subscription_id"])->get()->first();

                            $action = "";
                            $existing_plan_data = DB::table('plans')->where('id',$existing_subscription->plan_id)->get(['type'])->first();
                            $existing_plan_type = $existing_plan_data->type;
            
                            if($existing_plan_type == 'monthly' && $plan_data->type == 'yearly'){
                                $action = "upgraded";
                            }elseif($existing_plan_type == 'monthly' && $plan_data->type == 'life_time'){
                                $action = "upgraded";
                            }elseif($existing_plan_type == 'yearly' && $plan_data->type == 'monthly'){
                                $action = "downgraded";
                            }elseif($existing_plan_type == 'yearly' && $plan_data->type == 'life_time'){
                                $action = "upgraded";
                            }elseif($existing_plan_type == 'life_time' && $plan_data->type == 'monthly'){
                                $action = "downgraded";
                            }elseif($existing_plan_type == 'life_time' && $plan_data->type == 'yearly'){
                                $action = "downgraded";
                            }

                            Subscription::where('payment_subscription_id',$data["subscription_id"])->update([
                                'plan_id' => $plan_id, 
                                'is_trial'=> 0,
                                'expired_on' => $next_bill_date,
                                'cancellation_effective_date' => null,  
                                'updated_at' =>  date('Y-m-d h:i:s') 
                            ]);

                            User::where('subscription_id',$existing_subscription->id)->update([
                                'status' => 1
                            ]);
                            
                            if($action != ""){
                                $user_details = User::where('subscription_id',$existing_subscription->id)->get(['name','email'])->first();
                                $plan_data = plan::where('id',$plan_id)->get(['name'])->first();
                                $plan_name = $plan_data->name;
                                sendUpgradeDowngradeMail($user_details->email,$user_details->name,$plan_name, $action);
                            }
                        }
                        echo "Success";
                    }

                    if($data["alert_name"] == "subscription_cancelled"){
                        if(isset($data["subscription_id"])){

                            Subscription::where('payment_subscription_id',$data["subscription_id"])->update([
                                'is_trial'                      => 0,
                                'cancellation_effective_date'   => $data["cancellation_effective_date"],
                                'updated_at'                    => date('Y-m-d h:i:s')
                            ]);

                            $subscription_data = Subscription::where('payment_subscription_id',$data["subscription_id"])->get(['id'])->first();

                            User::where('subscription_id',$subscription_data->id)->update([
                                'status' => 1,
                                'updated_at' => date('Y-m-d h:i:s')
                            ]);

                            $user_details = User::where('subscription_id',$subscription_data->id)->get(['name','email'])->first();

                            cancelSubscriptionMail($user_details->email,$user_details->name,false, $data["cancellation_effective_date"]); 
                        }
                        echo "Success";
                    }

                    if($data["alert_name"] == "subscription_payment_failed"){
                        if(isset($data["subscription_id"])){

                            Subscription::where('payment_subscription_id',$data["subscription_id"])->update([
                                'is_trial'                      => 0,
                                'cancellation_effective_date'   => date('Y-m-d'),
                                'updated_at'                    => date('Y-m-d h:i:s')
                            ]);

                            $subscription_data = Subscription::where('payment_subscription_id',$data["subscription_id"])->get(['id'])->first();

                            User::where('subscription_id',$subscription_data->id)->update([
                                'status' => 0,
                                'updated_at' => date('Y-m-d h:i:s')
                            ]);

                            $user_details = User::where('subscription_id',$subscription_data->id)->get(['name','email'])->first();
                            cancelSubscriptionMail($user_details->email,$user_details->name,true);
                        }
                        echo "Success";
                    }

                    if($data["alert_name"] == "subscription_payment_succeeded"){
                        if(isset($data["subscription_id"])){
                            sleep(1);
                            $customer_name = '';
                            if(isset($data["customer_name"])){
                               $customer_name = $data["customer_name"];
                            }

                            $user_details = DB::table('users')                    
                            ->join('subscriptions','subscriptions.id','=','users.subscription_id')
                            ->join('user_settings','users.id','=','user_settings.user_id')
                            ->where('subscriptions.payment_subscription_id',$data["subscription_id"])
                            ->select('users.id','users.email','users.license','user_settings.is_email_send')
                            ->get()->first();
                            
                            $isSleep = false;
                            if($user_details->is_email_send == 0){
                                $isSleep = true;
                            }

                            Subscription::where('payment_subscription_id',$data["subscription_id"])->update([
                                'is_trial'                      => 0,
                                'expired_on'                    => $data["next_bill_date"],
                                'cancellation_effective_date'   => null,
                                'updated_at'                    => date('Y-m-d h:i:s')
                            ]);

                            User::where('id',$user_details->id)->update([
                                'status'     => 1,
                                'name'       => $customer_name,
                                'updated_at' => date('Y-m-d h:i:s')
                            ]);
                            User_setting::where('user_id',$user_details->id)->update([
                                'is_email_send'     => 1,
                                'updated_at' => date('Y-m-d h:i:s')
                            ]);
                            
                            if($isSleep){
                               sleep(5);
                               sendEmailAfterWelcome($user_details->email,$customer_name); 
                            }
                        }
                        echo "Success";
                    }
                break;
                
                case "reseller":
                    
                    //return "reseller code will execute.";
                    //$trial = 0;
                    if($data["alert_name"] == "subscription_created"){
                        if(isset($data["subscription_id"])){
                            $license_key = generateRandomString(16);
                            $next_bill_date = $data["next_bill_date"];

                            $existingUser = DB::table('resellers')->where('email',$data['email'])->get()->first();

                            if(!empty($existingUser)){

                                $existingPlanData = Subscription::where('id',$existingUser->subscription_id)->get()->first();

                                //get reseller license limit of the selected plan
                                $reseller_plan_data = DB::table('plans')->where('id',$plan_id)->get(['license_limit'])->first();
                                $license_limit = $reseller_plan_data->license_limit;

                                Reseller::where('id',$existingUser->id)->update([
                                    'status'     => 1,
                                    'first_payment_failed' => null,
                                    'package_limit' => $license_limit,
                                    'updated_at' => date('Y-m-d h:i:s')
                                ]); 

                                User::where('reseller_id',$existingUser->id)->update([
                                    'status'     => 1, 
                                    'updated_at' => date('Y-m-d h:i:s')
                                ]);                                

                                Subscription::where('id',$existingUser->subscription_id)->update([
                                    'payment_subscription_id'       => $data["subscription_id"],
                                    'plan_id'                       => $plan_id, 
                                    'is_trial'                      => $trial, 
                                    'expired_on'                    => $next_bill_date, 
                                    'cancellation_effective_date'   => null,
                                    'updated_at'                    => date('Y-m-d h:i:s')
                                ]); 

                                if($plan_id != $existingPlanData->plan_id){
                                    /* admin's gateway credentials*/
                                    $credentials_data = DB::table('plans as t1')
                                    ->join('payment_gateway_settings as t2','t2.id','=','t1.payment_gateway_id')
                                    ->where('t1.id',$existingPlanData->plan_id)
                                    ->select('t2.credentials')
                                    ->get()->first();
                                    $credentials = json_decode($credentials_data->credentials,true);
                                    $cancleData = [];
                                    $cancleData['vendor_id'] = $credentials['vendor_id'];
                                    $cancleData['vendor_auth_code'] = $credentials['api_key'];
                                    $cancleData['subscription_id'] = $existingPlanData->payment_subscription_id;
                                    $this->cancleSubscription($cancleData);
                                }
                            }else { 
                                $userPassword = generateRandomString(5);
                                $uniqueHash = generateRandomString(19);

                                // create subscription
                                $new_subscription = new Subscription;
                                $new_subscription->plan_id      = $plan_id; 
                                $new_subscription->payment_type = 'paddle';
                                $new_subscription->is_trial     = $trial;
                                $new_subscription->started_on   = date('Y-m-d');
                                $new_subscription->expired_on   = $next_bill_date;
                                $new_subscription->payment_subscription_id  = $data["subscription_id"];
                                $new_subscription->created_at   = date('Y-m-d');
                                $new_subscription->save();

                                $newSubscription_id = $new_subscription->id;

                                //get reseller license limit of the selected plan
                                $reseller_plan_data = DB::table('plans')->where('id',$plan_id)->get(['license_limit'])->first();
                                $license_limit = $reseller_plan_data->license_limit;

                                //create reseller
                                $new_user = new Reseller;
                                $new_user->email            = $data['email'];
                                $new_user->password         = Hash::make($userPassword);
                                $new_user->subscription_id  = $newSubscription_id;
                                $new_user->status           = 1;
                                $new_user->first_payment_failed = null;
                                $new_user->reseller_hash    = $uniqueHash;
                                $new_user->package_limit    = $license_limit;
                                $new_user->created_at       = date('Y-m-d h:i:s');
                                $new_user->updated_at       = date('Y-m-d h:i:s');
                                $new_user->save();

                                $newUser_id = $new_user->id;

                                if($newUser_id > 0){

                                    $newUser_details = Reseller::where('id',$newUser_id)
                                                    ->get(['name','email'])->first();

                                    sendWelcomeMailNewReseller($newUser_details->email,$userPassword, '');
                                }
                            }
                        }
                        echo "Success";
                    }

                    if($data["alert_name"] == "subscription_updated"){
                        if(isset($data["subscription_id"])){

                            $next_bill_date = $data["next_bill_date"];                            
                            $existing_subscription =  Subscription::where('payment_subscription_id',$data["subscription_id"])->get()->first();

                            $action = "";
                            $existing_plan_data = DB::table('plans')->where('id',$existing_subscription->plan_id)->get(['license_limit'])->first();
                            $existing_plan_limit = $existing_plan_data->license_limit;
            
                            if($existing_plan_limit < $plan_data->license_limit){
                                $action = "upgraded";
                            }elseif($existing_plan_limit > $plan_data->license_limit){
                                $action = "downgraded";
                            }

                            Subscription::where('payment_subscription_id',$data["subscription_id"])->update([
                                'plan_id' => $plan_id, 
                                'is_trial'=> 0,
                                'expired_on' => $next_bill_date,
                                'cancellation_effective_date' => null,  
                                'updated_at' =>  date('Y-m-d h:i:s') 
                            ]);

                            Reseller::where('subscription_id',$existing_subscription->id)->update([
                                // 'status' => 1,
                                // 'first_payment_failed' => null,
                                'package_limit' => $plan_data->license_limit,
                                'updated_at' => date('Y-m-d h:i:s')
                            ]);
                            
                            if($action != ""){
                                $user_details = Reseller::where('subscription_id',$existing_subscription->id)->get(['name','email'])->first();
                                $plan_data = plan::where('id',$plan_id)->get(['name'])->first();
                                $plan_name = $plan_data->name;
                                sendUpgradeDowngradeMail($user_details->email,$user_details->name,$plan_name, $action);
                            }
                        }
                        echo "Success";
                    }

                    if($data["alert_name"] == "subscription_cancelled"){
                        if(isset($data["subscription_id"])){
                            
                            Subscription::where('payment_subscription_id',$data["subscription_id"])->update([
                                'is_trial'                      => 0,
                                'cancellation_effective_date'   => $data["cancellation_effective_date"],
                                'updated_at'                    => date('Y-m-d h:i:s')
                            ]);

                            $subscription_data = Subscription::where('payment_subscription_id',$data["subscription_id"])->get(['id'])->first();

                            // Reseller::where('subscription_id',$subscription_data->id)->update([
                            //     'status' => 1,
                            //     'updated_at' => date('Y-m-d h:i:s')
                            // ]);

                            $user_details = Reseller::where('subscription_id',$subscription_data->id)->get(['name','email'])->first();

                            cancelSubscriptionMail($user_details->email,$user_details->name,false, $data["cancellation_effective_date"]); 
                        }
                        echo "Success";
                    }

                    if($data["alert_name"] == "subscription_payment_failed"){
                        if(isset($data["subscription_id"])){

                            Subscription::where('payment_subscription_id',$data["subscription_id"])->update([
                                'is_trial'                      => 0,
                                'cancellation_effective_date'   => date('Y-m-d'),
                                'updated_at'                    => date('Y-m-d h:i:s')
                            ]);

                            $subscription_data = Subscription::where('payment_subscription_id',$data["subscription_id"])->get(['id'])->first();

                            Reseller::where('subscription_id',$subscription_data->id)->update([
                                'status' => 0,
                                'updated_at' => date('Y-m-d h:i:s')
                            ]);

                            /* check is the first payment failed is already inserted or not */
                            $reseller_payment_failed_data = Reseller::where('subscription_id',$subscription_data->id)->get(['first_payment_failed'])->first();
                            if($reseller_payment_failed_data->first_payment_failed == null || $reseller_payment_failed_data->first_payment_failed ==''){
                                Reseller::where('subscription_id',$subscription_data->id)->update([
                                    'first_payment_failed' => date('Y-m-d h:i:s')
                                ]);
                            }
                            $user_details = Reseller::where('subscription_id',$subscription_data->id)->get(['name','email'])->first();

                            //$this->cancelSubscriptionMail($user_details->email,$user_details->name,true);
                        }
                        echo "Success";
                    }

                    if($data["alert_name"] == "subscription_payment_succeeded"){
                        if(isset($data["subscription_id"])){
                            sleep(1);
                            $customer_name = '';
                            if(isset($data["customer_name"])){
                               $customer_name = $data["customer_name"];
                            }

                            $user_details = DB::table('resellers')                    
                            ->join('subscriptions','subscriptions.id','=','resellers.subscription_id')
                            ->where('subscriptions.payment_subscription_id',$data["subscription_id"])
                            ->select('resellers.id','resellers.email','resellers.is_email_send')
                            ->get()->first();
                            
                            $isSleep = false;
                            if($user_details->is_email_send == 0){
                                $isSleep = true;
                            }

                            Subscription::where('payment_subscription_id',$data["subscription_id"])->update([
                                'is_trial'                      => 0,
                                'expired_on'                    => $data["next_bill_date"],
                                'cancellation_effective_date'   => null,
                                'updated_at'                    => date('Y-m-d h:i:s')
                            ]);

                            Reseller::where('id',$user_details->id)->update([
                                'status'     => 1,
                                'name'       => $customer_name,
                                'is_email_send'     => 1,
                                'first_payment_failed' => null,
                                'updated_at' => date('Y-m-d h:i:s')
                            ]);
                            
                            User::where('reseller_id',$user_details->id)->update([
                                'status'     => 1, 
                                'updated_at' => date('Y-m-d h:i:s')
                            ]);  

                            if($isSleep){
                               sleep(5);
                               sendEmailAfterWelcome($user_details->email,$customer_name); 
                            }
                        }
                        echo "Success";
                    }
                break;
                
                default:
                return "Something went wrong.";
            }            
    }

    public function resellerPaddleNotification(Request $request, $hash)
    {   
        $reselller_data = DB::table('resellers')->where('reseller_hash',$hash)->get()->first();

        if(empty($reselller_data)){
            return "Invalid URL.";
        }
        $reseller_id = $reselller_data->id;

        $reseller_plans = DB::table('plans')
        ->join('payment_gateway_settings','payment_gateway_settings.id','=','plans.payment_gateway_id')
        ->where('payment_gateway_settings.reseller_id',$reseller_id)
        ->where('payment_gateway_settings.payment_type','paddle')
        ->select('plans.id','plans.live_plan_id')
        ->get();

        if($reseller_plans->isEmpty()){
            return "No Plans Found";
        }
        
        foreach ($reseller_plans as $plan_data) {
            $resellerLivePlansArray[] = $plan_data->live_plan_id;
            $resellerPlansMappingArray[$plan_data->id] = $plan_data->live_plan_id;
        }

        $data = $request->all();
        if(!in_array($data["subscription_plan_id"], $resellerLivePlansArray)){
            return "Invalid Plan ids";
        }
        
        $plan_id = array_search($data["subscription_plan_id"],$resellerPlansMappingArray);

        $plan_data = DB::table('plans')->where('id',$plan_id)->get(['trial'])->first();
        $trial = $plan_data->trial;
        if($data["alert_name"] == "subscription_created"){
            if(isset($data["subscription_id"])){
                $license_key = generateRandomString(16);
                $next_bill_date = $data["next_bill_date"];
                
                $existingUser = DB::table('users')->where('email',$data['email'])->get()->first();

                if(!empty($existingUser)){

                    $existingPlanData = Subscription::where('id',$existingUser->subscription_id)
                                        ->get()->first();

                    // do i need to add a reseller id check here in users table.                    
                    User::where('id',$existingUser->id)->update([
                        'status'     => 1, 
                        'updated_at' => date('Y-m-d h:i:s')
                    ]);

                    Subscription::where('id',$existingUser->subscription_id)->update([
                        'payment_subscription_id'       => $data["subscription_id"],
                        'plan_id'                       => $plan_id, 
                        'is_trial'                      => $trial, 
                        'expired_on'                    => $next_bill_date, 
                        'cancellation_effective_date'   => null,
                        'updated_at'                    => date('Y-m-d h:i:s')
                    ]); 

                    if($plan_id != $existingPlanData->plan_id){
                        /*reseller paddle credentials*/
                        $credentials_data = DB::table('plans as t1')
                        ->join('payment_gateway_settings as t2','t2.id','=','t1.payment_gateway_id')
                        ->where('t1.id',$existingPlanData->plan_id)
                        ->select('t2.credentials')
                        ->get()->first();
                        $credentials = json_decode($credentials_data->credentials,true);
                        $cancleData = [];
                        $cancleData['vendor_id'] = $credentials['vendor_id'];
                        $cancleData['vendor_auth_code'] = $credentials['api_key'];
                        $cancleData['subscription_id'] = $existingPlanData->payment_subscription_id;
                        $this->cancleSubscription($cancleData);
                    }
                }else { 
                    $userPassword = generateRandomString(5);
                    $uniqueHash = generateRandomString(19);

                    // create subscription
                    $new_subscription = new Subscription;
                    $new_subscription->plan_id      = $plan_id; 
                    $new_subscription->payment_type = 'paddle';
                    $new_subscription->is_trial     = $trial;
                    $new_subscription->started_on   = date('Y-m-d');
                    $new_subscription->expired_on   = $next_bill_date;
                    $new_subscription->payment_subscription_id  = $data["subscription_id"];
                    $new_subscription->created_at   = date('Y-m-d');
                    $new_subscription->save();

                    $newSubscription_id = $new_subscription->id;

                    //create user
                    $new_user = new User;
                    $new_user->email            = $data['email'];
                    $new_user->password         = Hash::make($userPassword);
                    $new_user->license          = $license_key;
                    $new_user->subscription_id  = $newSubscription_id;
                    $new_user->status           = 1;
                    $new_user->reseller_id      = $reseller_id;
                    $new_user->created_at       = date('Y-m-d h:i:s');
                    $new_user->updated_at       = date('Y-m-d h:i:s');
                    $new_user->save();

                    $newUser_id = $new_user->id;

                    //create settings
                    $newUser_setting = new User_setting;
                    $newUser_setting->unique_hash   = $uniqueHash;
                    $newUser_setting->is_email_send = 0;
                    $newUser_setting->user_id       = $newUser_id;
                    $newUser_setting->save();

                    if($newUser_id > 0){

                        $newUser_details = User::where('id',$newUser_id)
                                        ->get(['name','email'])->first();

                        sendWelcomeMailNew($newUser_details->email,$userPassword, '', $license_key);
                    }
                }
            }
            echo "Success";
        }

        if($data["alert_name"] == "subscription_updated"){
            if(isset($data["subscription_id"])){
                
                $next_bill_date = $data["next_bill_date"];                
                $existing_subscription =  Subscription::where('payment_subscription_id',$data["subscription_id"])->get()->first();

                $action = "";
                /*if($existing_subscription->plan_id > $plan_id){
                    $action = "downgraded";
                } elseif($existing_subscription->plan_id < $plan_id){
                    $action = "upgraded";
                }*/ 
                /*NEW CHECK*/
                $existing_plan_data = DB::table('plans')->where('id',$existing_subscription->plan_id)->get(['type'])->first();
                $existing_plan_type = $existing_plan_data->type;

                $updated_plan_data = DB::table('plans')->where('id',$plan_id)->get(['type'])->first();
                $updated_plan_type = $updated_plan_data->type;

                if($existing_plan_type == 'monthly' && $updated_plan_type == 'yearly'){
                    $action = "upgraded";
                }elseif($existing_plan_type == 'yearly' && $updated_plan_type == 'monthly'){
                    $action = "downgraded";
                }

                Subscription::where('payment_subscription_id',$data["subscription_id"])->update([
                    'plan_id' => $plan_id, 
                    'is_trial'=> 0,
                    'expired_on' => $next_bill_date,
                    'cancellation_effective_date' => null,  
                    'updated_at' =>  date('Y-m-d h:i:s') 
                ]);

                // do i need to add a reseller id check here in users table. 
                User::where('subscription_id',$existing_subscription->id)->update([
                    'status' => 1
                ]);
                
                if($action != ""){
                    $user_details = User::where('subscription_id',$existing_subscription->id)->get(['name','email'])->first();
                    $plan_data = plan::where('id',$plan_id)->get(['name'])->first();
                    $plan_name = $plan_data->name;
                    sendUpgradeDowngradeMail($user_details->email,$user_details->name,$plan_name, $action);
                }
            }
            echo "Success";
        }

        if($data["alert_name"] == "subscription_cancelled"){
            if(isset($data["subscription_id"])){
                
                Subscription::where('payment_subscription_id',$data["subscription_id"])->update([
                    'is_trial'                      => 0,
                    'cancellation_effective_date'   => $data["cancellation_effective_date"],
                    'updated_at'                    => date('Y-m-d h:i:s')
                ]);

                $subscription_data = Subscription::where('payment_subscription_id',$data["subscription_id"])->get(['id'])->first();

                // do i need to add a reseller id check here in users table. 
                User::where('subscription_id',$subscription_data->id)->update([
                    'status' => 1,
                    'updated_at' => date('Y-m-d h:i:s')
                ]);

                $user_details = User::where('subscription_id',$subscription_data->id)->get(['name','email'])->first();

                cancelSubscriptionMail($user_details->email,$user_details->name,false, $data["cancellation_effective_date"]); 
            }
            echo "Success";
        }

        if($data["alert_name"] == "subscription_payment_failed"){
            if(isset($data["subscription_id"])){

                Subscription::where('payment_subscription_id',$data["subscription_id"])->update([
                    'is_trial'                      => 0,
                    'cancellation_effective_date'   => date('Y-m-d'),
                    'updated_at'                    => date('Y-m-d h:i:s')
                ]);

                $subscription_data = Subscription::where('payment_subscription_id',$data["subscription_id"])->get(['id'])->first();

                // do i need to add a reseller id check here in users table. 
                User::where('subscription_id',$subscription_data->id)->update([
                    'status' => 0,
                    'updated_at' => date('Y-m-d h:i:s')
                ]);

                $user_details = User::where('subscription_id',$subscription_data->id)->get(['name','email'])->first();

                //$this->cancelSubscriptionMail($user_details->email,$user_details->name,true);
            }
            echo "Success";
        }

        if($data["alert_name"] == "subscription_payment_succeeded"){
            if(isset($data["subscription_id"])){
                sleep(1);
                $customer_name = '';
                if(isset($data["customer_name"])){
                   $customer_name = $data["customer_name"];
                }

                // do i need to add a reseller id check here in users table. 
                $user_details = DB::table('users')                    
                ->join('subscriptions','subscriptions.id','=','users.subscription_id')
                ->join('user_settings','users.id','=','user_settings.user_id')
                ->where('subscriptions.payment_subscription_id',$data["subscription_id"])
                ->select('users.id','users.email','users.license','user_settings.is_email_send')
                ->get()->first();
                
                $isSleep = false;
                if($user_details->is_email_send == 0){
                    $isSleep = true;
                }

                Subscription::where('payment_subscription_id',$data["subscription_id"])->update([
                    'is_trial'                      => 0,
                    'expired_on'                    => $data["next_bill_date"],
                    'cancellation_effective_date'   => null,
                    'updated_at'                    => date('Y-m-d h:i:s')
                ]);

                // do i need to add a reseller id check here in users table. 
                User::where('id',$user_details->id)->update([
                    'status'     => 1,
                    'name'       => $customer_name,
                    'updated_at' => date('Y-m-d h:i:s')
                ]);
                User_setting::where('user_id',$user_details->id)->update([
                    'is_email_send'     => 1,
                    'updated_at' => date('Y-m-d h:i:s')
                ]);
                
                if($isSleep){
                   sleep(5);
                   sendEmailAfterWelcome($user_details->email,$customer_name); 
                }
            }
            echo "Success";
        }
    }

    public function cancleSubscription($cancleData){
        $url = "https://vendors.paddle.com/api/2.0/subscription/users_cancel";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $cancleData);                
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($httpCode == 200){
            return $result; 
        } else {
            return false;
        }
    }

    public function addUserToGetResponse($plan_id, $email, $plan_type) { 
      
        $apiKey = 'hjpgzsav5g5w0u17rpsyex7gjut25oi5';
      
        $campaignName = '';
        //******************** Sendfox List IDS******************
        $starterListId = 'group-leads-starter-subscribers';
        $proListId = 'group-leads-pro-subscribers';
        $lifeTimeListId = 'group-leads-lifetime-subscribers';
        $affliateListId = 'group-leads-affiliates';
        //******************** Sendfox List IDS******************
        
        if($plan_type == 'monthly'){
            $campaignName = $starterListId;
        }else if($plan_type == 'yearly'){
            $campaignName = $proListId;
        }else if($plan_type == 'life_time'){
            $campaignName = $lifeTimeListId;
        }
      
        $headers = array(
            'Content-Type: application/json',
            'X-Auth-Token: api-key '.$apiKey
        );

        $result = json_decode(getCompaignId($headers, $campaignName), true);
        if(isset($result["httpStatus"]) && ($result["httpStatus"] == 401)){
            $response = false;
        }elseif(sizeof($result) > 0){
            $compaign_id = $result[0]["campaignId"];                

                $subData = [
                    'email' => strtolower($email),
                    'dayOfCycle' => 0,
                    "campaign" => array(
                        "campaignId" => $compaign_id
                    )
                ];
            $url = 'https://api.getresponse.com/v3/contacts';
            $json = json_encode($subData);
            $ch = curl_init($url);
        
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        
            $result = curl_exec($ch);
           
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            return $httpCode;       
        }
    }
}
