<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
	protected $guard = 'admin';
    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function loginForm()
    {
        if(Auth::guard('admin')->check()){
            return redirect()->route("admin.dashboard");
        }
        return view("Admin::auth.login");
    }
	
	public function authenticate(Request $request)
    {
		$validator = $request->validate([
            'email'     => 'required',
            'password'  => 'required'
        ]);
		
		$userdata = array(
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        );
		
        if(Auth::guard('admin')->attempt($userdata)) {
            Auth::guard('reseller')->logout();
            return redirect()->route('admin.dashboard');
        }else{
	        return redirect()->back()->with('invalid_login', 'Username & Password is Incorrect.');   
		}
    }

	public function logout()
    {
        Auth::guard('admin')->logout();
		return redirect()->route('admin.loginForm')->with('message', 'Logged Out Successfully.');
    }
}