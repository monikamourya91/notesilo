<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ResellerAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		if(Auth::guard('reseller')->check()){
		    if(Auth::guard('reseller')->user()->status == 0){
                if($request->route()->getName() != 'reseller.dashboard'){
                    return redirect()->route('reseller.dashboard');
                }
            }
			return $next($request);
		}else{
			return redirect()->route('reseller.loginForm');
		}        
    }
}
