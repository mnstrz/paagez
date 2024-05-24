<?php

namespace Monsterz\Paagez\View;

use Illuminate\View\Component;

class WidgetRight extends Component
{

    public $id = '';

    public $widgets = [];

    public function __construct()
    {
        $this->id = \Str::random(10);

        $this->getContents();
        $this->sortContents();
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
                    $widgets = glob($path."Widgets/*.php");
                    foreach($widgets as $widget)
                    {
                        $path = preg_split('/[\/\\\\]/', $widget);
                        $class_name = array_slice($path, -4, 4, true);
                        $class_name = array_values($class_name);
                        $class_name[count($class_name)-1] = str_replace(".php","",$class_name[count($class_name)-1]);
                        $class_name = '\\App\\'.implode("\\",$class_name);
                        if(class_exists($class_name))
                        {
                            $widget = new $class_name;
                            if(\Auth::user()->hasRole($widget->roles) && $widget->position == 'right')
                            {
                                $this->widgets[] = [
                                    'order' => $widget->order,
                                    'views' => $widget->views
                                ];
                            }
                        }
                    }
                }
            }
        }
    }

    protected function sortContents()
    {
        $this->widgets = \collect($this->widgets)->sortBy('order')->map(function($item){
            return $item;
        });
    }

    public function render()
    {
        return view(config('paagez.theme').'::components.admin.widget-right');
    }
}