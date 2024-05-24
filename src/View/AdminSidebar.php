<?php

namespace Monsterz\Paagez\View;

use Illuminate\View\Component;

class AdminSidebar extends Component
{
    public $menus = [];

    public function __construct()
    {
        $this->getMenu();
        $this->sortMenu();
    }

    public function getMenu()
    {
        $roles = \DB::table('roles')->where('guard_name','web')->get()->pluck("name")->toArray();

        $menus_first = [
            [
                'order' => 0,
                'name' => 'dashboard',
                'icon' => 'fa-solid fa-grid-2',
                'label' => __('Dashboard'),
                'url' => url(config('paagez.prefix')),
                'roles' => $roles
            ]
        ];
        foreach($menus_first as $menu)
        {
            $this->menus[] = $menu;
        }
        $modules = config('paagez.models.module')::where('is_active',1)->get();
        foreach ($modules as $key => $module) {
            $classname = $module->namespace."\\Navigations\\Menu";
            if(file_exists(base_path($classname.".php")))
            {
                if(class_exists($classname))
                {
                    $menu = new $classname;
                    foreach($menu->menu as $item)
                    {
                        if(\Auth::user()->hasRole($item['roles']))
                        {
                            $this->menus[] = $item;
                        }
                    }
                }
            }
        }
        $menus_last = [
            [
                'order' => 9999999999,
                'name' => 'app-settings',
                'icon' => 'fa-solid fa-gear',
                'label' => __('paagez.app_configuration'),
                'url' => route(config('paagez.route_prefix').".app.config"),
                'roles' => ['admin']
            ]
        ];
        foreach($menus_last as $menu)
        {
            $this->menus[] = $menu;
        }
    }

    public function sortMenu()
    {
        $childs = \collect($this->menus)->filter(function ($item) {
                        $parent = (isset($item['parent'])) ? $item['parent'] : null;
                        return !is_null($parent);
                    })->sortBy('order')->map(function($item)
                    {
                        $item['active'] = (url()->current() == $item['url'] || "/".request()->path == $item['url']) ? true : false;
                        $item['roles'] = implode("|",$item['roles']);
                        return $item;
                    });
        $this->menus = \collect($this->menus)->filter(function ($item) {
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

    public function render()
    {
        return view(config('paagez.theme').'::components.admin.sidebar');
    }
}