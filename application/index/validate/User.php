<?php

namespace app\index\validate;

use think\Validate;

/**
 * 
 */
class User extends Validate
{
	
	protected $rule = [
		'name'	=> ['require', 'max' => 25, 'regex' => '/^[\w|\d]\w+/'],
		'age'	=> ['number', 'between' => '1,20'],
		'email' => 'emial',
	];
}