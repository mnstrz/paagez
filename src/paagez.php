<?php

return [

	/**
	 * 
	 * @string
	 * route prefix
	 * 
	 * */
	'prefix' => 'pz-admin',

	/**
	 * 
	 * @string
	 * route prefix
	 * 
	 * */
	'route_prefix' => 'paagez',

	/**
	 * 
	 * @string
	 * assets cache
	 * 
	 * */
	'cache' => '202401010001',

	/**
	 * 
	 * @string
	 * Application name
	 * 
	 * */
	'app_name' => 'Paagez',

	/**
	 * 
	 * @string
	 * Application logo
	 * 
	 * */
	'app_logo' => '/theme/images/logo.jpg',

	/**
	 * 
	 * @string
	 * composer base path
	 * 
	 * */
	'composer' => env('COMPOSER_HOME',''),

	/**
	 * 
	 * @string
	 * database prefix
	 * 
	 * */
	'db_prefix' => 'pz_',

	/**
	 * 
	 * @string
	 * base theme
	 * 
	 * */

	'base_theme' => 'bootstrap',

	/**
	 * 
	 * @string
	 * theme path
	 * 
	 * */

	'theme' => 'paagez',

	/**
	 * 
	 * @string
	 * theme path
	 * 
	 * */

	'default_layout' => 'layouts.admin',

	/**
	 * 
	 * @string
	 * boxed
	 * 
	 * */

	'boxed' => false,

	/**
	 * 
	 * @string
	 * layouts
	 * */
	'layouts' => ['app','admin','admin-navbar-only'],

	/**
	 * 
	 * @string
	 * footer contents html or string
	 * 
	 * */
	'footer' => '&copy 2024 Paagez.com | Allright reserved',

	/**
	 * 
	 * @string
	 * footer mode 'full' or 'sidebar'
	 * 
	 * */
	'footer_mode' => 'full',

	/**
	 * 
	 * 
	 * */
	'models' => [

		'user' => \Monsterz\Paagez\Models\User::class,

		'roles' => \Monsterz\Paagez\Models\Roles::class,

		'config' => \Monsterz\Paagez\Models\PzConfig::class,

		'module' => \Monsterz\Paagez\Models\PzModule::class,

		'module_config' => \Monsterz\Paagez\Models\PzModuleConfig::class,

		'module_packages' => \Monsterz\Paagez\Models\PzModulePackage::class,

	],

	/**
	 * 
	 * google captcha
	 * 
	 * */
	'gcaptcha' => false,

	/**
	 * 
	 * pwa
	 * 
	 * */
	'pwa' => false,

	/**
	 * 
	 * analytics
	 * 
	 * */
	'analytics' => ''
];