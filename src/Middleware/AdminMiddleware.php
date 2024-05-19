<?php

namespace Monsterz\Paagez\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle(Request $request, Closure $next)
    {
        if(!\Auth::user())
        {
            return redirect()->route('paagez.login');
        }
        if(!\Auth::user()->hasRole(['admin','author']))
        {
            return redirect()->route('paagez.login');
        }
        return $next($request);
    }
}
