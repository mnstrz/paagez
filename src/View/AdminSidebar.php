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
        $menus_first = [
            [
                'order' => 0,
                'name' => 'dashboard',
                'icon' => 'fa-solid fa-grid-2',
                'label' => __('Dashboard'),
                'url' => url(config('paagez.prefix'))
            ]
        ];
        foreach($menus_first as $menu)
        {
            $this->menus[] = $menu;
        }
        $modules = config('paagez.models.module')::all();
        foreach ($modules as $key => $module) {
            $classname = $module->namespace."\\Navigations\\Menu";
            if(file_exists(base_path($classname.".php")))
            {
                if(class_exists($classname))
                {
                    $menu = new $classname;
                    foreach($menu->menu as $item)
                    {
                        $this->menus[] = $item;
                    }
                }
            }
        }
        $menus_last = [
            [
                'order' => 9999999999,
                'name' => 'config',
                'icon' => 'fa-solid fa-wrench',
                'label' => __('Configuration'),
                'url' => '#',
            ],
            [
                'parent' => 'config',
                'name' => 'user',
                'label' => __('Users'),
                'url' => url(config('paagez.prefix')."/user"),
            ],
            [
                'parent' => 'config',
                'name' => 'role',
                'label' => __('Roles'),
                'url' => url(config('paagez.prefix')."/roles"),
            ],
            [
                'parent' => 'config',
                'name' => 'website',
                'label' => __('Website'),
                'url' => url(config('paagez.prefix')."/website"),
            ],
            [
                'parent' => 'config',
                'name' => 'modules',
                'label' => __('Modules'),
                'url' => url(config('paagez.prefix')."/modules"),
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
                        return $item;
                    })->toArray();
    }

    public function render()
    {
        return view(config('paagez.theme').'::components.admin.sidebar');
    }
}