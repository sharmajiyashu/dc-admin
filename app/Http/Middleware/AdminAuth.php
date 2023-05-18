<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Closure;

// use Closure;
use Carbon\Carbon;
use Illuminate\Foundation\Application;
use Symfony\Component\HttpFoundation\Cookie;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Session\TokenMismatchException;
use Auth;

class AdminAuth 
{
   
    public function handle(Request $request , Closure $next){
        if(!Auth::check()){
            return redirect()->route('admin.login')->witherror('login now');
        }else{
            // if(Auth::user()->type != 'admin'){
            //     return redirect('/')->witherror('login now');
            // }else{
                return $next($request);
            // }
        }
    }
}
