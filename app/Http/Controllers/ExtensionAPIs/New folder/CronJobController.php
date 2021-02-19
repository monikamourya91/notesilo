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

class CronJobController extends Controller
{
    // 
    public function __construct(){
        auth()->setDefaultDriver('api');
    }
    
     /*
    //Cancel subscription of all admin and reseller subscribers whose cancellation date arrives and now cron will change the status to 0 
    public function cancelSubscription(){

        $date = date('Y-m-d');

        $subscription_expired_users = DB::table('subscriptions')
        ->join('users','users.subscription_id','=','subscriptions.id')
        ->where('subscriptions.cancellation_effective_date','<=',$date)
        ->where('users.status',1)
        ->select('users.id','users.reseller_id','users.subscription_id','users.status')
        ->get(); 

        foreach($subscription_expired_users as $user){

            DB::table('users')->where('id',$user->id)->update([
                'status' => 0,
                //'reseller_id' => 0,
                'updated_at' => date('Y-m-d')
            ]);  

            DB::table('subscriptions')->where('id',$user->subscription_id)->update([
                'expired_on'  => date('Y-m-d'),
                'updated_at' => date('Y-m-d')
            ]);

            // if($user->reseller_id != 0){
            //     DB::table('history_logs')->insert([
            //         'user_id' => $user->id,
            //         'reseller_id' => $user->reseller_id,
            //         'last_status' => $user->status,
            //         'created_at' => date('Y-m-d')
            //     ]); 
            // }
                     
        }

        DB::table('cronjob_logs')->insert([
            'created_at' => date("Y-m-d h:i:s")
        ]);

        echo "Cron Executed Successfully.";
    }
    
    //Change status to 0 of all subscribers whose reseller's payment is failed 10 days ago and trigger email to both reseller and its subscribers  
    public function SubscriptionPaymentFailed(){

        $waiting_time = 10;
        $date = date('Y-m-d',strtotime('-'.$waiting_time.' days'));

        $payment_failed_resellers = DB::table('resellers')
        ->where('first_payment_failed','<=',$date)
        ->where('is_failed_email_sent',0)
        ->get(['id','email']);

        foreach($payment_failed_resellers as $reseller){

            $reseller_users = DB::table('users')
                            ->join('subscriptions','subscriptions.id','=','users.subscription_id')
                            ->where('users.status',1)
                            ->where('users.reseller_id',$reseller->id)
                            ->get(['users.id as user_id','users.email','users.status','subscriptions.id as subscription_id','subscriptions.payment_subscription_id']);

            foreach ($reseller_users as $user) {
                DB::table('history_logs')->insert([
                    'user_id'                    => $user->user_id,
                    'reseller_id'                => $reseller->id,
                    'payment_subscription_id'   => $user->payment_subscription_id,
                    'last_status'                => $user->status,
                    'created_at'                 => date('Y-m-d')
                ]);

                DB::table('users')->where('id',$user->user_id)->update([
                    'status' => 1,
                    'reseller_id' => 0,
                    'updated_at' => date('Y-m-d')
                ]); 

                DB::table('subscriptions')->where('id',$user->subscription_id)->update([
                    'payment_subscription_id' => '',
                    'updated_at' => date('Y-m-d')
                ]);  
            }

            //set flag so that cron will not again update this reseller's users
            DB::table('resellers')->where('id',$reseller->id)->update([
                'is_failed_email_sent' => 1,
                'updated_at' => date('Y-m-d')
            ]); 
        }
        DB::table('cronjob_logs')->insert([
            'created_at' => date("Y-m-d h:i:s")
        ]);
        echo "Cron Executed Successfully.";
    }

    // Send mail to users who plan is expired now and his reseller payment is failed earlier 
    public function sendMailToJoinAdminPlans(){

        //Users whose expiry date is today and have an entry in history logs table 
        //doubt- what will happen if there is case that history table contain same user with different reseller and different paddle subscription id
        $tomorrow = date('Y-m-d',strtotime('+ 1 day'));
        $users = DB::table('users')
                ->join('subscriptions','subscriptions.id','=','users.subscription_id')
                ->join('history_logs','history_logs.user_id','=','users.id')
                ->join('payment_gateway_settings','payment_gateway_settings.reseller_id','=','history_logs.reseller_id')
                ->where('users.reseller_id',0)
                ->where('subscriptions.expired_on',$tomorrow)
                ->select('users.email','history_logs.payment_subscription_id','history_logs.reseller_id','payment_gateway_settings.credentials')
                ->get();
 
        foreach($users as $user){
            //trigger email to user and cancel subscription in paddle
            $credentials = json_decode($user->credentials,true);
            $cancleData = [];
            $cancleData['vendor_id'] = $credentials['vendor_id'];
            $cancleData['vendor_auth_code'] = $credentials['api_key'];
            $cancleData['subscription_id'] = $user->payment_subscription_id;
            $this->cancleSubscription($cancleData); 
            //echo $credentials['vendor_id']." ".$credentials['api_key'];
        }
    }

    public function cancleSubscription($cancleData){
        // $url = "https://vendors.paddle.com/api/2.0/subscription/users_cancel";
        // $ch = curl_init($url);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $cancleData);                
        // $result = curl_exec($ch);
        // $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // curl_close($ch);
        // if($httpCode == 200){
        //     return $result; 
        // } else {
        //     return false;
        // }
    }
    
    /* Renew the subscription of manual subscribers as per their current plan 
    public function manualUsersSubscriptionExtend(){
        $today = date("Y-m-d");
        //Monthly Yearly Manual Subscribers who is expiring today 
        $subscribers = DB::table('users')
                    ->join('subscriptions','subscriptions.id','=','users.subscription_id')
                    ->join('plans','plans.id','=','subscriptions.plan_id')
                    ->where('subscriptions.expired_on',$today)
                    ->Where(function ($q) {
                        $q->where('subscriptions.payment_subscription_id','')
                            ->orWhere('users.is_manual', 1);
                    })
                    ->Where(function ($query) {
                        $query->where('plans.type', 'monthly')
                              ->orWhere('plans.type', 'yearly');
                    })
                    ->select(['users.id as user_id','subscriptions.id as subscription_id','subscriptions.expired_on','plans.type as plan_type'])
                    ->get();

        foreach($subscribers as $subscriber){
            if($subscriber->plan_type == 'monthly'){
                $new_expiry_date = date('Y-m-d', strtotime(" + 1 month"));
            }elseif($subscriber->plan_type == 'yearly'){
                $new_expiry_date = date('Y-m-d', strtotime(" + 1 year"));
            }else{
                $new_expiry_date = $today;
            }

            DB::table('subscriptions')->where('id',$subscriber->subscription_id)->update([
                'expired_on' => $new_expiry_date,
                'updated_at' => date("Y-m-d h:i:s")
            ]);

            DB::table('users')->where('id',$subscriber->user_id)->update([
                'status' => 1,
                'updated_at' => date("Y-m-d h:i:s")
            ]);
        }

        DB::table('cronjob_logs')->insert([
            'created_at' => date('Y-m-d h:i:s')
        ]);         
        echo "Cron Executed Successfully.";
    }*/
}
