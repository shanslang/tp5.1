<?php

namespace app\admin\controller;

use think\{Controller, Db};
use think\facade\{Log, Session, Validate, Request};
use app\common\{Conn, Sqlsrv};

class User extends Controller
{
	private $mid;
	protected function initialize()
    {
        if(!Session::has('admin')){
          	$this->error('请登录呦！', 'Login/index');
        }
        $this->mid = Session::get('admin.UserID');
    }

	public function sendUser()
	{
		if($this->request->isPost())
		{
			$arr     = $this->request->post('obj');
			$reason  = Conn::setCharg($arr['reason']);
			$reason2 = Conn::setCharg($arr['reason2']);
			$reason3 = Conn::setCharg($arr['sendreason3']);
			$reason4 = Conn::setCharg('赠送VIP'); 
			$reason5 = Conn::setCharg('赠送金币'); 
			$IP 	 = $this->request->ip();

			if($arr['days']>2000){
				$arr['days'] =2000;
			}

			$type = 0;
			if($arr['score'] && $arr['days'] && $arr['sendNum']){
				$type = 1;
			}
			if($arr['score'] && $arr['days'] && $arr['sendNum']==''){
				$type = 6;
			}
			if($arr['sendNum'] && $arr['days'] && $arr['score']==''){
				$type = 2;
			}
			if($arr['score'] && $arr['sendNum']== '' && $arr['days']==''){
				$type = 3;
			}
			if($arr['days'] && $arr['sendNum'] == '' && $arr['score'] == ''){
				$type = 4;
			}
			if($arr['sendNum'] && $arr['days'] == '' && $arr['score'] == ''){
				$type = 5;
			}

			$sql = "{call [THGameScoreDB].[dbo].[PHP_UserSendVip] (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)}";
			$params = array($arr['vipgrad'], $arr['gid'], $this->mid, $type, $arr['days'], $arr['goldpo'], $arr['score'], $arr['sendNum'], $IP, $reason, $reason2, $reason3, $reason4, $reason5);
			$row = Sqlsrv::getInstance()->setProcedure($sql, $params);
			$row['msg'] = Conn::setCharu($row['msg']);
			return $row['msg'];
		}
		return $this->view->fetch();
	}

	public function recoverGold()
	{
		if($this->request->isPost())
		{
			$arr = $this->request->post('obj');
			$validate = Validate::make([
			    'sourGameID'   => 'require|number',
			    'targetGameID' => 'require|number',
			    'sources' 	   => 'require|number|min:1',
			]);
			if (!$validate->check($arr)) {
			    return $validate->getError();
			}

			$sql = "{call [THGameScoreDB].[dbo].[PHP_UserRecover] (?, ?, ?, ?, ?)}";
			$params = array($arr['sourGameID'], $arr['targetGameID'], $arr['sources'], $this->mid, $this->request->ip());
			$row = Sqlsrv::getInstance()->setProcedure($sql, $params);
			$row['msg'] = Conn::setCharu($row['msg']);
			return $row['msg'];
		}
	}

	// 封号记录
	public function fhaoRecord()
	{
		if($this->request->isAjax())
		{
			$isvip = $this->request->get('isvip') ?: 1;
			$gid   = $this->request->get('gid') ?: 0;
			$t1    = $this->request->get('startTime') ?: date('Y-m-d');
			$t2    = $this->request->get('endTime') ?: date('Y-m-d');

			$pagesize = Request::get('limit',20);
	    	$pageNum = $this->request->get('offset');
  			if($pageNum == 0){
  				  $pageNo = 1;
  			}else{
  				  $pageNo = $pageNum/$pagesize+1;
  			}
  			$t3 	= $t2.' 23:59:59';

			$sql 	= "{call [THGameScoreDB].[dbo].[PHP_User_fhList] (?, ?, ?, ?, ?, ?)}";
			$params = array($pagesize, $pageNo, $gid, $isvip, $t1, $t3);
			$arr    = Sqlsrv::getInstance()->queryProcedure($sql, $params);
			$ct 	= count($arr);
			if($ct > 0){
				$total = $arr[0]['ct'];
			}else{
				$total = 0;
			}
			for($i=0;$i<$ct;$i++){
				if($arr[$i]['Nullity'] == 1){
					$arr[$i]['type'] = '封号中';
				}else if($arr[$i]['MoorMachine'] == 1){
					$arr[$i]['type'] = '锁定中';
				}else if($arr[$i]['CustomFaceVer'] == 1){
					$arr[$i]['type'] = '屏蔽中';
				}
				$arr[$i]['Accounts'] = Conn::setCharu($arr[$i]['Accounts']);
				$arr[$i]['NickName'] = Conn::setCharu($arr[$i]['NickName']);
				$arr[$i]['gold_sum'] = number_format($arr[$i]['Score']+$arr[$i]['InsureScore']);
				$arr[$i]['Score'] = number_format($arr[$i]['Score']); 
				$arr[$i]['InsureScore'] = number_format($arr[$i]['InsureScore']); 
			}
			$result = array('total' => $total, 'rows' => $arr);
    	  	return json($result);
		}
		return $this->view->fetch();
	}

	// 修改昵称
	public function editNickname()
	{
		if($this->request->isPost())
		{
			$arr = $this->request->post('arr');
			$type = 1;
			if(count($arr) > 1){
				$type = 2;
			}
			$arr = implode(',', $arr);
			$sql = "{call [THGameScoreDB].[dbo].[PHP_User_editNickname] (?, ?, ?)}";
			$params = array($arr, $this->mid, $type);
			$row    = Sqlsrv::getInstance()->setProcedure($sql, $params);
			$row['msg'] = Conn::setCharu($row['msg']);
			return $row['msg'];
		}
	}

	// 限制IP地址
	public function limitIp()
	{
		if($this->request->isAjax())
		{
			$isvague = $this->request->get('isvague') ?: 0;
			$IP      = $this->request->get('IP');

			$pagesize = Request::get('limit',20);
	    	$pageNum  = $this->request->get('offset');
  			if($pageNum == 0){
  				   $pageNo = 1;
  			}else{
  				   $pageNo = $pageNum/$pagesize+1;
  			}
  			$sql    = "{call [THGameScoreDB].[dbo].[PHP_User_limitIP] (?, ?, ?, ?)}";
			$params = array($IP, $isvague, $pagesize, $pageNo);
			$arr    = Sqlsrv::getInstance()->queryProcedure($sql, $params);

			if(count($arr) > 0)
			{
				$ct = $arr[0]['ct'];
			}else{
				$ct = 0;
			}
			for ($i=0; $i < count($arr); $i++) { 
				$arr[$i]['CollectNote'] = Conn::setCharu($arr[$i]['CollectNote']);
			}
			$result = array('total' => $ct, 'rows' => $arr);
    	  	return json($result);
		}
		return $this->view->fetch();
	}

	// ip状态查询
	public function queryIp()
	{
		if($this->request->isPost())
		{
			$ip 	= $this->request->post('ip');
			$type 	= 1;
			$sql 	= "{call [THGameScoreDB].[dbo].[PHP_User_editIP_2] (?, ?)}";
			$params = array($ip, $type);
			$row 	= Sqlsrv::getInstance()->setProcedure($sql, $params);
			return json($row);
		}
	}

	// ip限制修改
	public function editIP()
	{
		if($this->request->isPost())
		{
			$obj2 = $this->request->post('obj2');
			$type = $this->request->post('type');
			$ip   = $obj2['ips'];
			$limitip = $obj2['limitip'];
			list($a, $b, $c) = $limitip;
			$sql = "{call [THGameScoreDB].[dbo].[PHP_User_editIP] (?, ?, ?, ?, ?, ?, ?, ?)}";
			$obj2['remarks'] = Conn::setCharg($obj2['remarks']);
			$params = array($type, $this->mid, $a, $b, $c, $obj2['time'], $obj2['remarks'], $ip);
			$row 	= Sqlsrv::getInstance()->setProcedure($sql, $params);
			return json($row);
		}
	}

	// ip删除
	public function delIP()
	{
		if($this->request->isPost())
		{
			$ip = $this->request->post('ip');
			$type = $this->request->post('type');
			$sql = "{call [THGameScoreDB].[dbo].[PHP_User_delIP] (?, ?, ?)}";
			$params = array($ip, $this->mid, $type);
			$row 	= Sqlsrv::getInstance()->setProcedure($sql, $params);
			return json($row);
		}	
	}

	// ip批量删除
	public function delsIP()
	{
		if($this->request->isPost())
		{
			$arr = $this->request->post('arr');
			$type = $this->request->post('type');
			$ips = implode(',', $arr);
			$sql = "{call [THGameScoreDB].[dbo].[PHP_User_del_IPs] (?, ?, ?)}";
			$params = array($ips, $this->mid, $type);
			$row 	= Sqlsrv::getInstance()->setProcedure($sql, $params);
			return json($row);
		}	
	}

	// 新增限制IP
	public function addIp()
	{
		if($this->request->isPost())
		{
			$arr = $this->request->post('obj2');
			//Log::write($arr);
			$validate = Validate::make([
			    'ips2'   => 'require|ip',
			    'remarks2' => 'require',
			]);
			if (!$validate->check($arr)) {
			    $einf['msg'] = $validate->getError();
			    $einf['ret'] = 2;
			    return json($einf);
			}
			list($a, $b, $c) = $arr['limitip'];
			$sql = "{call [THGameScoreDB].[dbo].[PHP_User_addIP] (?, ?, ?, ?, ?, ?, ?)}";
			$arr['remarks2'] = Conn::setCharg($arr['remarks2']);
			$params = array($arr['remarks2'], $arr['ips2'], $arr['time2'], $a, $b, $c, $this->mid);
			$row 	= Sqlsrv::getInstance()->setProcedure($sql, $params);
			return json($row);
		}
	}

	// 前100多IP
	public function topIp()
	{
		if($this->request->isAjax())
		{
			$sql = "{call [THGameScoreDB].[dbo].[PHP_User_topIP] (?)}";
			$type = $this->request->get('type');
			$params = array($type);
			$arr = Sqlsrv::getInstance()->queryProcedure($sql, $params);
			$ct = count($arr);
			$result = array('total' => $ct, 'rows' => $arr);
    	  	return json($result);
		}
	} 

	// 永久限制IP
	public function foreverIP()
	{
		if($this->request->isPost())
		{
			$ips = $this->request->post('arr');
			$type = $this->request->post('type');
			$hh = implode(',', $ips);
			$sql = "{call [THGameScoreDB].[dbo].[PHP_User_foreverIP] (?, ?, ?)}";
			$params = array($this->mid, $type, $hh);
			$row 	= Sqlsrv::getInstance()->setProcedure($sql, $params);
			return json($row);
		}
	}

	// 限制机器码
	public function limitMachine()
	{
		if($this->request->isAjax())
		{
			$isvague = $this->request->get('isvague') ?: 0;
			$IP      = $this->request->get('IP');

			$pagesize = Request::get('limit',20);
	    	$pageNum  = $this->request->get('offset');
  			if($pageNum == 0){
  				   $pageNo = 1;
  			}else{
  				   $pageNo = $pageNum/$pagesize+1;
  			}
  			$sql    = "{call [THGameScoreDB].[dbo].[PHP_User_limitMachine] (?, ?, ?, ?)}";
			$params = array($IP, $isvague, $pagesize, $pageNo);
			$arr    = Sqlsrv::getInstance()->queryProcedure($sql, $params);

			if(count($arr) > 0)
			{
				$ct = $arr[0]['ct'];
			}else{
				$ct = 0;
			}
			for ($i=0; $i < count($arr); $i++) { 
				$arr[$i]['CollectNote'] = Conn::setCharu($arr[$i]['CollectNote']);
			}
			$result = array('total' => $ct, 'rows' => $arr);
    	  	return json($result);
		}
		return $this->view->fetch();
	}

	// 机器码状态查询
	public function queryMachine()
	{
		if($this->request->isPost())
		{
			$ip 	= $this->request->post('ip');
			$type 	= 2;
			$sql 	= "{call [THGameScoreDB].[dbo].[PHP_User_editIP_2] (?, ?)}";
			$params = array($ip, $type);
			$row 	= Sqlsrv::getInstance()->setProcedure($sql, $params);
			return json($row);
		}
	}

	// 新增限制机器码
	public function addMachine()
	{
		if($this->request->isPost())
		{
			$arr = $this->request->post('obj2');
			$validate = Validate::make([
			    'ips2'   => 'require|max:32',
			    'remarks2' => 'require',
			]);
			if (!$validate->check($arr)) {
			    $einf['msg'] = $validate->getError();
			    $einf['ret'] = 2;
			    return json($einf);
			}
			list($a, $b, $c) = $arr['limitip'];
			$sql = "{call [THGameScoreDB].[dbo].[PHP_User_addMachine] (?, ?, ?, ?, ?, ?, ?)}";
			$arr['remarks2'] = Conn::setCharg($arr['remarks2']);
			$params = array($arr['remarks2'], $arr['ips2'], $arr['time2'], $a, $b, $c, $this->mid);
			$row 	= Sqlsrv::getInstance()->setProcedure($sql, $params);
			return json($row);
		}
	}

	// 在线游戏分类
	public function onlineGame(){
		if($this->request->isAjax())
		{
			$resultSet = Db::connect('db_connect2')->query('PHP_User_onlineList');
			$result = array('rows' => $resultSet);
    	  	return json($result);
		}
		return $this->view->fetch();
	}

	//  各房间人数
	public function gameOnline()
	{
		if($this->request->isAjax())
		{
			$kid = $this->request->get('kid');
			$sql = "{call [THGameScoreDB].[dbo].[PHP_User_RoomOnline] (?)}";
			$params = array($kid);
			$arr    = Sqlsrv::getInstance()->queryProcedure($sql, $params);
			for ($i=0; $i < count($arr); $i++) { 
				$arr[$i]['ServerName'] = Conn::setCharu($arr[$i]['ServerName']);
			}
			$result = array('rows' => $arr);
    	  	return json($result);
		}
		return $this->view->fetch();
	}

	// 房间输赢
	public function roomWin()
	{
		if($this->request->isAjax())
		{
			$pagesize = Request::get('limit',20);
	    	$pageNum  = $this->request->get('offset');
  			if($pageNum == 0){
  				   $pageNo = 1;
  			}else{
  				   $pageNo = $pageNum/$pagesize+1;
  			}
  			$iswin = Request::get('iswin', 0);
			$serid = $this->request->get('serid');
			$sql = "{call [THGameScoreDB].[dbo].[PHP_User_gameWinnew] (?, ?, ?, ?)}";
			$params = array($serid, $iswin, $pagesize, $pageNo);

			$arr    = Sqlsrv::getInstance()->queryProcedure($sql, $params);
			$ct = 0;
			$ServerName = '';
			for ($i=0; $i < count($arr); $i++) { 
				$arr[$i]['NickName'] = Conn::setCharu($arr[$i]['NickName']);
				$arr[$i]['ServerName'] = Conn::setCharu($arr[$i]['ServerName']);
			}
			if(count($arr) > 0)
			{
				$ct = $arr[0]['ct'];
				$ServerName = $arr[0]['ServerName'];
			}
			$result = array('total' => $ct, 'rows' => $arr, "extend" => ['ServerName' => $ServerName]);
    	  	return json($result);
		}
		return $this->view->fetch();
	}

	// 游戏人数
	public function gamePeople()
	{
		if($this->request->isAjax())
		{
			$sqltype = Request::get('type2',1);
			$sortName = Request::get('sort','CollectDate');
			if($sortName == 'CollectDate'){
				$type = 2;
			}else{
				$type = 1;
			}
			$pagesize = Request::get('limit',20);
	    	$pageNum  = $this->request->get('offset');
  			if($pageNum == 0){
  				   $pageNo = 1;
  			}else{
  				   $pageNo = $pageNum/$pagesize+1;
  			}
			$kid = $this->request->get('kid');
			if($sqltype == 1){
				$sql = "{call [THGameScoreDB].[dbo].[PHP_User_onlineKindID2] (?, ?, ?, ?)}";
			}else{
				$sql = "{call [THGameScoreDB].[dbo].[PHP_User_onlineKindID] (?, ?, ?, ?)}";
			}
			$params = array($pagesize, $pageNo, $type, $kid);

			$arr    = Sqlsrv::getInstance()->queryProcedure($sql, $params);
			$ct = 0;
			for ($i=0; $i < count($arr); $i++) { 
				$arr[$i]['NickName'] = Conn::setCharu($arr[$i]['NickName']);
				$arr[$i]['ServerName'] = Conn::setCharu($arr[$i]['ServerName']);
				if(substr($arr[$i]['PlatformID'],0,1) == 1){
                  	$arr[$i]['device'] = '苹果';
                }else if(substr($arr[$i]['PlatformID'],0,1) == 2){
                  	$arr[$i]['device'] = '安卓';
                }else{
                  	$arr[$i]['device'] = 'PC';
                }
                $arr[$i]['z_score'] = number_format($arr[$i]['InsureScore']+$arr[$i]['Score']);
                $arr[$i]['InsureScore'] = number_format($arr[$i]['InsureScore']);
                $arr[$i]['Score'] = number_format($arr[$i]['Score']);
			}
			if(count($arr) > 0)
			{
				$ct = $arr[0]['ct'];
			}
			$result = array('total' => $ct, 'rows' => $arr);
    	  	return json($result);
		}
		return $this->view->fetch();
	}

	// 关联号查询
	public function queryRelevance()
	{
		if($this->request->isAjax())
		{

			$con = trim(Request::get('con',0));
			$con = Conn::setCharg($con);
			$type = Request::get('type',1);
			$ids = Request::get('ids',1);

			$pagesize = Request::get('limit',20);
	    	$pageNum  = $this->request->get('offset');
  			if($pageNum == 0){
  				   $pageNo = 1;
  			}else{
  				   $pageNo = $pageNum/$pagesize+1;
  			}

  			$sql = "{call [THGameScoreDB].[dbo].[PHP_User_queryRelevance] (?, ?, ?, ?, ?)}";
  			$params = array($type, $con, $ids, $pagesize, $pageNo);
  			$arr    = Sqlsrv::getInstance()->queryProcedure($sql, $params);
			$ct = 0;
			$games = 0;
			for ($i=0; $i < count($arr); $i++) { 
				$arr[$i]['NickName'] = Conn::setCharu($arr[$i]['NickName']);
				$arr[$i]['Accounts'] = Conn::setCharu($arr[$i]['Accounts']);
                $arr[$i]['z_score'] = number_format($arr[$i]['InsureScore']+$arr[$i]['Score']);
                $arr[$i]['InsureScore'] = number_format($arr[$i]['InsureScore']);
                $arr[$i]['Score'] = number_format($arr[$i]['Score']);
			}
			if(count($arr) > 0)
			{
				$ct = $arr[0]['ct'];
				$games = number_format($arr[0]['GameSums'] - $arr[0]['DezSum']+$arr[0]['scores']);
			}

			$result = array('total' => $ct, 'rows' => $arr, "extend" => ['games' => $games]);
    	  	return json($result);
		}
		return $this->view->fetch();
	}

	// 模拟器用户
	public function simulatorList()
	{
		if($this->request->isAjax())
		{
			$gid = Request::get('gid',0);
			$chid = Request::get('channel',0);

			$pagesize = Request::get('limit',20);
	    	$pageNum  = $this->request->get('offset');
  			if($pageNum == 0){
  				   $pageNo = 1;
  			}else{
  				   $pageNo = $pageNum/$pagesize+1;
  			}

  			$sql = "{call [THGameScoreDB].[dbo].[PHP_Supply_imitatorList] (?, ?, ?, ?)}";
  			$params = array($pagesize, $pageNo, $gid, $chid);
  			$arr    = Sqlsrv::getInstance()->queryProcedure($sql, $params);
			$ct = 0;
			for ($i=0; $i < count($arr); $i++) { 
				$arr[$i]['NickName'] = Conn::setCharu($arr[$i]['NickName']);
				$arr[$i]['Accounts'] = Conn::setCharu($arr[$i]['Accounts']);
                $arr[$i]['z_score'] = number_format($arr[$i]['InsureScore']+$arr[$i]['Score']);
                $arr[$i]['InsureScore'] = number_format($arr[$i]['InsureScore']);
                $arr[$i]['Score'] = number_format($arr[$i]['Score']);
			}
			if(count($arr) > 0)
			{
				$ct = $arr[0]['ct'];
			}

			$result = array('total' => $ct, 'rows' => $arr);
    	  	return json($result);
		}
		return $this->view->fetch();
	}

	// 注册
	public function registerList()
	{
		if($this->request->isAjax())
		{
			$tim = Request::get('tim',0);
			$chid = Request::get('channel',0);
			$type = Request::get('type') ?: 1;

			$t1 = $tim." 00:00:00";
			$t2 = $tim." 23:59:59";

			$pagesize = Request::get('limit',20);
	    	$pageNum  = $this->request->get('offset');
  			if($pageNum == 0){
  				   $pageNo = 1;
  			}else{
  				   $pageNo = $pageNum/$pagesize+1;
  			}

  			$sql = "{call [THGameScoreDB].[dbo].[PHP_Statistics_registerStatistics] (?, ?, ?, ?, ?, ?)}";
  			$params = array($pagesize, $pageNo, $chid, $type, $t1, $t2);
  			$arr    = Sqlsrv::getInstance()->queryProcedure($sql, $params);
			$ct = 0;
			$ipArea = new \app\common\Operation;
			for ($i=0; $i < count($arr); $i++) { 
				$arr[$i]['NickName'] = Conn::setCharu($arr[$i]['NickName']);
				$arr[$i]['Accounts'] = Conn::setCharu($arr[$i]['Accounts']);
                $arr[$i]['z_score'] = number_format($arr[$i]['InsureScore']+$arr[$i]['Score']);
                $arr[$i]['InsureScore'] = number_format($arr[$i]['InsureScore']);
                $arr[$i]['Score'] = number_format($arr[$i]['Score']);
                if(substr($arr[$i]['PlatformID'],0,1) == 1){
					$arr[$i]['device'] = '苹果';
				}else if(substr($arr[$i]['PlatformID'],0,1) == 2){
					$arr[$i]['device'] = '安卓';
				}else{
					$arr[$i]['device'] = 'PC';
				}
				$arr[$i]['ZrVipSum'] = number_format($arr[$i]['ZrVipSum']); // vip转入
				$arr[$i]['ZcVipSum'] = number_format($arr[$i]['ZcVipSum']);
				
        		$registArea = $ipArea->getArea($arr[$i]['RegisterIP']);
        		$arr[$i]['registArea'] = $registArea['country']." ".$registArea['area'];
			}
			if(count($arr) > 0)
			{
				$ct = $arr[0]['ct'];
			}

			$result = array('total' => $ct, 'rows' => $arr);
    	  	return json($result);
		}
		return $this->view->fetch();
	}
}