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
        $roles = config('paagez.models.roles')::where('guard_name','web')->get()->pluck('name')->toArray();
        if(!\Auth::user())
        {
            return redirect()->route(config('paagez.route_prefix').'.login');
        }
        if(!\Auth::user()->hasRole($roles))
        {
            return redirect()->route(config('paagez.route_prefix').'.login');
        }
        if($request->query('notification'))
        {
            $notification_id = $request->query('notification');
            $notification = \Auth::user()->notifications()->find($notification_id);
            if ($notification && $notification->unread()) {
                $notification->markAsRead();
            }
        }
        return $next($request);
    }
}
