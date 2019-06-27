<?php

namespace app\admin\controller;

use think\Controller;
use think\facade\Request;
use think\facade\Log;
use think\Db;


class QuserUserID extends Controller
{
	public function index()
	{
		if($this->request->isPost())
		{
			$gid = Request::post('gid');
			$uid = Db::table('AccountsInfo')->field('UserID')->where('GameID',$gid)->find();
			$this->assign('gid', $uid['UserID']);
			$this->assign('gidd', $gid);
		}
		return $this->view->fetch();
	}
}