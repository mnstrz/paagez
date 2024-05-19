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
    }

    public function getNavs()
    {
        $modules = config('paagez.models.module')::all();
        foreach ($modules as $key => $module) {
            $classname = $module->namespace."\\Navigations\\Nav";
            if(file_exists(base_path($classname.".php")))
            {
                if(class_exists($classname))
                {
                    $nav = new $classname;
                    foreach($nav->navs as $item)
                    {
                        $this->navs[] = $item;
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

    public function render()
    {
        return view(config('paagez.theme').'::components.admin.navbar');
    }
}