<?php

namespace Monsterz\Paagez\View;

use Illuminate\View\Component;

class AdminTab extends Component
{
    public $tabs = [];

    public $active = '';

    public function __construct()
    {
        $this->getTabs();
        $this->selectTabs();
    }

    protected function getTabs()
    {
        $modules = config('paagez.models.module')::where('is_active',1)->get();
        foreach ($modules as $key => $module) {
            $classname = $module->namespace."\\Navigations\\Tab";
            if(file_exists(base_path($classname.".php")))
            {
                if(class_exists($classname))
                {
                    $tab = new $classname;
                    foreach($tab->tabs as $key => $item)
                    {
                        $this->tabs[$key] = $item;
                    }
                }
            }
        }
    }

    protected function selectTabs()
    {
        $selected = null;
        $active = null;

        foreach($this->tabs as $key => $tabs)
        {
            $routename = \Request::route()->getName()??null;
            $url = url()->current();
            $path = "/".request()->path;
            if(isset($tabs[$routename]))
            {
                $selected = $key;
                $active = $routename;
            }elseif(isset($tabs[$url]))
            {
                $selected = $key;
                $active = $url;
            }elseif(isset($tabs[$path]))
            {
                $selected = $key;
                $active = $path;
            }
        }
        if($selected)
        {
            $this->tabs = $this->tabs[$selected];
            $this->active = $active;
        }
    }

    public function render()
    {
        return view(config('paagez.theme').'::components.admin.tab');
    }
}