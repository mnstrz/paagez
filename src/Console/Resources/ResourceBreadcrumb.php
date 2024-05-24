<?php

namespace Monsterz\Paagez\Console\Resources;

trait ResourceBreadcrumb
{
	public function initBreadcrumb()
	{
    	if(!file_exists($this->module_path."/Navigations/Breadcrumb.php"))
    	{
    		$this->call('paagez:breadcrumb',['--module'=>$this->module_name]);
    	}
    	if(!file_exists($this->module_path."/Navigations/Breadcrumbs"))
    	{
    		\File::makeDirectory($this->module_path."/Navigations/Breadcrumbs", 0755, true);
    	}
		$fileContent = '<?php

namespace '.$this->breadcrumb_namespace.';

use Monsterz\Paagez\Classes\Navigations\ModuleBreadcrumbs;

class '.$this->breadcrumb_name.' extends ModuleBreadcrumbs
{
	public function register()
	{
		$data = \Route::current()->parameter("'.$this->model_var.'");
		$id = ($data) ? $data->'.$this->primary_key.' : 1;
		$this->add_breadcrumb(route('.$this->route_name.'.".index"),[
            route("paagez.index") => "<i class='."'".'fa fa-home'."'".'></i>",
            route('.$this->route_name.'.".index") => __("'.$this->title.'")
		]);
		$this->add_breadcrumb(route('.$this->route_name.'.".create"),[
            route("paagez.index") => "<i class='."'".'fa fa-home'."'".'></i>",
            route('.$this->route_name.'.".index") => __("'.$this->title.'"),
            route('.$this->route_name.'.".create") => __("Create '.$this->title.'")
		]);
		$this->add_breadcrumb(route('.$this->route_name.'.".edit",[$id]),[
            route("paagez.index") => "<i class='."'".'fa fa-home'."'".'></i>",
            route('.$this->route_name.'.".index") => __("'.$this->title.'"),
            route('.$this->route_name.'.".edit",[$id]) => __("Edit '.$this->title.'")
		]);
		$this->add_breadcrumb(route('.$this->route_name.'.".show",[$id]),[
            route("paagez.index") => "<i class='."'".'fa fa-home'."'".'></i>",
            route('.$this->route_name.'.".index") => __("'.$this->title.'"),
            route('.$this->route_name.'.".show",[$id]) => __("Show '.$this->title.'")
		]);
	}
}';
		\File::put($this->breadcrumb_path, $fileContent);
	}
}