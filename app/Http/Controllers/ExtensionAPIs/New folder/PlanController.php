<?php

namespace App\Http\Controllers\ExtensionAPIs;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\User;

class PlanController extends Controller
{
    // 
    public function __construct(){
        auth()->setDefaultDriver('api');
    }

    public function planDetails(Request $request)
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

        $plan_details = DB::table('users')
        ->join('subscriptions','users.subscription_id','=','subscriptions.id')
        ->join('plans','subscriptions.plan_id','=','plans.id')
        ->join('user_settings','user_settings.user_id','=','users.id')
        ->where('users.id',$user_id)
        ->select('users.id as user_id','users.reseller_id','subscriptions.is_trial as trial','subscriptions.plan_id','plans.name','plans.fb_groups','user_settings.unique_hash')
        ->get()->first();

        if(empty($plan_details)){
            $plan_details = [];
        }
        return response()->json([
            "status" => 200,
            "planDetails" => $plan_details
        ]);
    }
}
