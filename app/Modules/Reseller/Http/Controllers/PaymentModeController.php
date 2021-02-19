<?php

namespace App\Modules\Reseller\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Reseller\Models\Reseller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Payment_gateway_setting as PaymentGateway;

class PaymentModeController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
	 
    public function paymentModesList()
    {

        $reseller_id = Auth::guard('reseller')->user()->id;
        $reseller_hash = Auth::guard('reseller')->user()->reseller_hash;
        $gateways_linked_with_users = DB::table('payment_gateway_settings')
        ->join('plans','plans.payment_gateway_id','=','payment_gateway_settings.id')
        ->join('subscriptions','subscriptions.plan_id','=','plans.id')
        ->join('users','users.subscription_id','=','subscriptions.id')
        ->where('payment_gateway_settings.reseller_id',$reseller_id)
        ->select(DB::raw('payment_gateway_settings.id,count(users.id) as users'))
        ->groupBy('payment_gateway_settings.id')
        ->get();

        $GatewaysAlreadyInUse = array();
        foreach($gateways_linked_with_users as $existing){
            if($existing->users > 0){
                $GatewaysAlreadyInUse[] = $existing->id;
            }
        }

        $payment_modes = PaymentGateway::where('reseller_id',$reseller_id)->get();
        return view("Reseller::payment_modes.paymet_modes",compact('payment_modes','GatewaysAlreadyInUse','reseller_hash'));
    }

    public function AddNew()
    {   
        $users = array();
        return view("Reseller::payment_modes.add_payment_mode",compact('users'));
    }

    public function StoreNewPaymentMode(Request $request)
    {  
        $request->validate([
            'payment_mode' => 'required',
            'vendor_id' => 'required',
            'api_key' => 'required',
        ]);
        $reseller_id = Auth::guard('reseller')->user()->id;

        $count = PaymentGateway::where('reseller_id',$reseller_id)->where('payment_type',$request->input('payment_mode'))->count();

        if($count > 0){
            return redirect()->back()->withInput($request->all())->with('error',"Payment mode (".$request->input('payment_mode').") already exists.");
        }
        
        /* validate credentials with paddle api */
        if($request->input('payment_mode') == 'paddle'){
            $post_fields['vendor_id']        = $request->input('vendor_id');
            $post_fields['vendor_auth_code'] = $request->input('api_key');

            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://vendors.paddle.com/api/2.0/product/get_products",
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
                return redirect()->back()->withInput($request->all())->with('error',"Invalid Vendor ID or Api Key");
            }
        }
        /* validate credentials with paddle api end */


        $credentials['vendor_id'] = $request->input('vendor_id');
        $credentials['api_key'] = $request->input('api_key');

        $PaymentGateway = new PaymentGateway;
        $PaymentGateway->reseller_id = $reseller_id;
        $PaymentGateway->payment_type = $request->input('payment_mode');
        $PaymentGateway->status = '0';
        $PaymentGateway->credentials = json_encode($credentials);
        $PaymentGateway->save();

        $reseller_hash = Auth::guard('reseller')->user()->reseller_hash;
        $webhook_url =  url('api/reseller-paddle-notification/'.$reseller_hash);
        return redirect()->route('reseller.paymentModesList')->with('success',"Payment mode added successfully.<br>Webhook URL: ".$webhook_url);
    }

    public function DeletePaymentMode($id)
    {
        $reseller_id = Auth::guard('reseller')->user()->id;
        $exist = PaymentGateway::where('id',$id)->where('reseller_id',$reseller_id)->count();
        if($exist <= 0){
            return view('unauthorized_access',['backlink' => route('reseller.paymentModesList')]);
            echo "Invalid Access";
            die();
        }
        $PaymentGateway = PaymentGateway::find($id);
        $PaymentGateway->delete();
        return redirect()->route('reseller.paymentModesList')->with('success',"Payment mode deleted successfully.");
    }


    public function EditPaymentMode($id)
    {
        $reseller_id = Auth::guard('reseller')->user()->id;
        $exist = PaymentGateway::where('id',$id)->where('reseller_id',$reseller_id)->count();
        if($exist <= 0){
            return view('unauthorized_access',['backlink' => route('reseller.paymentModesList')]);
            echo "Invalid Access";
            die();
        }


        $isGatewayPlansInUse = DB::table('payment_gateway_settings')
        ->join('plans','plans.payment_gateway_id','=','payment_gateway_settings.id')
        ->join('subscriptions','subscriptions.plan_id','=','plans.id')
        ->join('users','users.subscription_id','=','subscriptions.id')
        ->where('payment_gateway_settings.id',$id)
        ->count();

        $editable = true;
        if($isGatewayPlansInUse > 0){
            $editable = false;
        }

        $PaymentGateway = PaymentGateway::find($id);
        return view("Reseller::payment_modes.edit_payment_mode",compact('PaymentGateway','editable'));
    }

    public function UpdatePaymentMode(Request $request)
    {
        $request->validate([
            'payment_mode' => 'required',
            'vendor_id' => 'required',
            'api_key' => 'required',
            'status' => 'required',
        ]);

        if($request->has('payment_mode_id') && $request->input('payment_mode_id') != ""){

            $reseller_id = Auth::guard('reseller')->user()->id;

            $exist = PaymentGateway::where('id',$request->input('payment_mode_id'))->where('reseller_id',$reseller_id)->count();
            if($exist <= 0){
                return view('unauthorized_access',['backlink' => route('reseller.paymentModesList')]);
                echo "Invalid Access";
                die();
            }
            $count = PaymentGateway::where('reseller_id',$reseller_id)->where('payment_type',$request->input('payment_mode'))->where('id','!=',$request->input('payment_mode_id'))->count();

            if($count > 0){
                return redirect()->back()->withInput($request->all())->with('error',"Payment mode (".$request->input('payment_mode').") already exists.");
            }

            /* validate credentials with paddle api */
            if($request->input('payment_mode') == 'paddle'){
                $post_fields['vendor_id']        = $request->input('vendor_id');
                $post_fields['vendor_auth_code'] = $request->input('api_key');

                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://vendors.paddle.com/api/2.0/product/get_products",
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
                    return redirect()->back()->withInput($request->all())->with('error',"Invalid Vendor ID or Api Key");
                }
            }
            /* validate credentials with paddle api end */

            $credentials['vendor_id'] = $request->input('vendor_id');
            $credentials['api_key'] = $request->input('api_key');

            $PaymentGateway = PaymentGateway::find($request->input('payment_mode_id'));
            //$PaymentGateway->reseller_id = $reseller_id;
            $PaymentGateway->payment_type = $request->input('payment_mode');
            $PaymentGateway->status = $request->input('status');
            $PaymentGateway->credentials = json_encode($credentials);
            $PaymentGateway->save();

            if($request->input('status') == '1')
            {
                PaymentGateway::where('id','!=',$request->input('payment_mode_id'))->where('reseller_id',$reseller_id)->update([
                    'status' => 0
                ]);
            }

            return redirect()->back()->withInput($request->all())->with('success',"Payment mode updated successfully.");
        }
    }

}
