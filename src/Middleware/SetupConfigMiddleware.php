<?php

namespace Monsterz\Paagez\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetupConfigMiddleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle(Request $request, Closure $next)
    {
        if(!\Schema::hasTable(config('paagez.db_prefix').'config'))
        {
            \Artisan::call('migrate');
        }
        if(!config('paagez.models.config')::where('name','app_name')->whereNotNull('value')->first()){
            return redirect()->route(config('paagez.route_prefix').'.setup');
        }
        if(!config('paagez.models.user')::first()){
            return redirect()->route(config('paagez.route_prefix').'.setup');
        }
        return $next($request);
    }
}
