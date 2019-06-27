<?php
namespace app\admin\controller;

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
		return $name.'地址错误';
	}

	protected function me($name)
	{
		echo $name.'地址不存在';
	}
}