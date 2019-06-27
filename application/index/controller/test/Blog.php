<?php

namespace app\index\controller\test;

use think\Controller;

/**
 * 
 */
class Blog extends Controller
{

	protected function initialize()
	{
		echo 'init<br/>';
	}

	public function _empty($name)
	{
		return $this->showCity($name);
	}
	
	/**
	 * @route('test','index/test.Blog/index')
	 */
	public function index()
	{

		$this->success('hh','index/TryTest/getFu');
	}

	protected function showCity($name)
	{
		return 'city='.$name;
	}

}