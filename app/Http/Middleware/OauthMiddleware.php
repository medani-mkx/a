<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class OauthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return redirect('/');
        }

        return $next($request);
        
//        dd($request->session());
//        if($request->session()-) {
//            return $next($request);
//        }

        // Flash message
//        $request->session()->flash('alert-danger', 'Keine Berechtigung für diese Aktion!');
        
        return redirect()->route('/');
    }
}
