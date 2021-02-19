<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Reseller;
use App\User;


class ResellerController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
	 
	public function resellersList()
	{
		$resellers = Reseller::all();
		return view("Admin::resellers.resellers",compact('resellers'));
	}

	public function resellerDetails($id)
	{
		$reseller = Reseller::find($id);
		$active_subscribers = User::where('reseller_id',$id)->where('status','1')->count();
		return view("Admin::resellers.view_reseller",compact('reseller','active_subscribers'));
	}

	public function updateReseller(Request $request)
	{

		$request->validate([
			'name' => 'required',
			'email' => 'required|email|unique:resellers,email,'.$request->input('userId'),
		]);

		if($request->has('userId') && $request->input('userId') != "")
		{
			$reseller = Reseller::find($request->input('userId'));
			$reseller->name  = $request->input('name');
			//$reseller->email = $request->input('email');
			$reseller->save();
			return redirect()->back()->with('message','Profile updated Successfully.');
		}
	}
}
