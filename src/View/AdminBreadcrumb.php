<?php

namespace Monsterz\Paagez\View;

use Illuminate\View\Component;

class AdminBreadcrumb extends Component
{
    public $breadcrumbs = [];

    public function __construct()
    {
        $this->getBreadCrumbs();
        $this->selectBreadCrumbs();
    }

    protected function getBreadCrumbs()
    {
        $modules = config('paagez.models.module')::all();
        foreach ($modules as $key => $module) {
            $classname = $module->namespace."\\Navigations\\Breadcrumb";
            if(class_exists($classname))
            {
                $breadcrumb = new $classname;
                foreach($breadcrumb->breadcrumbs as $key => $item)
                {
                    $this->breadcrumbs[$key] = $item;
                }
            }
        }
    }

    protected function selectBreadCrumbs()
    {
        $routename = \Request::route()->getName()??null;
        $url = url()->current();
        $path = "/".request()->path;
        if(isset($this->breadcrumbs[$routename]))
        {
            $this->breadcrumbs = $this->breadcrumbs[$routename];
        }elseif(isset($this->breadcrumbs[$url]))
        {
            $this->breadcrumbs = $this->breadcrumbs[$url];
        }elseif(isset($this->breadcrumbs[$path]))
        {
            $this->breadcrumbs = $this->breadcrumbs[$path];
        }else{
            $this->breadcrumbs = [];
        }
    }

    public function render()
    {
        return view(config('paagez.theme').'::components.admin.breadcrumb');
    }
}