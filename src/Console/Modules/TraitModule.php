<?php

namespace Monsterz\Paagez\Console\Modules;

trait TraitModule
{
    public $module_name = '';

    public $module_title = '';

    public $module_version = '';

    public $module_route_prefix = '';

    public $module_route_name = '';

    public $module_folder = '';

    public $module_path = '';

    public $namespace = '';

    public $studly_case = '';

    public $camel_case = '';

    public $snake_case = '';

    public $title_case = '';

    public $module_extra_path = '';

    public $extra_namespace = '';

    public $route_path = '';

    public function initials($name,$regex='/^[A-Za-z0-9_]+$/')
    {
        if(!$name)
        {
            $this->error('Invalid value characters');
            return 1;
        }
    	if(!preg_match($regex, $name))
    	{
    		$this->error('Module name only permitted alphabeth, numbers, or underscore');
    		return 1;
    	}
        if(in_array($name,['admin','website','config','settings','users','user','role','permission','permissions','api','roles','app','update','install','setup','login','logout','sign','notifications','notification','rest','appearance','theme','display','dashboard','module','modules','email','mail','job','jobs','profile']))
        {
            $this->error('Module names are not permitted');
            return 1;
        }
    	$this->module_name = \Str::snake($name);
    	$title = str_replace("-"," ",strtolower($this->module_name));
    	$title = str_replace("_"," ",$title);

    	$this->module_title = \Str::snake($title);
    	$this->module_version = \Carbon\Carbon::now()->format('Ymdh001');
    	$this->module_route_prefix = \Str::slug($title,"-");
    	$this->module_route_name = \Str::slug($title,"_");
    	$this->module_folder = str_replace(" ","",$this->module_title);
    	$this->module_path = base_path("app/Modules/".$this->module_folder);
    	$this->namespace = "App\Modules\\$this->module_folder";
    }

    public function strings($name,$regex='/^[A-Za-z0-9_-]+$/')
    {
        if(!$name)
        {
            $this->error('Invalid value characters');
            return 1;
        }
        if(!preg_match($regex, $name))
        {
            $this->error('The value only permitted alphabeth, numbers, strips, or underscore');
            return 1;
        }
        $this->studly_case = \Str::studly($name);

        $this->snake_case = \Str::snake($name);

        $this->camel_case = \Str::camel($name);
        if (preg_match('/[\/\\\\.]/', $name)) {
            $parts = preg_split('/[\/\\\\.]/', $name);
            $this->route_path = implode(".",$parts);
            $lastPart = array_pop($parts);
            $path = implode('/', $parts);
            $namespace = implode('\\', $parts);
            $this->module_extra_path = "/".$path;
            $this->extra_namespace = "\\".$namespace;
            $name = $lastPart;
        }else{
            $this->route_path = $this->snake_case;
        }

        $this->title_case = \Str::title(str_replace("_"," ",$this->snake_case));
    }

    public function checkString($string,$regex=null)
    {
        if(!$string)
        {
            $this->error('Invalid value characters');
            return 1;
        }
        if(!$regex)
        {
            $regex = '/^[A-Za-z0-9_-]+$/';
            if(!preg_match('/^[A-Za-z0-9_-]+$/', $string))
            {
                $this->error('The value only permitted alphabeth, numbers, strips, or underscore');
                return 1;
            }
            return 0;
        }else{
            if(!preg_match($regex, $string))
            {
                $this->error('Invalid value characters');
                return 1;
            }
            return 0;
        }
    }
}