<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\User;

class FrontendController extends Controller
{
    public function showPlans($hash = ""){

        if(!empty($hash)){
            //show reseller plans
            $reseller = DB::table('resellers')->where('reseller_hash',$hash)->get()->first();

            $plans_list = DB::table('resellers as t1')
            ->join('payment_gateway_settings as t2','t1.id','=','t2.reseller_id')
            ->join('plans as t3','t2.id','=','t3.payment_gateway_id')
            ->where('t1.status',1)
            ->where('t2.status',1)
            ->where('t3.status',1)
            ->where('t2.reseller_id',$reseller->id)
            ->select('t3.*', 't2.credentials')
            ->orderBy('t3.price', 'ASC')
            ->get();

            $resellers_subscribers = User::where('reseller_id',$reseller->id)->count();
            $package_limit_already_complete = false;
            if($resellers_subscribers >= $reseller->package_limit ){
                $package_limit_already_complete = true;
            }

            $count = count($plans_list);

            return view('frontend.plans',compact('plans_list','count','package_limit_already_complete'));

        }else{

            return redirect('https://groupleads.net/plans/'); 

           
        }        

    }
    
    
    
    public function getSubscriptionId(){
        
             
                echo "<pre>";
                 $plans_list = DB::table('users as t1')
                ->join('subscriptions as t2','t1.subscription_id','=','t2.id')
                ->whereNotNull('t2.payment_subscription_id')
                ->skip(0)->take(10)
                ->select('t1.email', 't2.payment_subscription_id')
                ->get();
                
                
                print_r((array)$plans_list);

        
        
    }

 
}
