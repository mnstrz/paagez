<?php

namespace Monsterz\Paagez\Classes\Widget;

class ModuleWidget
{
	/**
	 * 
	 * @string
	 * widget position
	 * `dashboard-main`,`dashboard-side`,`left`,`right`
	 * 
	 * 
	 * */
	public $position = 'dashboard-main';

	public $roles = ['admin','authors'];

	public $order = 999999;

	public $views = '';

	public function __construct()
	{
		$this->views = $this->render()->render();
	}

	public function render()
	{
		return new \View;
	}
}