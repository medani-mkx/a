<?php

namespace App\Http\Middleware;

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
    public function handle($request, Closure $next)
    {
        if($request->session()-) {
            return $next($request);
        }

        // Flash message
//        $request->session()->flash('alert-danger', 'Keine Berechtigung für diese Aktion!');
        
        return redirect()->route('/');
    }
}
