<?php

namespace Monsterz\Paagez\Console\Widgets;

trait ResourceInit
{
	public $widget_path = '';

	public $widget_name = '';

	public $widget_namespace = '';

	public $view_path = '';

	public $view_name = '';


	public function initResourceName($name)
	{
		if(!preg_match('/^[A-Za-z0-9 ]*$/', $name))
    	{
    		$this->error('Invalid widget name character');
    		return 1;
    	}

    	$module = $this->namespace."\\Module";
    	if(!class_exists($module))
    	{
    		$this->error("Module <fg=yellow>module/$this->module_name</> doesn't exists");
    		return 1;
    	}

    	$random = \Str::lower(\Str::random(3));
    	$name = \Str::snake($name);
    	$name = preg_replace('/[-_]/',' ',$name);

    	$this->title = \Str::title($name);

    	$this->widget_name = \Str::studly($name)."Widget";

    	$controller_class = $this->namespace."\\Widgets\\".$this->widget_name;
    	if(file_exists($controller_class.".php"))
    	{
    		$this->widget_name = $this->widget_name."_".$random;
    	}

    	$this->widget_path = $this->module_path."/Widgets/".$this->widget_name.".php";

    	$this->widget_namespace = $this->namespace."\\Widgets";

    	$filename = \Str::slug($name,"_");
    	if(file_exists($this->module_path."/resources/views/widget-".$this->type."/".$filename.".blade.php"))
    	{
    		$filename = $filename."_".$random;
    	}

    	$this->view_path = $this->module_path."/resources/views/widget-".$this->type."/".$filename.".blade.php";
    	
    	$this->view_name = $this->module_name."::widget-".$this->type.".".$filename;
	}

	public function inputName()
	{
		$option = $this->ask('Please enter the widget name');
        if(!$option)
        {
            return $this->inputName();
        }else{
            $this->input->setArgument('name',$option);
            return 0;
        }
	}

	public function inputType()
	{
		$option = $this->ask('Please enter the widget type <fg=yellow>main,side,left,right</>');
        if(!$option && !in_array($option,['main','side','left','right']))
        {
            return $this->inputType();
        }else{
            $this->input->setOption($option,1);
            return 0;
        }
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
}