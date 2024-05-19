<?php

namespace Monsterz\Paagez\Classes\Navigations;

class ModuleBreadcrumbs
{
	public $breadcrumbs = [];

	public function __construct()
	{
		$this->register();
	}

	public function register()
	{

	}

	public function add_breadcrumb($name,$breadcrumb)
	{
		$this->breadcrumbs[$name] = $breadcrumb;
	}
}