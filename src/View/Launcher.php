<?php

namespace Monsterz\Paagez\View;

use Illuminate\View\Component;

class Launcher extends Component
{

    public $launchers = [];

    public function __construct()
    {
        $this->getLaunchers();
        $this->sortLaunchers();
    }

    protected function getLaunchers()
    {
        $modules = config('paagez.models.module')::where('is_active',1)->get();
        foreach ($modules as $key => $module) {
            $classname = $module->namespace."\\Navigations\\Launcher";
            if(file_exists(base_path($classname.".php")))
            {
                if(class_exists($classname))
                {
                    $menu = new $classname;
                    foreach($menu->launchers as $item)
                    {
                        if(\Auth::user()->hasRole($item['roles']))
                        {
                            $this->launchers[] = $item;
                        }
                    }
                }
            }
        }
        $launchers = [
            [
                'order' => 1,
                'name' => 'app_configuration',
                'label' => __('paagez.app_configuration'),
                'icon' => "fa-solid fa-gear",
                'url' => route(config('paagez.route_prefix').".app.config"),
                'image' => '',
                'roles' => ['admin']
            ]
        ];
        foreach($launchers as $launcher)
        {
            $this->launchers[] = $launcher;
        }
    }

    protected function sortLaunchers()
    {
        $this->launchers = \collect($this->launchers)->sortBy('order')->map(function($item){
            $item['roles'] = implode("|",$item['roles']);
            return $item;
        });
    }

    public function render()
    {
        return view(config('paagez.theme').'::components.admin.launcher');
    }
}