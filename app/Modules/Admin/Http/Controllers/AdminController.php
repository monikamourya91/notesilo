<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Admin\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;

class AdminController extends Controller
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
        if(Auth::guard('admin')->check())
        {
            return redirect()->route('admin.dashboard');
        }else{
            return redirect()->route('admin.loginForm');
        }
    }

    public function dashboard()
    {
        return view("Admin::dashboard");
    }

    public function profile()
    {
        $loggedInUser = Auth::guard('admin')->user()->id;
        $adminData = Admin::find($loggedInUser);

        return view("Admin::profile", ['adminData' => $adminData]);
    }

    public function changePassword()
    {
        return view("Admin::changepassword");
    }

    public function updatePassword(Request $request)
    {
        $password = $request->input('password');
        $id       = $request->input('id');
        
        $admin = Admin::find($id);
        if($admin) {
            $admin->password = Hash::make($password);
            $admin->save();
        }
        return redirect()->route("admin.changePassword")->with('success','Password changed successfully.');
    }

    public function editProfile($id)
    {
        $admin = Admin::find($id);
        
        return view('Admin::editprofile',compact('admin'));
    }

   public function validateAdminEmailCheck(Request $request)
    {   

        $email = $request['email'];
        $user  = User::where('email',$email)->get()->count();
        $admin = Admin::where('email',$email)->get()->count();
        if($admin or $user){
            if($admin){
                $count = Admin::where('email',$email)->get()->count();
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

        $already = Admin::where('id','!=',$id)->where('email',$email)->get()->count();
        if(!$already){
            $admin = Admin::find($id);
            if($admin) {
                $admin->name  = $name;
                $admin->email = $email;
                $admin->save();
            }
            
            return redirect()->route('admin.editProfile',['id' => $id])->with('success','Profile updated successfully.');
        }else{
            return redirect('/editProfile/'.$id)->with('error','This email id is already exist'); 
        }

    }
}
