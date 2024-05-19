<?php

namespace Monsterz\Paagez\Classes\Navigations;

class ModuleBreadcrumb
{
	public $breadcrumbs = [];

	public function __construct()
	{
		$this->register();
		$this->load_from_classes();
	}

	public function register()
	{

	}

	public function add_breadcrumb($name,$breadcrumb)
	{
		$this->breadcrumbs[$name] = $breadcrumb;
	}

	public function load_from_classes()
	{
		$class_name = get_class($this);
		$reflection_class = new \ReflectionClass($class_name);
		$namespace = $reflection_class->getNamespaceName();
		$path = str_replace("App","app",$namespace);
		$breadcrumbs = glob(base_path($path."\\Breadcrumbs\\*.php"));
		foreach ($breadcrumbs as $key => $breadcrumb) {
			$path = preg_split('/[\/\\\\]/', $breadcrumb);
            $class_name = array_slice($path, -4, 4, true);
            $class_name = array_values($class_name);
            $class_name[count($class_name)-1] = str_replace(".php","",$class_name[count($class_name)-1]);
            $class_name = '\\App\\Modules\\'.implode("\\",$class_name);
            if(class_exists($class_name))
            {
            	$breadcrumb = new $class_name;
            	foreach($breadcrumb->breadcrumbs as $breadcrumb_name => $breadcrumb_items)
            	{
            		$this->add_breadcrumb($breadcrumb_name,$breadcrumb_items);
            	}
            }
		}
	}
}