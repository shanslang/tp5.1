<?php

namespace app\http\middleware;

/**
 * 
 */
class Hello
{
	
	public function handle($request, \Closure $next)
	{
		$request->hello = 'ThinkPHP';
		$request->hh = 'hh';
		return $next($request);
	}
}