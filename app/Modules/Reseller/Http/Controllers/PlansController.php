<?php

namespace App\Modules\Reseller\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Reseller\Models\Reseller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Payment_gateway_setting as PaymentGateway;
use App\Plan;

class PlansController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
	 
    public function plansList($gateway_id="")
    {
        $reseller_id = Auth::guard('reseller')->user()->id;
        if($gateway_id == "")
        {
            $gateway_id = PaymentGateway::where('reseller_id',$reseller_id)->where('status',1)->get('id')->first();
            if($gateway_id)
            {
                $gateway_id = $gateway_id->id;    
            }
        }
        
        if($gateway_id != ""){
            $exist = PaymentGateway::where('id',$gateway_id)->where('reseller_id',$reseller_id)->count();
            if($exist <= 0){
                return view('unauthorized_access',['backlink' => route('reseller.plansList')]);
                echo "Invalid Access";
                die();
            }
        }
        
        $PaymentGateway = PaymentGateway::where('reseller_id',$reseller_id)->get();
        //$plans = Plan::where('payment_gateway_id',$gateway_id)->get();
        $plans = DB::table('plans')
        ->join('payment_gateway_settings','plans.payment_gateway_id','=','payment_gateway_settings.id')
        ->where('plans.payment_gateway_id',$gateway_id)
        ->select('plans.*','payment_gateway_settings.payment_type')
        ->get();

        $plans_linked_with_users = DB::table('plans')
        ->Join('subscriptions','subscriptions.plan_id','=','plans.id')
        ->Join('users','users.subscription_id','=','subscriptions.id')
        ->where('users.reseller_id',$reseller_id)
        ->select(DB::raw('plans.id,count(users.id) as users'))
        ->groupBy('plans.id')
        ->get();
        $plansAlreadyInUse = array();
        foreach($plans_linked_with_users as $existing){
            if($existing->users > 0){
                $plansAlreadyInUse[] = $existing->id;
            }
        }
        return view("Reseller::plans.plans",compact('PaymentGateway','gateway_id','plans','plansAlreadyInUse'));
    }

    public function AddNewPlan()
    {   
        $reseller_id = Auth::guard('reseller')->user()->id;
        $PaymentGateway = PaymentGateway::where('reseller_id',$reseller_id)
        ->where('status',1)->get();
        return view("Reseller::plans.add_reseller_plan",compact('PaymentGateway'));
    }

    public function StoreNewPlan(Request $request)
    {  
        $request->validate([
            'payment_mode' => 'required',
            'plan_id' => 'required',
        ]);

        $reseller_id = Auth::guard('reseller')->user()->id;

        $payment_mode_data = DB::table('payment_gateway_settings')->where('id',$request->input('payment_mode'))->get(['payment_type','credentials'])->first();

        $name = $type = $fb_groups = $price = '';
        $trial = 0;
        if($payment_mode_data->payment_type == 'paddle'){

            $credentials = json_decode($payment_mode_data->credentials,true); 
            $post_fields['vendor_id']        = $credentials['vendor_id'];
            $post_fields['vendor_auth_code'] = $credentials['api_key'];
            $post_fields['plan']             = $request->input('plan_id');

            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://vendors.paddle.com/api/2.0/subscription/plans",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => $post_fields,
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $response = json_decode($response,true);

            if(!$response['success']){
                return redirect()->back()->withInput($request->all())->with('error',"Invalid Plan ID");
            }else{
                // if plan is not monthly or yearly then redirect back
                if(!(($response['response']['0']['billing_type'] == 'month' || 
                    $response['response']['0']['billing_type'] == 'year') && 
                    $response['response']['0']['billing_period'] == '1')){
                    return redirect()->back()->withInput($request->all())->with('error',"Only monthly and yearly plans are allowed.");
                }
                $name = $response['response'][0]['name'];
                if($response['response'][0]['billing_type'] == 'month'){
                    $type = "monthly";
                    $fb_groups = 2;
                }else if($response['response'][0]['billing_type'] == 'year'){
                    $type = "yearly";
                    $fb_groups = 4;
                }
                $price = $response['response'][0]['recurring_price']['USD'];
                if($response['response'][0]['trial_days'] > 0){
                    $trial = 1;
                }
            }
        }

        $live_plan_id_already_exist = Plan::where('live_plan_id',$request->input('plan_id'))->where('payment_gateway_id',$request->input('payment_mode'))->count();
        if($live_plan_id_already_exist > 0){
            return redirect()->back()->withInput($request->all())->with('error',"Plan id (".$request->input('plan_id').") already exists with selcted payment mode.");
        }

        $name_already_exist = Plan::where('name',$name)->where('payment_gateway_id',$request->input('payment_mode'))->count();
        if($name_already_exist > 0){
            return redirect()->back()->withInput($request->all())->with('error',"Plan name (".$name.") already exists with selcted payment mode.");
        }

        $Plan = new Plan;
        $Plan->level = '2';
        $Plan->payment_gateway_id = $request->input('payment_mode');
        $Plan->name = $name;
        $Plan->price = $price;
        $Plan->live_plan_id = $request->input('plan_id');
        $Plan->type = $type;
        $Plan->trial  = $trial;
        $Plan->fb_groups = $fb_groups;
        $Plan->save();

        return redirect()->route('reseller.AddNewPlan')->with('success',"Plan added successfully.");
    }

    public function DeletePlan($id)
    {
        $reseller_id = Auth::guard('reseller')->user()->id;
        $exist = DB::table('plans')
        ->join('payment_gateway_settings','payment_gateway_settings.id','=','plans.payment_gateway_id')
        ->where('payment_gateway_settings.reseller_id',$reseller_id)
        ->where('plans.id',$id)
        ->count();
        if($exist <= 0){
            return view('unauthorized_access',['backlink' => route('reseller.plansList')]);
            echo "Invalid Access";
            die();
        }
        $Plan = Plan::find($id);
        $Plan->delete();
        return redirect()->route('reseller.plansList')->with('success',"Plan deleted successfully.");
    }

    public function EditPlan($id)
    {   
        $reseller_id = Auth::guard('reseller')->user()->id;

        $exist = DB::table('plans')
        ->join('payment_gateway_settings','payment_gateway_settings.id','=','plans.payment_gateway_id')
        ->where('payment_gateway_settings.reseller_id',$reseller_id)
        ->where('plans.id',$id)
        ->count();
        if($exist <= 0){
            return view('unauthorized_access',['backlink' => route('reseller.plansList')]);
            echo "Invalid Access";
            die();
        }

        $isPlanAlreadyInUse = DB::table('plans')
        ->join('subscriptions','subscriptions.plan_id','=','plans.id')
        ->join('users','users.subscription_id','=','subscriptions.id')
        ->where('plans.id',$id)
        ->count();

        $editable = true;
        if($isPlanAlreadyInUse > 0){
            $editable = false;
        }

        $plan = Plan::find($id);
        $PaymentGateway = PaymentGateway::where('reseller_id',$reseller_id)->get();
        return view("Reseller::plans.edit_reseller_plan",compact('PaymentGateway','plan','editable'));
    }

    public function UpdatePlan(Request $request)
    {
        $request->validate([
            'payment_mode' => 'required',
            'plan_id' => 'required',
        ]);

        if($request->has('id') && $request->input('id') != ""){

            $reseller_id = Auth::guard('reseller')->user()->id;
            
            $exist = DB::table('plans')
            ->join('payment_gateway_settings','payment_gateway_settings.id','=','plans.payment_gateway_id')
            ->where('payment_gateway_settings.reseller_id',$reseller_id)
            ->where('plans.id',$request->input('id'))
            ->count();
            if($exist <= 0){
                return view('unauthorized_access',['backlink' => route('reseller.plansList')]);
                echo "Invalid Access";
                die();
            }

            $payment_mode_data = DB::table('payment_gateway_settings')->where('id',$request->input('payment_mode'))->get(['payment_type','credentials'])->first();

            $name = $type = $fb_groups = $price = '';
            $trial = 0;
            if($payment_mode_data->payment_type == 'paddle'){

                $credentials = json_decode($payment_mode_data->credentials,true); 
                $post_fields['vendor_id']        = $credentials['vendor_id'];
                $post_fields['vendor_auth_code'] = $credentials['api_key'];
                $post_fields['plan']             = $request->input('plan_id');

                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://vendors.paddle.com/api/2.0/subscription/plans",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS => $post_fields,
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $response = json_decode($response,true);
                if(!$response['success']){
                    return redirect()->back()->withInput($request->all())->with('error',"Invalid Plan ID");
                }else{
                     // if plan is not monthly or yearly then redirect back
                    if(!(($response['response']['0']['billing_type'] == 'month' || 
                        $response['response']['0']['billing_type'] == 'year') && 
                        $response['response']['0']['billing_period'] == '1')){
                        return redirect()->back()->withInput($request->all())->with('error',"Only monthly and yearly plans are allowed.");
                    }
                    $name = $response['response'][0]['name'];
                    if($response['response'][0]['billing_type'] == 'month'){
                        $type = "monthly";
                        $fb_groups = 2;
                    }else if($response['response'][0]['billing_type'] == 'year'){
                        $type = "yearly";
                        $fb_groups = 4;
                    }
                    $price = $response['response'][0]['recurring_price']['USD'];
                    if($response['response'][0]['trial_days'] > 0){
                        $trial = 1;
                    }
                }
            }

            $name_already_exist = Plan::where('name',$name)
            ->where('payment_gateway_id',$request->input('payment_mode'))
            ->where('id','!=',$request->input('id'))
            ->count();
            if($name_already_exist > 0){
                return redirect()->back()->withInput($request->all())->with('error',"Plan name (".$request->input('name').") already exists with selcted payment mode.");
            }

            /*allow max 2 active plans per payment mode for a reseller*/
            $max_allowed = 4;
            if($request->input('status') == '1')
            {
                $check_active_count = DB::table('plans')
                ->join('payment_gateway_settings','payment_gateway_settings.id','=','plans.payment_gateway_id')
                ->where('plans.status',$request->input('status'))
                ->where('plans.payment_gateway_id',$request->input('payment_mode'))
                ->where('plans.id','!=',$request->input('id'))
                ->where('payment_gateway_settings.reseller_id',$reseller_id)
                ->count();
                if($check_active_count > ($max_allowed - 1)){
                    return redirect()->back()->withInput($request->all())->with('error',"Only {$max_allowed} active plans are alllowed. To activate this plan, please inactive other plan first.");
                }
            }

            $Plan = Plan::find($request->input('id'));
            $Plan->payment_gateway_id = $request->input('payment_mode');
            $Plan->name = $name;
            $Plan->price = $price;
            $Plan->live_plan_id = $request->input('plan_id');
            $Plan->type  = $type;
            $Plan->trial  = $trial;
            $Plan->status = $request->input('status');
            $Plan->save();

            return redirect()->route('reseller.plansList')->withInput($request->all())->with('success',"Plan updated successfully.");
        }
    }

    /*public function livePlanDetails(Request $request){
        $payment_mode_id    = $request->input('payment_mode');
        $plan_id            = $request->input('plan_id');

        $payment_mode_data = DB::table('payment_gateway_settings')->where('id',$payment_mode_id)->get(['payment_type','credentials'])->first();

        if($payment_mode_data->payment_type == 'paddle'){

            $credentials = json_decode($payment_mode_data->credentials,true); 
            $post_fields['vendor_id']        = $credentials['vendor_id'];
            $post_fields['vendor_auth_code'] = $credentials['api_key'];
            $post_fields['plan']             = $plan_id;

            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://vendors.paddle.com/api/2.0/subscription/plans",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => $post_fields,
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            echo $response;
        }
        die();
    }*/

}
