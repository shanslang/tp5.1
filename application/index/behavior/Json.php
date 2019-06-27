<?php
namespace app\index\behavior;

class Json
{
	public function run($response)
	{
		$response->contentType('application/json');
		var_dump($response);
	}
}