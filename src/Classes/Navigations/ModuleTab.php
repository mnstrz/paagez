<?php

namespace Monsterz\Paagez\Classes\Navigations;

class ModuleTab
{
	public $tabs = [];

	public function __construct()
	{
		$this->register();
	}

	public function register()
	{

	}
	
	public function add_tab_group($name,$tabs)
	{
		if(!isset($this->tabs[$name]))
		{
			$this->tabs[$name] = $tabs;
		}
	}
}