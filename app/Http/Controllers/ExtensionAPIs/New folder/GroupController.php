<?php

namespace App\Http\Controllers\ExtensionAPIs;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\User;
use App\Linked_fb_group;
use App\Language;
use App\Autoresponder_list;
use App\Auto_approve_setting;
use App\Fb_group_setting;
use App\Autoresponder;

class GroupController extends Controller
{
    //
    public function __construct(){
        auth()->setDefaultDriver('api');
    }

    public function updateProfile(Request $request)
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

        $validator = Validator::make($request->all(), [
           'name' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'msg' => $validator->errors()->first()
            ]);
        }

        $user_id = auth()->user()->id;

        User::where('id',$user_id)
        ->where('status','1')->update([
            "name" => $request->input('name')
        ]);

        return response()->json([
            "status" => 200,
            "msg" => "Profile updated."
        ]); 
    }

    public function getAutoresponderCredentails(Request $request)
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
        $validator = Validator::make($request->all(), [
           'autoresponderId' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'msg' => $validator->errors()->first()
            ]);
        }

        $autoresponderId    = $request->input('autoresponderId');
       
        $user_id = auth()->user()->id;

        $autoresonder_list_data = Autoresponder_list::find($autoresponderId);
        $autoresonder_list_data->makeHidden(['updated_at']);
        $autoresponder_data = Autoresponder::where('userId',$user_id)
                                            ->where('autoresponder_list_id',$autoresponderId)
                                            ->get()->first();
        unset($autoresponder_data->updated_at);
        
       
        
        if(!empty($autoresponder_data)){
            
            $autoresponder_data->api_url =  $autoresponder_data->field_one_value;
        
            $autoresponder_data->api_key =  $autoresponder_data->field_two_value;
          
            $autoresponder_data->app_path =  $autoresponder_data->field_three_value;
        
            $hotProspectorGroups = [];
            if($autoresponder_data->autoresponder_list_id == 14){ ///hot prospector
              
                $data_array = [];
                $data_array['field_three_value'] = $autoresponder_data->field_three_value;
                $data_array['field_two_value'] = $autoresponder_data->field_two_value;
                $hotProspectorGroups = getGroupsOfHotProspectorAutoresponder($data_array);
                        
    
                return response()->json([
                    'status' => 200,
                    'data' => $autoresponder_data,
                    "autoresponder" => $autoresonder_list_data,
                    "hotProspectorGroups" => $hotProspectorGroups
                ]);
            }else if($autoresponder_data->autoresponder_list_id == 15){ // drip
                $dripTags = getTagsOfDripAutoresponder($autoresponder_data->field_three_value,$autoresponder_data->field_two_value);
                return response()->json([
                    'status' => 200,
                    'data' => $autoresponder_data,
                    "autoresponder" => $autoresonder_list_data,
                    "dripTags" => $dripTags
                ]);
            }else{
                return response()->json([
                    'status' => 200,
                    'data' => $autoresponder_data,
                    "autoresponder" => $autoresonder_list_data
                ]);
            }
        }else{
            return response()->json([
                'status' => 404,
                'msg' => "Not found",
                "autoresponder" => $autoresonder_list_data
            ]);
        }
    }

    public function updateAutoresponder(Request $request)
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
        $validator = Validator::make($request->all(), [
           'group_set_id'   => 'required',
          // 'field_one_value' => 'required',
        //   'field_two_value'  => 'required',
          // 'field_three_value' => 'required',
           'type' => 'required',
           'id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'msg' => $validator->errors()->first()
            ]);
        } 

        $url = $key = $app_path = "";
        if($request->has('field_one_value')){
            $url        = trim($request->input('field_one_value'));    
        }
        if($request->has('field_two_value')){
            $key        = trim($request->input('field_two_value'));    
        }
        if($request->has('field_three_value')){
            $app_path        = trim($request->input('field_three_value'));    
        }

        if($request->has('url')){
            $url        = trim($request->input('url'));    
        }
        if($request->has('key')){
            $key        = trim($request->input('key'));    
        }
        if($request->has('app_path')){
            $app_path        = trim($request->input('app_path'));    
        }
        


        $type       = trim($request->input('type'));
        $user_id    = auth()->user()->id;

        //$verifyAutoresponderCredentials = true;

        $postCredentials = ['field_one_value'=>$url, 'field_two_value'=> $key , 'field_three_value'=>    $app_path, 'type'=>$type];


        if(verifyAutoresponderCredentials($postCredentials))
        {
            $fieldFour = null;
            if($request->has('field_four')){
                $fieldFour = $request->input('field_four');
            }

            if($request->has('new_autoresponder') && $request->input('new_autoresponder') == 1 )
            {
                $responder = new Autoresponder;
                $responder->autoresponder_list_id = $request->input('id');
                $responder->userId = $user_id;
                $responder->field_one_value = $url;
                $responder->field_two_value = $key;
                $responder->field_three_value = $app_path;
                $responder->field_four = $fieldFour;
                $responder->save();
                $addedId = $responder->id;

                
                if($request->input('id') == 6){ //// active campaign
                    return response()->json([
                        'status'    =>  200,
                        'msg'       =>  'Credentials verified and added.',
                        'autoresponder_id'  =>  $addedId, 
                        'showactivecampaigntag' =>  1,
                        'action'    =>  'added'
                    ]);    
                }else if($request->input('id') == 14){ /// hot prospector
                        $data_array['field_three_value'] = $app_path;
                        $data_array['field_two_value'] = $key;
                        $hotProspectorGroups = getGroupsOfHotProspectorAutoresponder($data_array);
                        return response()->json([
                            'status'    =>  200,
                            'msg'       =>  'Credentials verified and added.',
                            'autoresponder_id'  =>  $addedId, 
                            'showactivecampaigntag' =>  0,
                            'hotProspectorGroups' => $hotProspectorGroups,
                            'action'    =>  'added'
                        ]); 
                }else if($request->input('id') == 15){/// drip
                        $dripTags =  getTagsOfDripAutoresponder($app_path,$key);
                        return response()->json([
                            'status'    =>  200,
                            'msg'       =>  'Credentials verified and added.',
                            'autoresponder_id'  =>  $addedId,
                            'showactivecampaigntag' =>  0,
                            'dripTags' => $dripTags,
                            'action'    =>  'added'
                        ]); 
                }else{
                    return response()->json([
                        'status'    =>  200,
                        'msg'       =>  'Credentials verified and added.',
                        'autoresponder_id'  =>  $addedId, 
                        'showactivecampaigntag' =>  0,
                        'action'    =>  'added'
                    ]);
                }
            }else{

                   if($request->has('multiTagData')){

                   Autoresponder::where("id",$request->input('id'))->update([
                        "field_one_value" => $url,
                        "field_two_value" => $key,
                        "field_three_value" => $app_path,
                        "tagid" => $request->input('multiTagData'),
                        "tag_name" => $request->input('tagname')
                    ]);

                }else if($request->has('tagid') && $request->input('tagid') > 0 && $request->has('tagname')){   
                    Autoresponder::where("id",$request->input('id'))->update([
                        "field_one_value" => $url,
                        "field_two_value" => $key,
                        "field_three_value" => $app_path,
                        "tagid" => $request->input('tagid'),
                        "tag_name" => $request->input('tagname')
                    ]);
                } else{
                    $tempUrl = addslashes($url);
                    Autoresponder::where("id",$request->input('id'))->update([
                        "field_one_value" => $tempUrl,
                        "field_two_value" => $key,
                        "field_three_value" => $app_path,
                        "tagid" => 0,
                        "tag_name" => null,
                        "field_four" => $fieldFour
                    ]); 
                }
                $autoresponder_type_data = DB::table("autoresponder_list")
                                        ->where("responder_key",$request->input('type'))
                                        ->get()->first();
                $autoresponderId = $autoresponder_type_data->id;

                return response()->json([
                    'status'    =>  200,
                    'msg'       =>  "Updated",
                    'action'    =>  "updated",
                ]);             
            }
        }else{
            return response()->json([
                'status'    =>  404,
                'msg'       =>  "Invalid"
            ]);
        }   
    }
}
