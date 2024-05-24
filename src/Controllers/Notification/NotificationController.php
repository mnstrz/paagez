<?php

namespace Monsterz\Paagez\Controllers\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
       $now = \Carbon\Carbon::now();
       $notifications = \Auth::user()->notifications()->paginate(10)->through(function($item)  use($now)
                       {
                            $url = (isset($item->data['url'])) ? $item->data['url'] : '';
                            if($url)
                            {
                                $urlComponents = parse_url($url);
                                if(isset($urlComponents['query']))
                                {
                                    parse_str($urlComponents['query'], $queryParams);
                                    $queryParams['notification'] = $item->id;
                                    $newQueryString = http_build_query($queryParams);
                                    $url = $urlComponents['path'] . '?' . $newQueryString;
                                }else{
                                    $url = $url."?notification=".$item->id;
                                }
                            }
                            $item->url = $url;
                            $item->subject = (isset($item->data['subject'])) ? $item->data['subject'] : '';
                            $item->message = (isset($item->data['message'])) ? $item->data['message'] : '';
                            $date = \Carbon\Carbon::parse($item->created_at);
                            if ($date->diffInDays($now) > 7) {
                                $date = $date->translatedFormat('d M Y');
                            } else {
                                $date = $date->diffForHumans();
                            }
                            $item->date = $date;
                            return $item;
                       });
       return view(config('paagez.theme').'::notification.index',compact('notifications'));
    }

    public function readAll()
    {
        \Auth::user()->unreadNotifications->markAsRead();
        return redirect()->route(config("paagez.route_prefix").".notifications.index")->with(["success" => __("All notifications mark as read")]);
    }
}