<?php

namespace app\index\widget;

/**
 * 
 */
class Blog
{
	
	public function header()
	{
		return 'header';
	}

	public function left()
	{
		return 'left';
	}

	public function menu($name)
	{
		return 'menu:'.$name;
	}
}