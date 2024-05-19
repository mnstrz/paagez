<?php

if(!function_exists('error_page')){
	function error_page($title,$message=null)
	{
		echo view('errors.error',compact('title','message'));
		die();
	}
}

if(!function_exists('_table')){
	function _table($tablename)
	{
		return config('simkes.table_prefix').$tablename;
	}
}

if(!function_exists('is_admin')){
	function is_admin()
	{
		return 555;
	}
}