<?php

namespace Monsterz\Paagez\View;

use Illuminate\View\Component;

class AdminNavbar extends Component
{

    public $notifications = [];

    public $navs = [];

    public $update = false;

    public function __construct()
    {
        $this->getNavs();
        $this->sortNavs();
        $this->getUpdateModule();
        $this->getNotifications();
    }

    public function getNavs()
    {
        $modules = config('paagez.models.module')::where('is_active',1)->get();
        foreach ($modules as $key => $module) {
            $classname = $module->namespace."\\Navigations\\Nav";
            if(file_exists(base_path($classname.".php")))
            {
                if(class_exists($classname))
                {
                    $nav = new $classname;
                    foreach($nav->navs as $item)
                    {
                        if(\Auth::user()->hasRole($item['roles']))
                        {
                            $this->navs[] = $item;
                        }
                    }
                }
            }
        }
    }

    public function sortNavs()
    {
        $childs = \collect($this->navs)->filter(function ($item) {
                        $parent = (isset($item['parent'])) ? $item['parent'] : null;
                        return !is_null($parent);
                    })->sortBy('order')->map(function($item)
                    {
                        $item['active'] = (url()->current() == $item['url'] || "/".request()->path == $item['url']) ? true : false;
                        $item['roles'] = implode("|",$item['roles']);
                        return $item;
                    });
        $this->navs = \collect($this->navs)->filter(function ($item) {
                        $parent = (isset($item['parent'])) ? $item['parent'] : null;
                        return is_null($parent);
                    })->sortBy('order')->map(function($item) use($childs)
                    {
                        $item['child'] = $childs->where('parent',$item['name'])->sortBy('order');
                        $active = false;
                        if(url()->current() == $item['url'] || "/".request()->path == $item['url'])
                        {
                            $active = true;
                        }
                        if($childs->where('parent',$item['name'])->where('active',true)->first()){
                            $active = true;
                        }
                        $item['active'] = $active;
                        $item['roles'] = implode("|",$item['roles']);
                        return $item;
                    })->toArray();
    }

    public function getUpdateModule()
    {
        $updates_app = [];
        $modules = glob(base_path('app/Modules/*/Module.php'));
        foreach ($modules as $key => $module) {
            try {
                $path = explode("/", $module);
                $class_name = array_slice($path, -2, 1, true);
                $class_name = array_values($class_name)[0];
                $class_name = '\\App\\Modules\\'.$class_name.'\\Module';
                if(class_exists($class_name))
                {
                    $module = new $class_name;
                    if($module->need_update)
                    {
                        array_push($updates_app,[
                            'name' => $module->name,
                            'title' => $module->title,
                            'version' => $module->version,
                            'latest_version' => $module->latest_version,
                            'namespace' => $module->namespace,
                            'can_install' => $module->can_install,
                            'error_messages' => $module->error_messages,
                            'warning_messages' => $module->warning_messages,
                        ]);
                        if(!$module->can_install)
                        {
                            $install = false;
                        }
                    }
                }
            } catch (\Exception $e) {
                continue;
            }
        }
        $this->update = (count($updates_app) > 0) ? true : false;
    }

    protected function getNotifications()
    {
        $now = \Carbon\Carbon::now();
        $notifications = \Auth::user()->unreadNotifications()->take(5)->get()->map(function($item) use($now)
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
                                    $date = \Carbon\Carbon::parse($item->created_at);
                                    if ($date->diffInDays($now) > 7) {
                                        $date = $date->translatedFormat('d M Y');
                                    } else {
                                        $date = $date->diffForHumans();
                                    }
                                    $item->date = $date;
                                    return $item;
                                });
        $this->notifications = $notifications;
    }

    public function render()
    {
        return view(config('paagez.theme').'::components.admin.navbar');
    }
}