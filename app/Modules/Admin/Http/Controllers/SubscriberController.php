<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Fb_group_setting as FbGroupSetting;
use App\Linked_fb_group as FbGroup;
use App\Autoresponder_list as AutoresponderList;
use App\Autoresponder;
use App\Payment_gateway_setting as PaymentGateway;
use App\Plan;
use App\Subscription;

use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriberWelcomeEmail;
use App\Mail\SendLicense;


class SubscriberController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
	 
	public function subscribersList(Request $request)
	{
        if($request->has('email')){
            $email = $request->input('email');
            //$users = User::where('email','like','%'.$email.'%')->get();
            $users = DB::table('users')
            ->leftJoin('subscriptions','subscriptions.id','=','users.subscription_id')
            ->leftJoin('plans','plans.id','=','subscriptions.plan_id')
            ->leftJoin('resellers','resellers.id','=','users.reseller_id')
            ->where('users.email','like','%'.$email.'%')
            ->select('users.*','plans.name as plan_name','subscriptions.payment_subscription_id','resellers.email as reseller')
            ->get();
        }else{
            //$users = User::all();
            $users = DB::table('users')
            ->leftJoin('subscriptions','subscriptions.id','=','users.subscription_id')
            ->leftJoin('plans','plans.id','=','subscriptions.plan_id')
            ->leftJoin('resellers','resellers.id','=','users.reseller_id')
            ->select('users.*','plans.name as plan_name','subscriptions.payment_subscription_id','resellers.email as reseller')->orderBy('users.id', 'DESC')
            ->get();
        }
		
		return view("Admin::subscribers.subscribers",compact('users'));
	}

	public function subscriberDetails($id)
	{
		$user = DB::table('users')
                ->leftJoin('resellers','resellers.id','=','users.reseller_id')
                ->where('users.id',$id)
                ->select('users.*','resellers.email as reseller')
                ->get()->first();
		

		$planData = DB::table('users')
		->join('subscriptions','users.subscription_id','=','subscriptions.id')
		->join('plans','subscriptions.plan_id','=','plans.id')
		->where('users.id',$id)
		->select('users.subscription_id','subscriptions.*','plans.name as plan_name')
		->get();

		return view("Admin::subscribers.view_subscriber",compact('user','planData'));
	}

	public function updateSubscriber(Request $request)
	{
		$request->validate([
			'name' => 'required',
			'email' => 'required|email|unique:users,email,'.$request->input('userId'),
		]);

		if($request->has('userId') && $request->input('userId') != "")
		{
			$user = User::find($request->input('userId'));
			$user->name  = $request->input('name');
			$user->email = $request->input('email');
			$user->save();
			return redirect()->back()->with('message','Profile updated Successfully.');
		}
	}

	public function groupResponders(Request $request)
	{
		$group_id = $request->input('group_id');

		$autoresponders = DB::table('fb_group_settings')
		->join('autoresponders', 'fb_group_settings.autoresponder_id', '=', 'autoresponders.autoresponder_list_id')
		->join('autoresponder_list', 'fb_group_settings.autoresponder_id', '=', 'autoresponder_list.id')
		->where('fb_group_settings.group_id',$group_id)
		->where('autoresponders.group_id',$group_id)
		->select('fb_group_settings.group_id','autoresponders.field_one_value','autoresponders.field_two_value','autoresponders.field_three_value','autoresponder_list.responder_type')
		->get();

		if(count($autoresponders) > 0)
		{
		$response = "<table class='autoresponders ' border='1' cellspacing='0' cellpadding='3'>
						<tr>
							<th>Sr. No.</th>
							<th>Type</th>
							<th>API Key</th>
							<th>List (ID|Name)</th>
							<th>App Path</th>
						</tr>";
						$i = 1;
						foreach($autoresponders as $autoresponder)
						{
							$response .= "<tr>
								<td>{$i}</td>
								<td>{$autoresponder->responder_type}</td>
								<td style='word-break: break-all;'>{$autoresponder->field_two_value}</td>
								<td>{$autoresponder->field_one_value}</td>
								<td>{$autoresponder->field_three_value}</td>
							</tr>";
							$i++;
						}
		$response .= "</table>";
		}else{
			$response = "<p class='text-center'>No records found.</p>";
		}
		echo $response;
	}

	public function AddNew()
    {
        $error_msg = "";
        
        $active_gateway = PaymentGateway::where('reseller_id',0)
        ->where('status',1)->get('id');

        if($active_gateway->isEmpty())
        {
            $error_msg = "Your account doesn't have any active payment mode. Please add new or activate existing payment mode.";
            return view('Admin::subscribers.add_subscriber',compact('error_msg'));
        }

        $plans = Plan::where('status',1)->where('level',1)->where('is_reseller_package',0)->where('payment_gateway_id',$active_gateway[0]->id)->orderby('id','asc')->get(['id','name','price','trial']);


        if($plans->isEmpty())
        {
            $error_msg = "Your account doesn't have any active plan. Please add new or activate existing plan.";
            return view('Admin::subscribers.add_subscriber',compact('error_msg'));
        }

        return view('Admin::subscribers.add_subscriber',compact('error_msg','plans'));
    }

    public function StoreNewSubscriber(Request $request)
    {
        $request->validate([
            "email" => "required|email|unique:users",
            "name"  => "required",
            "plan"  => "required"
        ]);

        $plan_details = DB::table('plans')
        ->join('payment_gateway_settings','payment_gateway_settings.id','=','plans.payment_gateway_id')
        ->where('payment_gateway_settings.reseller_id',0)
        ->where('plans.status',1)
        ->where('plans.id',$request->input('plan'))
        ->select('payment_gateway_settings.payment_type','plans.type')
        ->get();

        $trial = 0;
        if($request->has('is_trial'))
        {
            $trial = 1;
        }
        if($plan_details[0]->type == "monthly"){
            $expire_on = date("Y-m-d",strtotime('+1 month'));
        }elseif($plan_details[0]->type == "yearly"){
            $expire_on = date("Y-m-d",strtotime('+1 year'));
        }elseif($plan_details[0]->type == "life_time"){
            $expire_on = "";
        }else{
            $expire_on = date("Y-m-d");
        }

        $subscription = new Subscription;
        $subscription->payment_type = $plan_details[0]->payment_type;
        $subscription->plan_id = $request->input('plan');
        $subscription->payment_subscription_id = "";
        $subscription->is_trial = $trial;
        $subscription->started_on = date("Y-m-d");
        $subscription->expired_on = $expire_on;
        $subscription->save();

        $subscription_id = $subscription->id;
       	$password = generateRandomString(5);
        $license = generateRandomString(16);

        $user = new User;
        $user->name = $request->input('name');
        $user->email  = $request->input('email');
        $user->subscription_id = $subscription_id;
        $user->password = Hash::make($password);
        $user->license = $license;
        $user->reseller_id = 0;
        $user->is_manual = 1;
        $user->status = 1;
        $user->save();

        $unique_hash = generateRandomString(16);
        DB::table('user_settings')->insert([
            'user_id' => $user->id,
            'unique_hash' => $unique_hash
        ]);

        // $data['name'] = $request->input('name');
        // $data['email'] = $request->input('email');
        // $data['license'] = $license;
        // $data['password'] = $password;
        $to = $request->input('email');
        $subject = "Your Purchased License!";
        $message = "Hi ".$request->input('name').",<br/><br/>
                    Thanks for signing up!<br/><br/>
                    To login to the extension, here is your License Key : {$license}<br/><br/>
                    Email: ".$request->input('email')." <br/>
                    Password: {$password}<br/><br/>
                    
                    Thank you<br/>";
        sendGridApi($to, $subject, $message);
        // Mail::to($request->input('email'))->send(new SubscriberWelcomeEmail($data));

        return redirect()->route('admin.subscribersList')->with('success','Subscriber added successfully.');
    }

    public function sendLicense($id)
    { 
    	$subscriber = User::find($id);
    	//$data['license'] = $subscriber->license;
        $to = $subscriber->email;
        $subject = "Your Purchased License!";
        $message = "Hey,<br/><br/>
                    Thank you for signing up!<br/><br/>
                    It seems you've not been able to receive your license due to one reason or the other.<br/><br/>
                    Maybe it landed in your spam folder.<br/><br/>
                    Here is your License Key : <b>".$subscriber->license."</b><br/><br/>
                    
                    If you have any question, just reply to this email and I will be happy to assist you further.<br/><br/>
                    Thank you<br/>";
        sendGridApi($to, $subject, $message);
    	//Mail::to($subscriber->email)->send(new SendLicense($data));

    	return redirect()->route('admin.subscribersList')->with('success','License sent successfully to '.$subscriber->email.'.');
    }
    
    public function cancelSubscription(Request $request){
        $status = $request->input('status');
        $user_id = $request->input('userId');

        DB::table('users')->where('id',$user_id)->update([
            'status' => $status
        ]);

        $user_data = DB::table('users')->where('id',$user_id)
                    ->select('id','name','email')
                    ->get()->first();

        if($status == 0){   
            cancelSubscriptionMail($user_data->email,$user_data->name);
        } else {
            //reactivateSubscriptionMail($user["email"],$user["name"]);
        }

        echo json_encode(array('status'=>200,'msg'=>'Subscription updated successfully.'));
        die();
    }

    public function subscriberChangePlan($id){
        $user = DB::table('users')
               ->join('subscriptions','subscriptions.id','=','users.subscription_id')
               ->join('plans','plans.id','=','subscriptions.plan_id')
                ->where('users.id',$id)
               ->select('users.id','users.name','users.email','plans.id as plan_id','plans.name as plan_name','subscriptions.expired_on','subscriptions.id as subscription_id')
                ->get()
                ->first();
    
	 
	  

        $plans = DB::table('plans')
                    ->where('level',1)
                    ->where('is_reseller_package',0)
                    ->select('plans.id','plans.name','plans.price','plans.trial')
                    ->orderby('id','asc')
                    ->get();

        return view("Admin::subscribers.subscriber_plan_change",compact('user','plans'));
    }

    public function updateSubscription(Request $request){
        $request->validate([
            'plan' => 'required'
        ]);

        $new_plan_id           = $request->input('plan');
        $old_plan_id           = $request->input('old_plan_id');
        $user_id    = $request->input('id');
        $subscription_id    = $request->input('subscription_id');
        
        $old_plan_type = DB::table('plans')
                        ->where('id',$old_plan_id)
                        ->get(['type'])->first();
        $new_plan_type = DB::table('plans')
                        ->where('id',$new_plan_id)
                        ->get(['type'])->first();
        $action = "";
        if($old_plan_type->type == 'monthly' && ($new_plan_type->type == 'yearly' || $new_plan_type->type == 'life_time'))
        {
            $action = "upgraded";
        }elseif($old_plan_type->type == 'yearly' && $new_plan_type->type == 'life_time'){
            $action = "upgraded";
        }elseif($old_plan_type->type == 'yearly' && $new_plan_type->type == 'monthly'){
            $action = "downgraded";
        }elseif($old_plan_type->type == 'life_time' && ($new_plan_type->type == 'monthly' || $new_plan_type->type == 'yearly')){
            $action = "downgraded";
        }

        if($new_plan_type->type == 'life_time'){
            $expiry_date = NULL;
        }elseif($new_plan_type->type == 'yearly'){
            $expiry_date = date('Y-m-d', strtotime(" + 1 year"));
        }else{
            $expiry_date = date('Y-m-d', strtotime(" + 1 month"));
        }

        DB::table('subscriptions')
            ->where('id',$subscription_id)
            ->update([
                'plan_id' => $new_plan_id,
                'expired_on' => $expiry_date,
                'cancellation_effective_date' => NULL
            ]);
        
        DB::table('users')
            ->where('id',$user_id)
            ->update([
                'status' => 1,
                'is_manual' => 1
            ]);

        $user_data = DB::table('users')
                ->join('subscriptions','subscriptions.id','=','users.subscription_id')
                ->join('plans','plans.id','=','subscriptions.plan_id')
                ->where('users.id',$user_id)
                ->select('users.name','users.email','plans.name as plan_name')
                ->get()
                ->first();

        if($action != ""){
            sendUpgradeDowngradeMail($user_data->email,$user_data->name,$user_data->plan_name, $action);
        }    

        return redirect()->route('admin.subscriberChangePlan',['id' => $user_id])->with('success','Subscription '.$action.' successfully.');
    }

	
}
