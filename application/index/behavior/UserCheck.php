<?php
namespace app\index\behavior;

class UserCheck
{
	public function run()
	{
		if('user/0' == request()->url()){
			request()->url();
			return false;
		}else{
			//var_dump(request()->url());exit;//string(28) "/tp5/public/index.php/hh/1/1"
		}
	}
}