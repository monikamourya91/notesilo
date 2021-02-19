<?php

namespace App\Modules\Reseller\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Reseller\Models\Reseller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\User;


class ResellerController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
	 
	public function __construct(){
		//$this->middleware('auth:admin')->except(['validateAdminEmailCheck','index']);
	}
	
    public function index(){
        if(Auth::guard('reseller')->check())
        {   
            return redirect()->route('reseller.dashboard');
        }else{
            return redirect()->route('reseller.loginForm');
        }
    }

    public function dashboard()
    {

        $reseller_status = Auth::guard('reseller')->user()->status;
        $reseller_id = Auth::guard('reseller')->user()->id;

        $active_subscribers = DB::table('users')
        ->where('reseller_id',$reseller_id)
        ->where('status',1)
        ->count();

        $gateways = DB::table('payment_gateway_settings')
        ->where('reseller_id',$reseller_id)
        ->count();

        $active_plans = DB::table('resellers as t1')
        ->join('payment_gateway_settings as t2','t1.id','=','t2.reseller_id')
        ->join('plans as t3','t2.id','=','t3.payment_gateway_id')
        ->where('t1.status',1)
        ->where('t2.status',1)
        ->where('t3.status',1)
        ->where('t2.reseller_id',$reseller_id)
        ->count();

        return view("Reseller::dashboard",compact('active_subscribers','gateways','active_plans','reseller_status'));
    }

    public function profile()
    {
        $loggedInUser = Auth::guard('reseller')->user()->id;
        $adminData = Reseller::find($loggedInUser);

        $subscription_info = DB::table('resellers')
        ->join('subscriptions','subscriptions.id','=','resellers.subscription_id')
        ->join('plans','plans.id','=','subscriptions.plan_id')
        ->where('resellers.id',$loggedInUser)
        ->get()
        ->first();

        $used_licenses = DB::table('users')->where('reseller_id',$loggedInUser)->count();
        $paid_subscribers = DB::table('users')
        ->join('subscriptions','subscriptions.id','=','users.subscription_id')
        ->where('users.reseller_id',$loggedInUser)
        ->where('subscriptions.payment_subscription_id','!=','')
        ->count();
        return view("Reseller::profile", compact('adminData','subscription_info','used_licenses','paid_subscribers'));
    }

    public function changePassword()
    {
        return view("Reseller::changepassword");
    }

    public function updatePassword(Request $request)
    {
        $password = $request->input('password');
        $id       = $request->input('id');
        
        $reseller = Reseller::find($id);
        if($reseller) {
            $reseller->password = Hash::make($password);
            $reseller->save();
        }
        return redirect()->route("reseller.changePassword")->with('success','Password changed successfully.');
    }

    public function editProfile($id)
    {
        $admin = Reseller::find($id);
        
        return view('Reseller::editprofile',compact('admin'));
    }

   public function validateResellerEmailCheck(Request $request)
    {   

        $email = $request['email'];
        $user  = User::where('email',$email)->get()->count();
        $reseller = Reseller::where('email',$email)->get()->count();
        if($reseller or $user){
            if($reseller){
                $count = Reseller::where('email',$email)->get()->count();
                /* $admin_data = Admin::where('email',$email)->get('id')->first();
                $admin_id = Admin::where('id','!=',$admin_data->id)->where('email',$email)->get()->count();*/
                if($count > 1){
                    echo json_encode(false);
                    die();
                }else{
                    echo json_encode(true);
                    die();
                }
            }
            echo json_encode(false);
        }else{
            echo json_encode(true);
        }
         die;
    } 

    public function updateProfile($id, Request $request)
    {
        $name  = $request->input('name');
        $email = $request->input('email');

        $already = Reseller::where('id','!=',$id)->where('email',$email)->get()->count();
        if(!$already){
            $reseller = Reseller::find($id);
            if($reseller) {
                $reseller->name  = $name;
                //$reseller->email = $email;
                $reseller->save();
            }
            return redirect()->route('reseller.editProfile',['id' => $id])->with('success','Profile updated successfully.');
        }else{
            return redirect()->route('reseller.editProfile',['id' => $id])->with('error','This email id is already exist'); 
        }
    }
}
