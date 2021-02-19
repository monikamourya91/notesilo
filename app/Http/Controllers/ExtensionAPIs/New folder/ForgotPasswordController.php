<?php

namespace App\Http\Controllers\ExtensionAPIs;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendLicense;

class ForgotPasswordController extends Controller
{
    //
    public function __construct(){
        auth()->setDefaultDriver('api');
    }

    public function sendLicense(Request $request)
    {

        $validator = Validator::make($request->all(), [
           'email' => 'required|email'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'msg' => $validator->errors()->first()
            ]);
        }
        
        $userLanguage = $request->header('userlanguage','en');
        $subscriber_data = User::where('email',$request->input('email'))->get()->first();
        if(empty($subscriber_data))
        {
            return response()->json([
                'status' => 404,
                'msg' => $this->languageArray[$userLanguage]['api_message']['reset_license']['not_register']
            ]);
        }
        sendResetLinkMail($subscriber_data->name,$subscriber_data->license,$request->input('email'));
        $return['status'] = "200";
        $return['msg'] = $this->languageArray[$userLanguage]['api_message']['reset_license']['send_mail'];
        return response()->json($return);
    }

}
