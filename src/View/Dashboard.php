<?php

namespace Monsterz\Paagez\View;

use Illuminate\View\Component;

class Dashboard extends Component
{

    public $id = '';

    public $dashboards = [];

    public function __construct()
    {
        $this->id = \Str::random(10);

        $this->getContents();
        $this->sortFromModule();
    }

    protected function getContents()
    {

        $modules = config('paagez.models.module')::where('is_active',1)->get();
        foreach ($modules as $key => $module) {
            $module_class = $module->namespace."\\Module";
            if(file_exists(base_path($module_class.".php")))
            {
                if(class_exists($module_class))
                {
                    $reflector = new \ReflectionClass($module_class);
                    $path = str_replace("Module.php","",$reflector->getFileName());
                    $dashboards = glob($path."Widgets/*.php");
                    foreach($dashboards as $dashboard)
                    {
                        $path = preg_split('/[\/\\\\]/', $dashboard);
                        $class_name = array_slice($path, -4, 4, true);
                        $class_name = array_values($class_name);
                        $class_name[count($class_name)-1] = str_replace(".php","",$class_name[count($class_name)-1]);
                        $class_name = '\\App\\'.implode("\\",$class_name);
                        if(class_exists($class_name))
                        {
                            $dashboard = new $class_name;
                            if(\Auth::user()->hasRole($dashboard->roles) && $dashboard->position == 'main')
                            {
                                $this->dashboards[] = [
                                    'order' => $dashboard->order,
                                    'views' => $dashboard->views
                                ];
                            }
                        }
                    }
                }
            }
        }
    }

    protected function sortFromModule()
    {
        $this->dashboards = \collect($this->dashboards)->sortBy('order')->map(function($item){
            return $item;
        });
    }


    public function render()
    {
        return view(config('paagez.theme').'::components.admin.dashboard');
    }
}