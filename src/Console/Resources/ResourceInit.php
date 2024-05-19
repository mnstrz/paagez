<?php

namespace Monsterz\Paagez\Console\Resources;

trait ResourceInit
{
	public $controller_path = '';

	public $controller_name = '';

	public $controller_namespace = '';

	public $model_var = '';

	public $model_name = '';

	public $model_namespace = '';

	public $route_name = '';

	public $view_path = '';

	public $view_name = '';

	public $breadcrumb_path = '';

	public $breadcrumb_namespace = '';

	public $breadcrumb_name = '';

	public $request_name = '';

	public $request_path = '';

	public $request_namespace = '';

	public $layout = '';


	public function initResourceName($name,$model)
	{
		if(!preg_match('/^[A-Za-z0-9 ]*$/', $name))
    	{
    		$this->error('Invalid title character');
    		return 1;
    	}

		if(!preg_match('/^[A-Za-z0-9\\\\]*$/', $model))
    	{
    		$this->error('Invalid model');
    		return 1;
    	}

    	if(!class_exists($model))
    	{
    		$this->error("<fg=yellow>$model</> not found");
    		return 1;
    	}

    	$module = $this->namespace."\\Module";
    	if(!class_exists($module))
    	{
    		$this->error("Module <fg=yellow>module/$this->module_name</> doesn't exists");
    		return 1;
    	}

    	$this->model_namespace = $model;

    	if($this->getTableInformation())
    	{
    		return 1;
    	}

    	$random = \Str::lower(\Str::random(3));
    	$name = \Str::snake($name);
    	$name = preg_replace('/[-_]/',' ',$name);

    	$this->title = \Str::title($name);

    	$this->controller_name = \Str::studly($name)."Controller";

    	$controller_class = $this->namespace."\\Controllers\\".$this->controller_name;
    	if(file_exists($controller_class.".php"))
    	{
    		$this->controller_name = $this->controller_name."_".$random;
    	}

    	$this->controller_path = $this->module_path."/Controllers/".$this->controller_name.".php";

    	$this->controller_namespace = $this->namespace."\\Controllers";

    	$this->request_name = \Str::studly($name)."Request";
    	$requestclass = $this->namespace."\\Requests\\".$this->request_name;
    	if(file_exists($requestclass.".php"))
    	{
    		$this->request_name = $this->request_name."_".$random;
    	}

    	$this->request_path = $this->module_path."/Requests/".$this->request_name.".php";

    	$this->request_namespace = $this->namespace."\\Requests";

    	$modelname = explode("\\",$model);
    	$this->model_name = end($modelname);

    	$this->model_namespace = $model;

    	$this->model_var = \Str::slug($name,"_");

    	$route = config("paagez.route_prefix").".".$this->module_name.".".$this->model_var;

    	if(\Route::has($route.".index") || \Route::has($route.".store") || \Route::has($route.".create") || \Route::has($route.".show") || \Route::has($route.".update") || \Route::has($route.".destroy") || \Route::has($route.".edit"))
    	{
    		$this->model_var = $this->model_var."_".$random;
    	}

    	$this->route_name = config("paagez.route_prefix").".".$this->module_name.".".$this->model_var;

    	$foldername = \Str::slug($name,"_");
    	if(file_exists($this->module_path."/resources/views/".$foldername."/index.blade.php") || file_exists($this->module_path."/resources/views/".$foldername."/form.blade.php") || file_exists($this->module_path."/resources/views/".$foldername."/show.blade.php"))
    	{
    		$foldername = $foldername."_".$random;
    	}

    	$this->view_path = $this->module_path."/resources/views/".$foldername;
    	
    	$this->view_name = $this->module_name."::".$foldername;

    	$this->breadcrumb_name = \Str::studly($name)."Breadcrumb";

    	$tabclass = $this->namespace."\\Navigations\\Breadcrumbs\\".$this->breadcrumb_name;
    	if(file_exists($tabclass.".php"))
    	{
    		$this->breadcrumb_name = $this->breadcrumb_name."_".$random;
    	}

    	$this->breadcrumb_namespace = $this->namespace."\\Navigations\\Breadcrumbs";

    	$this->breadcrumb_path = $this->module_path."/Navigations/Breadcrumbs/".$this->breadcrumb_name.".php";

    	$this->layout = config('paagez.theme')."::layouts.admin";
	}

	public function validateResource()
	{

	}

	public function inputModule()
	{
		$option = $this->ask('Please enter the module name');
        if(!$option)
        {
            return $this->inputOptions();
        }else{
            $this->input->setOption('module',$option);
            return 0;
        }
	}

	public function inputModel()
	{
		$option = $this->ask('Please enter the model');
        if(!$option)
        {
            return $this->inputOptions();
        }else{
            $this->input->setOption('model',$option);
            return 0;
        }
	}

	public function inputTitle()
	{
		$option = $this->ask('Please enter the title');
        if(!$option)
        {
            return $this->inputOptions();
        }else{
            $this->title = $option;
            return 0;
        }
	}
}