<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Admin\Models\Admin;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegisterForm()
    {
        return view("Admin::register");
    }
	
	public function createAdmin(Request $request)
    {
		$request->validate([
			'name' 			=> 'required',
			'email' 		=> 'required|email',
			'password' 		=> 'required',
			'password_confirmation' => 'required',
		]);
		
		$new_admin = new Admin;
		$new_admin->name  = $request->input('name');
		$new_admin->email  = $request->input('email');
		$new_admin->password  = $hashed = Hash::make($request->input('password'));
		$new_admin->save();
		
		return redirect()->route('admin.loginForm')->with('message', 'Account Created.!!! Please login with your credentials.');
    }
}