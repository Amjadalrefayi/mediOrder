<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check()){

            if(Auth::user()->type == 'App\Model\Admin'){
                return view('dashboard.dashboard');
            }

            else{

                return redirect('')->with('message', 'Access Denied as you are not adimn');
            }

        }
        else{
            return redirect('')->with('message', 'login in to access');

        }
        //return $next($request);
    }
}
