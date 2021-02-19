<?php

namespace App\Modules\Reseller\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Reseller\Models\Reseller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\User;
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

        $reseller_id = Auth::guard('reseller')->user()->id;

        if($request->has('email')){
            $email = $request->input('email');
            //$users = User::where('reseller_id',$reseller_id)->where('email','like','%'.$email.'%')->get();
            $users = DB::table('users')
            ->leftJoin('subscriptions','subscriptions.id','=','users.subscription_id')
            ->leftJoin('plans','plans.id','=','subscriptions.plan_id')
            ->where('users.reseller_id',$reseller_id)
            ->where('users.email','like','%'.$email.'%')
            ->select('users.*','plans.name as plan_name')
            ->get();
        }else{
            //$users = User::where('reseller_id',$reseller_id)->get();
            $users = DB::table('users')
            ->leftJoin('subscriptions','subscriptions.id','=','users.subscription_id')
            ->leftJoin('plans','plans.id','=','subscriptions.plan_id')
            ->where('users.reseller_id',$reseller_id)
            ->select('users.*','plans.name as plan_name')
            ->get();
        }
        
        return view("Reseller::subscribers.subscribers",compact('users'));
    }

    public function subscriberDetails($id)
    {
        $reseller_id = Auth::guard('reseller')->user()->id;
        $exist = User::where('id',$id)->where('reseller_id',$reseller_id)->count();
        if($exist <= 0){
            echo "Invalid User Id";
            die();
        }

        $user = User::find($id);
        
        $groups = DB::table('users')
                ->join('linked_fb_groups', 'users.id', '=', 'linked_fb_groups.userId')
                ->leftJoin('fb_group_settings', 'linked_fb_groups.id', '=', 'fb_group_settings.group_id')
                ->leftJoin('autoresponder_list', 'autoresponder_list.id', '=', 'fb_group_settings.autoresponder_id')
                ->where('users.id',$id)
                ->select('linked_fb_groups.id as group_id','linked_fb_groups.group_name','fb_group_settings.autoresponder_id','fb_group_settings.google_sheet_url','autoresponder_list.responder_type')
                ->get();

        $planData = DB::table('users')
        ->join('subscriptions','users.subscription_id','=','subscriptions.id')
        ->join('plans','subscriptions.plan_id','=','plans.id')
        ->where('users.id',$id)
        ->select('users.subscription_id','subscriptions.*','plans.name as plan_name')
        ->get();

        return view("Reseller::subscribers.view_subscriber",compact('user','groups','planData'));
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
        $reseller_id = Auth::guard('reseller')->user()->id;
        $reseller_status = Auth::guard('reseller')->user()->status;
        $error_msg = "";
        if($reseller_status == '0')
        {
            $error_msg = "Your account is inactive. Please activate your account to add new subscribers.";
            return view('Reseller::subscribers.add_subscriber',compact('error_msg'));
        }

        $active_gateway = PaymentGateway::where('reseller_id',$reseller_id)
        ->where('status',1)->get('id');

        if($active_gateway->isEmpty())
        {
            $error_msg = "Your account doesn't have any active payment mode. Please add new or activate existing payment mode.";
            return view('Reseller::subscribers.add_subscriber',compact('error_msg'));
        }

        $plans = Plan::where('status',1)->where('payment_gateway_id',$active_gateway[0]->id)->get(['id','name']);

        if($plans->isEmpty())
        {
            $error_msg = "Your account doesn't have any active plan. Please add new or activate existing plan.";
            return view('Reseller::subscribers.add_subscriber',compact('error_msg'));
        }

        return view('Reseller::subscribers.add_subscriber',compact('error_msg','plans'));
    }

    public function StoreNewSubscriber(Request $request)
    {   
        $request->validate([
            "email" => "required|email|unique:users",
            "name"  => "required",
            "plan"  => "required"
        ]);

        $reseller_id = Auth::guard('reseller')->user()->id;
        $reseller_status = Auth::guard('reseller')->user()->status;
        $package_limit = Auth::guard('reseller')->user()->package_limit;
        if($reseller_status == '0'){
            return redirect()->back()->withInput($request->all())->with('error','Your account is not active. You cannot add subscribers.');
        }

        $resellers_subscribers = User::where('reseller_id',$reseller_id)->count();

        if($resellers_subscribers >= $package_limit ){
            return redirect()->back()->withInput($request->all())->with('error',"Your account's package limit reached, Contact administrator.");
        }
        $plan_details = DB::table('plans')
        ->join('payment_gateway_settings','payment_gateway_settings.id','=','plans.payment_gateway_id')
        ->where('payment_gateway_settings.reseller_id',$reseller_id)
        ->where('plans.status',1)
        ->where('plans.id',$request->input('plan'))
        ->select('payment_gateway_settings.payment_type','plans.type')
        ->get();

        $trial = 1;
        
        /*if($plan_details[0]->type == "monthly"){
            $expire_on = date("Y-m-d",strtotime('+1 month'));
        }elseif($plan_details[0]->type == "yearly"){
            $expire_on = date("Y-m-d",strtotime('+1 year'));
        }else{
            $expire_on = date("Y-m-d");
        }*/
        $expire_on = null;
        if($request->has('expired_on') && $request->input('expired_on') != ''){
            $expire_on = $request->input('expired_on');
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
        $user->reseller_id = $reseller_id;
        $user->status = "1";
        $user->save();

        $unique_hash = generateRandomString(16);
        DB::table('user_settings')->insert([
            'user_id' => $user->id,
            'unique_hash' => $unique_hash
        ]);



        $to = $request->input('email');
        $subject = "Your Purchased Group Leads License!";
        $message = "Hi ".$request->input('name').",<br/><br/>
                    Thanks for signing up with Group Leads!<br/><br/>
                    To login to the extension, here is your License Key : {$license}<br/><br/>
                    To login to our web app, here are the details:<br/><br/>
                    Web app: <a href='https://app.groupleads.net/'>https://app.groupleads.net/</a><br/>
                    Email: ".$request->input('email')." <br/>
                    Password: {$password}<br/><br/>
                    Don’t forget to reset your password <a href='https://app.groupleads.net/forgetPassword'>https://app.groupleads.net/forgetPassword</a>
                    <br/><br/>Please, <a href='https://groupleads.net/contact'>click here</a> to read step by step instructions on how to properly configure group leads and start using it immediately <br/><br/>
                    Thank you<br/>Group Leads Team";
        sendGridApi($to, $subject, $message);
        return redirect()->route('reseller.subscribersList')->with('success','Subscriber added successfully.');
    }


    public function sendLicense($id)
    {
        //$this->isValidSubscriber($id);
        $reseller_id = Auth::guard('reseller')->user()->id;
        $exist = User::where('id',$id)->where('reseller_id',$reseller_id)->count();
        if($exist <= 0){
            return view('unauthorized_access',['backlink' => route('reseller.subscribersList')]);
            echo "Invalid Access";
            die();
        }
        $subscriber = User::find($id);
        //$data['license'] = $subscriber->license;

        $to = $subscriber->email;
        $subject = "Your Purchased Group Leads License!";
        $message = "Hey,<br/><br/>
                    Thank you for signing up for Group Leads!<br/><br/>
                    It seems you've not been able to receive your license due to one reason or the other.<br/><br/>
                    Maybe it landed in your spam folder.<br/><br/>
                    Here is your License Key : <b>".$subscriber->license."</b><br/><br/>
                    Please, follow the instructions <a href='https://docs.groupleads.net/'>here</a> to set up your Group Leads account and get started with generating leads from your Facebook groups.<br/><br/>
                    <a  href='https://docs.groupleads.net'>https://docs.groupleads.net</a><br/><br/>
                    If you have any question, just reply to this email and I will be happy to assist you further.<br/><br/>
                    Thank you<br/>Group Leads Team";
        sendGridApi($to, $subject, $message);
        //Mail::to($subscriber->email)->send(new SendLicense($data));
        return redirect()->route('reseller.subscribersList')->with('success','License sent successfully to '.$subscriber->email.'.');
    }


    public function isValidSubscriber($id)
    {
        /*$reseller_id = Auth::guard('reseller')->user()->id;
        $exist = User::where('id',$id)->where('reseller_id',$reseller_id)->count();
        if($exist <= 0){
            return view('unauthorized_access',['backlink' => route('reseller.subscribersList')]);
            echo "Invalid Access";
            die();
        }else{ 
            return true;
        }*/
    }

    public function isSubscriberAlreadyExist(Request $request){
        $email = $request->input('email');
        $user_exist = User::where('email',$email)->count();
        if($user_exist > 0){
            return json_encode(false);
        }else{
            return json_encode(true);
        }
    }

}
