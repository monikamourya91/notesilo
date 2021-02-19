<?php

namespace App\Modules\Reseller\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function loginForm()
    {
        if(Auth::guard('reseller')->check()){
            return redirect()->route("reseller.dashboard");
        }
        return view("Reseller::auth.login");
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
		
        if(Auth::guard('reseller')->attempt($userdata)) {
            Auth::guard('admin')->logout();
            return redirect()->route('reseller.dashboard');
        }else{
	        return redirect()->back()->with('invalid_login', 'Username & Password is Incorrect.');   
		}
    }

	public function logout()
    {
        Auth::guard('reseller')->logout();
		return redirect()->route('reseller.loginForm')->with('message', 'Logged Out Successfully.');
    }
}