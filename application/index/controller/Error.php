<?php
namespace app\index\controller;

use think\Request;

/**
 * 
 */
class Error
{
	public function _empty($name)
	{
		return $this->me($name);
	}
	public function index(Request $request)
	{
		$cityName = $request->controller();
		return $this->city($cityName);
	}

	protected function city($name)
	{
		return 'controller='.$name;
	}
	protected function me($name)
	{
		return 'method='.$name;
	}
}