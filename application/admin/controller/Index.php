<?php

namespace app\admin\controller;

use think\Controller;
use think\facade\Session;
use think\facade\Request;
use think\facade\Log;
use think\Validate;
use app\admin\model\Gamescorelocker;

/**
 * 
 */
class Index extends Controller
{
	
	protected function initialize()
    {
        if(!Session::has('admin')){
        	$this->error('请登录呦！', 'Login/index');
        }
    }

    public function index()
    {
    	  if($this->request->isAjax()){
    		    $sel = $this->request->get('sel');
    		    $info = $this->request->get('info');
			      switch ($sel) {
				        case '1':
					      $ads = 'a.GameID';
					      break;
				    case '2':
					      $ads = 'a.Accounts';
					      break;
    				case '3':
    					  $ads = 'a.NickName';
    					  break;
    				case '4':
    					  $ads = 'a.UserID';
    					  break;
			      }

    			  $pagesize = Request::get('limit',20);
    	    	$pageNum = $this->request->get('offset');
      			if($pageNum == 0){
      				  $pageNo = 1;
      			}else{
      				  $pageNo = $pageNum/$pagesize+1;
      			}

      			$conn = new \app\common\Conn;
      			$conn = $conn->index();

      			$sql = "{call [THGameScoreDB].[dbo].[PHP_UserIndex] (?,?,?,?)}";
      			$params = array($ads, $info, $pagesize, $pageNo);
      			$stmt = sqlsrv_query( $conn, $sql, $params);
      			$arr = array();
      			while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
        				//$row['NickName'] = iconv('GBK', 'utf-8', $row['NickName']);
        				$row['Score'] = number_format($row['Score']);
        				$row['InsureScore'] = number_format($row['InsureScore']);
        				$row['gold_sum'] = number_format($row['gold_sum']);
        				$arr[] = $row;
      			}
    			  sqlsrv_free_stmt($stmt);

        		$result = array('total' => $arr[0]['ct'], 'rows' => $arr);
    	  		return json($result);
    	  }
    	  return $this->view->fetch();
    }
  
  	// 用户详细信息
    public function userInfo()
    {
    	  if($this->request->isPost()){
          		$sel = Request::post('sel');
          		$info = Request::post('info');
          		$likes = Request::post('likes');

    		      $rule = [
                  'info'      => 'require|max:32',
              ];

              $data = [
                  'info'  	=> $info,
              ];

              $validate = new Validate($rule, [], ['info' => '查询信息']);
              $result = $validate->check($data);
              if (!$result) {
                  $this->error($validate->getError());
              }

              $info = trim($info);
            //  $info = iconv('Utf-8', 'GBK', $info);

          		$conn = new \app\common\Conn;
      			  $conn = $conn->index(); 

        			$sql = "{call [THGameScoreDB].[dbo].[PHP_Index_userSearch] (?,?,?)}";
        			$params = array($sel, $info, $likes);
        			$stmt = sqlsrv_query( $conn, $sql, $params);
        			$row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);
        			sqlsrv_free_stmt($stmt);

        			if($row['ret'] == 1){
        				  $msg = '找不到'.$info;
        				  $this->error($msg);
        			}
        			if($row['ret'] == 2){
        				  $ins['sel'] = $sel;
        				  $ins['info'] = $info;
        				  // return redirect('index')->with('ins', $ins);
        				  echo "<script>window.location.href='/index.php/admin/index/index?sel=".$sel."&info=".$info."';</script>";exit;
        			}


        			//$row['Compellation'] = iconv('GBK', 'utf-8', $row['Compellation']);
        			//$row['NickName'] = iconv('GBK', 'utf-8', $row['NickName']);
        			$row['game_z'] = number_format($row['scores'] + $row['GameSum'] - $row['DezSum']);
        			$row['z_jb'] = number_format($row['Score'] + $row['InsureScore']);
        			$row['Score'] = number_format($row['Score']);
        			$row['zcCount'] = number_format($row['ZcPtCount']+$row['ZcVipCount']);
        			$row['InsureScore'] = number_format($row['InsureScore']);
        			$row['zrCount'] = number_format($row['ZrPtCount']+$row['ZrVipCount']);
        			$row['zcSum'] = number_format($row['ZcPtSum']+$row['ZcVipSum']);
        			$row['zrSum'] = number_format($row['ZrPtSum']+$row['ZrVipSum']);
        			$row['others'] = number_format($row['other']+$row['back']);

        			$ipArea = new \app\common\Operation;
        			$registArea = $ipArea->getArea($row['RegisterIP']);
        			$LastLogonArea = $ipArea->getArea($row['LastLogonIP']);
        			$row['registArea'] = $registArea['country']." ".$registArea['area'];
        			$row['LastLogonArea'] = $LastLogonArea['country']." ".$LastLogonArea['area']; Log::write($row['LastLogonArea'],'LastLogonArea');
        			$this->assign('row', $row);
        			return $this->view->fetch();
    	    }
    }
  
  	// 玩家信息
    public function userInfoUid()
    {
    	  if($this->request->isGet())
    	  {
        		$info = $this->request->get('uid');
        		$sel = 4;
        		$likes = 0;

        		$conn = new \app\common\Conn;
    			  $conn = $conn->index(); 

      			$sql = "{call [THGameScoreDB].[dbo].[PHP_Index_userSearch] (?,?,?)}";
      			$params = array($sel, $info, $likes);
      			$stmt = sqlsrv_query( $conn, $sql, $params);
      			$row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);
      			sqlsrv_free_stmt($stmt);

      			if($row['ret'] == 1){
      				  $msg = '找不到'.$info;
      				  $this->error($msg);
      			}
      			if($row['ret'] == 2){
      				  $ins['sel'] = $sel;
      				  $ins['info'] = $info;
      				  return redirect('index')->with('ins', $ins);
      			}

      			//$row['Compellation'] = iconv('GBK', 'utf-8', $row['Compellation']);
      			//$row['NickName'] = iconv('GBK', 'utf-8', $row['NickName']);
      			$row['game_z'] = number_format($row['scores'] + $row['GameSum'] - $row['DezSum']);
      			$row['z_jb'] = number_format($row['Score'] + $row['InsureScore']);
      			$row['Score'] = number_format($row['Score']);
      			$row['zcCount'] = number_format($row['ZcPtCount']+$row['ZcVipCount']);
      			$row['InsureScore'] = number_format($row['InsureScore']);
      			$row['zrCount'] = number_format($row['ZrPtCount']+$row['ZrVipCount']);
      			$row['zcSum'] = number_format($row['ZcPtSum']+$row['ZcVipSum']);
      			$row['zrSum'] = number_format($row['ZrPtSum']+$row['ZrVipSum']);
      			$row['others'] = number_format($row['other']+$row['back']+$row['emailGold']);

      			$ipArea = new \app\common\Operation;
      			$registArea = $ipArea->getArea($row['RegisterIP']);
      			$LastLogonArea = $ipArea->getArea($row['LastLogonIP']);
      			$row['registArea'] = $registArea['country']." ".$registArea['area'];
      			$row['LastLogonArea'] = $LastLogonArea['country']." ".$LastLogonArea['area'];
      			$this->assign('row', $row);
      			return $this->view->fetch('user_info');
    	  }
    }

    // 在线玩家
    public function getOnlineUser()
    {
    	if($this->request->isAjax()){
    		$pagesize = Request::get('limit',20);
	    	$pageNum = $this->request->get('offset');
  			if($pageNum == 0){
  				$pageNo = 1;
  			}else{
  				$pageNo = $pageNum/$pagesize+1;
  			}

  			$gid = Request::get('search') ?? 0;
  			$sort = Request::get('sort') ?? 'CollectDate';
  			if($sort == 'CollectDate'){
  				$order = 1;
  			}else{
  				$order = 0;
  			}

  			if(!$gid){$gid = 0;}

  			$conn = new \app\common\Conn;
			$conn = $conn->index(); 

			$sql = "{call [THGameScoreDB].[dbo].[PHP_Index_online] (?,?,?,?)}";
			$params = array($pagesize, $pageNo, $order, $gid);
			$stmt = sqlsrv_query( $conn, $sql, $params);

			$arr = array();
			while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
				// $row['NickName'] = iconv('GBK', 'Utf-8', $row['NickName']);
				// $row['Accounts'] = iconv('GBK', 'Utf-8', $row['Accounts']);
				// $row['ServerName'] = iconv('GBK', 'Utf-8', $row['ServerName']);
				$row['z_gold'] = number_format($row['Score'] + $row['InsureScore']);
				$row['Score'] = number_format($row['Score']);
				$row['InsureScore'] = number_format($row['InsureScore']);
				$leftnum = substr($row['PlatformID'],0,1);
				switch ($leftnum) {
					case '1':
						$row['device'] = '苹果';
						break;
					case '2':
						$row['device'] = '安卓';
						break;
					default:
						$row['device'] = 'PC';
						break;
				}
				$arr[] = $row;
			}
			sqlsrv_free_stmt($stmt);

			$ct = $arr[0]['ct'] ?? 0;
			$zong['mobileCt'] = $arr[0]['mobileCt'] ?? 0;
			$zong['ffCt'] = $arr[0]['ffCt'] ?? 0;
			$zong['ffAnd'] = $arr[0]['ffAnd'] ?? 0;
			$zong['ffPc'] = $arr[0]['ffPc'] ?? 0;
			$zong['ffIphone'] = $arr[0]['ffIphone'] ?? 0;
			$zong['pcCt'] = $arr[0]['pcCt'] ?? 0;
			$zong['andCt'] = $arr[0]['andCt'] ?? 0;
			$zong['iphoneCt'] = $arr[0]['iphoneCt'] ?? 0;
			$zong['gameGold'] = $arr[0]['gameGold'] ?? 0;
			$zong['bankGold'] = $arr[0]['bankGold'] ?? 0;
			$zong['z_score'] = number_format($zong['gameGold']+$zong['bankGold']);
			$zong['gameGold'] = number_format($zong['gameGold']);
			$zong['mfCt'] = $ct - $zong['ffCt'];
			$zong['mfAnd'] = $zong['andCt']-$zong['ffAnd'];
			$zong['mfPc'] = $zong['pcCt']-$zong['ffPc'];
			$zong['mfIphone'] = $zong['iphoneCt']-$zong['ffIphone'];
			$zong['ct'] = $ct;
			
			$result = array('total' => $ct, 'rows' => $arr, "extend" => ['zong' => $zong]);
	  		return json($result);
	  	}
		return $this->view->fetch();
    }

    public function kickPeople()
    {
    	if($this->request->isAjax()){
	    	$users = Request::get('arr');
	    	$users = implode(',', $users);
	    	$res = Gamescorelocker::where('UserID','in',$users)->delete();
	    	return json($res);
	    }
    }

    // 充值记录
    public function rechargeRecord()
    {
    	if($this->request->isAjax()){
	    	$GID = Request::get('search',0);
	    	$lx = Request::get('cardType',10);

	    	$pagesize = Request::get('limit',20);
	    	$pageNum = $this->request->get('offset');
  			if($pageNum == 0){
  				$pageNo = 1;
  			}else{
  				$pageNo = $pageNum/$pagesize+1;
  			}

	    	$czf = Request::get('rechargeParty',0);
	    	$time1 = date("Y-m-d",strtotime("-1 week")).' 00:00:00';
	    	$start_date = Request::get('startTime',$time1);
	    	$end_date = Request::get('endTime',date('Y-m-d H:i:s'));
	    	if(!$start_date){
	    		$start_date = $time1;
	    	}
	    	if(!$end_date){
	    		$end_date = date('Y-m-d').' 23:59:59';
	    	}
	    	$start_date = str_replace('T', ' ', $start_date);
	    	$end_date = str_replace('T', ' ', $end_date);

	    	$conn = new \app\common\Conn;
			$conn = $conn->index(); 

			$sql = "{call [THGameScoreDB].[dbo].[PHP_User_czList] (?,?,?,?,?,?,?)}";
			$params = array($pagesize, $pageNo, $GID, $lx, $czf, $start_date, $end_date);
			$stmt = sqlsrv_query( $conn, $sql, $params);
			$arr = array();
			while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
				// $row['NickName'] = iconv('GBK', 'Utf-8', $row['NickName']);
				// $row['ShareNote'] = iconv('GBK', 'Utf-8', $row['ShareNote']);
				$arr[] = $row;
			}
			sqlsrv_free_stmt($stmt);
			if(empty($arr)){
				$ct = 0;
				$z_recharge = 0;
			}else{
				$ct = $arr[0]['ct'];
				$z_recharge = $arr[0]['sums'];
			}
			$result = array('total' => $ct, 'rows' => $arr, "extend" => ['z_recharge' => $z_recharge]);
	  		return json($result);
  	    }
		return $this->view->fetch();
    }
  
  	// 修改用户密码
    public function editPsw()
    {
    	if($this->request->isPost())
    	{
    		$data = $this->request->post('obj');
    		$hh = json_encode($data);
    		// Log::write($data, 'DATA');
    		$ip = $this->request->ip();
    		$logpsw = strtoupper(md5($data['logpsw']));
    		$bankpsw = strtoupper(md5($data['bankpsw']));
    		$mid = Session::get('admin.UserID');
    		// Log::write($mid, 'mid');

    		$conn = new \app\common\Conn;
			$conn = $conn->index(); 

			$sql = "{call [THGameScoreDB].[dbo].[PHP_UserChangePWD_do] (?,?,?,?,?,?)}";
			$params = array($data['types'], $logpsw, $bankpsw, $data['UserIDs2'], $mid, $ip);
			$stmt = sqlsrv_query( $conn, $sql, $params);
			$row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);
			sqlsrv_free_stmt($stmt);
    		return $row['ret'];
    	}
    }
  
  	// 修改用户信息
   	public function EditInfo()
   	{
   		if($this->request->isPost())
   		{
   			$Uid = trim($this->request->post('Uid'));
   			$type = trim($this->request->post('type'));
   			$info = trim($this->request->post('info'));
   			//$info = iconv('Utf-8', 'GBK', $info);
   			$mid = Session::get('admin.UserID');

   			$conn = new \app\common\Conn;
			$conn = $conn->index();

			$sql = "{call [THGameScoreDB].[dbo].[PHP_UserEditInfo] (?,?,?,?)}";
			$params = array($info, $type, $Uid, $mid);
			$stmt = sqlsrv_query( $conn, $sql, $params);
			$row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);
			sqlsrv_free_stmt($stmt);
			//if($row['ret'] > 0)
			//{
				//$row['msg'] = iconv('GBK', 'Utf-8', $row['msg']);
			//}
    		return json($row);
   		}
   	}
  
  	public function yunVip()
   	{
   		if($this->request->isPost())
   		{
   			$uid = trim($this->request->post('uid'));
   			$vipGrade = trim($this->request->post('vipGrade'));
   			$mid = Session::get('admin.UserID');

   			$conn = new \app\common\Conn;
   			$conn = $conn->index();

   			$sql = "{call [THGameScoreDB].[dbo].[PHP_User_yunVip] (?,?,?)}";
   			$params = array($uid, $vipGrade, $mid);
   			$stmt = sqlsrv_query( $conn, $sql, $params);
   			$row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);
			sqlsrv_free_stmt($stmt);
    		return 1;
   		}
   	}
  
    // 进出房间总
   	public function GetInout()
   	{
   		if($this->request->isPost())
   		{
   			$uid = trim($this->request->post('uid'));

   			$conn = new \app\common\Conn;
   			$conn = $conn->index();

   			$sql = "{call [THGameScoreDB].[dbo].[PHP_UserInfo_4] (?)}";
   			$params = array($uid);
   			$stmt = sqlsrv_query($conn, $sql, $params);
   			$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
   			sqlsrv_free_stmt($stmt);
   			$score = number_format($row['s'] + $row['Insures']);
   			return $score;
   		}
   	}
  
    //  锁定，解锁
   	public function setLock()
   	{
   		if($this->request->isPost())
   		{
   			$type = trim($this->request->post('type'));
   			$uid = trim($this->request->post('uid'));
   			$mid = Session::get('admin.UserID');


   			$conn = new \app\common\Conn;
   			$conn = $conn->index();

   			$sql = "{call [THGameScoreDB].[dbo].[PHP_UserInfosuoding] (?,?,?)}";
   			$params = array($type, $uid, $mid);
   			$stmt = sqlsrv_query($conn, $sql, $params);
   			$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
   			sqlsrv_free_stmt($stmt);
   			return 1;
   		}
   	}
  
  	// 删除会员
   	public function delVip()
   	{
   		if($this->request->isPost())
   		{
   			$uid = trim($this->request->post('uid'));
   			$mid = Session::get('admin.UserID');

   			$conn = new \app\common\Conn;
   			$conn = $conn->index();

   			$sql = "{call [THGameScoreDB].[dbo].[PHP_UserDelVip] (?,?)}";
   			$params = array($uid, $mid);
   			$stmt = sqlsrv_query($conn, $sql, $params);
   			$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
   			sqlsrv_free_stmt($stmt);
   			return 1;
   		}
   	}
  
   // 封号，解封
   	public function fhao()
   	{
   		if($this->request->isPost())
   		{
   			$type = $this->request->post('type');
   			$uid  = $this->request->post('uid');
   			$reason = trim($this->request->post('reason'));
   			$mid  = Session::get('admin.UserID');

   			$reason = iconv('Utf-8', 'GBK', $reason);

   			$conn = new \app\common\Conn;
   			$conn = $conn->index();

   			$sql  = "{call [THGameScoreDB].[dbo].[PHP_UserInfofhao] (?,?,?,?)}";
   			$params = array($type, $uid, $mid, $reason);
   			$stmt = sqlsrv_query($conn, $sql, $params);
   			$row  = sqlsrv_fetch_array($stmt);
   			sqlsrv_free_stmt($stmt);
   			return 1;
   		}
   	}
  
  // 其他金币来源1
   	public function otherGold()
   	{
   		if($this->request->isAjax())
   		{
   			$uid = $this->request->get('uid');

   			$pagesize = Request::get('limit',20);
	    	$pageNum = $this->request->get('offset') ?? 0;
  			if($pageNum == 0){
  				$pageNo = 1;
  			}else{
  				$pageNo = $pageNum/$pagesize+1;
  			}
  			// Log::write($uid.'-'.$pagesize.'-'.$pageNum, 'Other');

			$ct = Db::table('PackageRecord')->where([['RecordType', 'eq', 2], ['GoodsID', 'eq', 501], ['UserID', 'eq', $uid]])->count();

			$sql = "select * from (select [UserID],[GoodsCount],[RecordNote],convert(varchar,[RecodrdDate],120) RecodrdDate,ROW_NUMBER() OVER(order by RecodrdDate desc ) AS RowNumber FROM [THAccountsDB].[dbo].[PackageRecord](nolock) where [RecordType] = 2 and [GoodsID] = 501 and [UserID] = $uid ) as k where RowNumber BETWEEN (($pageNo-1)*$pagesize+1) and (($pageNo-1)*$pagesize+$pagesize)";

			$arr = Db::query($sql);

			$result = array('total' => $ct, 'rows' => $arr, 'extend' => ['uid' => $uid]);
	  		return json($result);
   		}
   		return $this->view->fetch();
   	}
    
     // 其他金币来源2
   	public function otherGold2()
   	{
   		if($this->request->isAjax())
   		{
   			$uid = $this->request->get('uid');

   			$pagesize = Request::get('limit',20);
	    	$pageNum = $this->request->get('offset') ?? 0;
  			if($pageNum == 0){
  				$pageNo = 1;
  			}else{
  				$pageNo = $pageNum/$pagesize+1;
  			}

			$ct = Db::connect('db_connect3')->table('RecordGrantGameScore')->where([ ['UserID', 'eq', $uid]])->count();

			$sql = "select * from (select [UserID],[AddScore],[Reason],convert(varchar,[CollectDate],120) CollectDate,ROW_NUMBER() OVER(order by CollectDate desc ) AS RowNumber FROM [THRecordDB].[dbo].[RecordGrantGameScore](nolock) where [UserID] = $uid ) as k where RowNumber BETWEEN (($pageNo-1)*$pagesize+1) and (($pageNo-1)*$pagesize+$pagesize)";

			$arr = Db::query($sql);

			$result = array('total' => $ct, 'rows' => $arr);
	  		return json($result);
   		}
   	}

    // 邮件金币
    public function otherGold3()
    {
      if($this->request->isAjax())
      {
        $uid = $this->request->get('uid');

        $pagesize = Request::get('limit',20);
          $pageNum = $this->request->get('offset') ?? 0;
        if($pageNum == 0){
          $pageNo = 1;
        }else{
          $pageNo = $pageNum/$pagesize+1;
        }

        $ct = Db::connect('db_connect1')->table('RecordEmail')->where([ ['UserID', 'eq', $uid]])->count();
        $sql = "select * from (select [UserID],[gold],[Title],convert(varchar,[receiveTime],120) receiveTime,ROW_NUMBER() OVER(order by receiveTime desc ) AS RowNumber 
        FROM [THTreasureDB].[dbo].[RecordEmail](nolock) where isReceive = 1 and [UserID] = $uid ) as k where RowNumber BETWEEN (($pageNo-1)*$pagesize+1) and (($pageNo-1)*$pagesize+$pagesize)";
        $arr = Db::query($sql);

          $result = array('total' => $ct, 'rows' => $arr);
          return json($result);
      }
    }

    public function redirectGameID()
    {
    	$pagesize = Request::get('id',20);
    	return 'redirectGameID'.$pagesize;
    }

    // 订单号查询
    public function orderSearch()
    {
    	$type = Request::get('type');
    	$OrderID = Request::get('OrderID');
    	return $OrderID.'--'.$type;
    }

    // 实卡查询
    public function LivcardManage()
    {
    	$SerialID = Request::get('SerialID');
    	return $SerialID;
    }

    // 相同身份证号
   	public function identityCard()
   	{
   		if($this->request->isAjax())
   		{
   			$PassPortID = $this->request->get('PassPortID');

  			$conn = new \app\common\Conn;
  			$conn = $conn->index();

  			$sql = "{call [THGameScoreDB].[dbo].[PHP_UserInfo_5] (?)}";
  			$params = array($PassPortID);
  			$stmt = sqlsrv_query($conn, $sql, $params);
  			$arr = array();
  			$i = 0;
  			while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
  				++$i;
  				$row['rowid'] = $i;
  				$row['Score'] = number_format($row['Score']);
	    		$row['InsureScore'] = number_format($row['InsureScore']);
	    		//$row['NickName'] = iconv('GBK', 'UTF-8', $row['NickName']);
	    		$arr[] = $row;
  			}
   			sqlsrv_free_stmt($stmt);
   			$result = array('total' => count($arr), 'rows' => $arr);
	  		return json($result);
   		}
   	}

   	// 相同手机号
   	public function PhoneList()
   	{
   		if($this->request->isAjax())
   		{
   			$mobile = $this->request->get("mobile");

   			$conn = new \app\common\Conn;
  			$conn = $conn->index();

  			$sql = "{call [THGameScoreDB].[dbo].[PHP_UserPhonelist] (?)}";
  			$params = array($mobile);
  			$stmt = sqlsrv_query($conn, $sql, $params);
  			$arr = array();
  			$i = 0;
  			while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
  				++$i;
  				$row['rowid'] = $i;
  				$row['Score'] = number_format($row['Score']);
	    		$row['InsureScore'] = number_format($row['InsureScore']);
	    		//$row['NickName'] = iconv('GBK', 'UTF-8', $row['NickName']);
	    		$arr[] = $row;
  			}
   			sqlsrv_free_stmt($stmt);
   			$result = array('total' => count($arr), 'rows' => $arr);
	  		return json($result);
   		}
   	}

   	// 解绑资料
   	public function jiebang()
   	{
   		if($this->request->isPost())
   		{
   			$mid = Session::get('admin.UserID');
   			$uid = $this->request->post("uid");

   			$conn = new \app\common\Conn;
   			$conn = $conn->index();

   			$sql = "{call [THGameScoreDB].[dbo].[PHP_Userjiebang] (?,?)}";
   			$params = array($uid, $mid);
   			$stmt = sqlsrv_query($conn, $sql, $params);

   			$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
   			sqlsrv_free_stmt($stmt);
   			if($row['ret'] == 0)
   			{
   				return 1;
   			}
   		}
   	}
  
    // 绑定资料
   	public function binding()
   	{
   		if($this->request->isPost())
   		{
   			$obj  = $this->request->post('obj');
   			$ip   = $this->request->ip();
   			$mid  = Session::get('admin.UserID');
   			$name = $obj['names'];
   			$mobile = $obj['mobiles'];
   			$cardid = $obj['idCard'];
   			$uid 	= $obj['uid'];

   			$hh = json_encode($obj);

   			$conn = new \app\common\Conn;
   			$conn = $conn->index();

   			$sql    = "{call [THGameScoreDB].[dbo].[PHP_UserPerfectInformation] (?,?,?,?,?,?)}";
   			$params = array($uid, $mid, $ip, $name, $mobile, $cardid);
   			$stmt 	= sqlsrv_query($conn, $sql, $params);

   			$row    = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
   			sqlsrv_free_stmt($stmt);
   			return $row['ret'];
   		}
   	}

   	// 游戏所得
   	public function getGameSum()
   	{
   		if($this->request->isPost())
   		{
   			$uid = $this->request->post('uid');

   			$conn = new \app\common\Conn;
   			$conn = $conn->index();

   			$sql = "{call [THGameScoreDB].[dbo].[PHP_getGameSum] (?)}";
   			$params = array($uid);
   			$stmt   = sqlsrv_query($conn, $sql, $params);
   			$row 	= sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
   			sqlsrv_free_stmt($stmt);
   			$gamesore = number_format($row['scores']+$row['GameSum']-$row['DezSum']);
   			return $gamesore;
   		}
   	}

   	// 订单查询
   	public function queryOrder()
   	{
     		if($this->request->isAjax())
     		{   
            $cardType = Request::get('cardType',1);
            $info = Request::get('info','');
            $uid = Request::get('uid',0);
     			  $pagesize = Request::get('limit',20);
            $pageNum = $this->request->get('offset');
            if($pageNum == 0){
                $pageNo = 1;
            }else{
                $pageNo = $pageNum/$pagesize+1;
            }

            if($info != '')
            {
                $uid = $info;
            }

            $conn = new \app\Common\Conn;
            $conn = $conn->index();

            $sql  = "{call [THGameScoreDB].[dbo].[PHP_User_ordersearch] (?,?,?,?)}";
            $params = array($pageNo, $pagesize, $cardType, $uid); 
            Log::write($pageNo.'-'.$pagesize.'-'.$cardType.'-'.$uid, 'Params');
            $stmt   = sqlsrv_query($conn, $sql, $params);
            $arr = array();
            while($row  = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
            {
                $arr[] = $row;
            }
            sqlsrv_free_stmt($stmt);
            if(count($arr) > 0)
            {
                $ct = $arr[0]['ct'];
            }else{
                $ct = 0;
            }

            $result = array('total' => $ct, 'rows' => $arr);
            return json($result);
     		}
   		  return $this->view->fetch();
   	}

    // 设备详情
    public function deviceInfo()
    {
        if($this->request->isAjax())
        {
            $cardType = Request::get('cardType',1);
            $ckall = Request::get('ckall','');
            if(count($ckall)==0)
            {
                return 2;
            }
            $ckall = implode(',', $ckall);
            $uid = Request::get('uid',0);
            $pagesize = Request::get('limit',20);
            $pageNum = $this->request->get('offset');
            if($pageNum == 0){
                $pageNo = 1;
            }else{
                $pageNo = $pageNum/$pagesize+1;
            }

            $conn = new \app\common\Conn;
            $conn = $conn->index();

            $sql = "{call [THGameScoreDB].[dbo].[PHP_Index_deviceInfo] (?,?,?,?,?)}";
            $params = array($pagesize, $pageNo, $uid, $cardType, $ckall);
            $stmt = sqlsrv_query($conn, $sql, $params);
            $arr = array();
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
               // $row['NickName'] = iconv('GBK', 'utf-8', $row['NickName']);
                $row['z_gold']   = number_format($row['Score']+$row['InsureScore']);
                $row['Score']    = number_format($row['Score']);
                $row['InsureScore'] = number_format($row['InsureScore']);
                $arr[] = $row;
            }
            sqlsrv_free_stmt($stmt);
            if(count($arr) > 0)
            {
                $ct = $arr[0]['ct'];
            }else{
                $ct = 0;
            }

            $arr1 = Db::table('UserDeviceInfo')->where('UserID', $uid)->find();
            $arr1['deviceUniqueIdentifier'] = substr($arr1['deviceUniqueIdentifier'], 0,32);
            $arr1['systemMemorySize'] = $arr1['systemMemorySize'] / 1000;
            switch ($arr1['deviceType']) {
                case '0':
                    $arr1['dtype'] = 'PC';
                    break;
                case '16':
                    $arr1['dtype'] = '安卓';
                    break;
              
                default:
                    $arr1['dtype'] = '苹果';
                    break;
            }

            $result = array('total' => $ct, 'rows' => $arr, "extend" => ['arr1' => $arr1]);
            return json($result);
        }
        return $this->view->fetch();
    }

    // 仓库记录
    public function depotRecord()
    {   
        $uid = Request::get('uid',0);
        if($this->request->isAjax())
        {
            $uid = Request::get('uid',0);
            $cardType = Request::get('cardType',1);
            $t1 = Request::get('t1') ?: date('Y-m-d', strtotime('-2 days'));
            $t2 = Request::get('t2') ?: date('Y-m-d');
            $datetype = Request::get('datetype',0);

            if($datetype > 0){
                switch ($datetype) {
                  case '1':
                    $t1 = date('Y-m-d');
                    $t2 = date('Y-m-d');
                    break;
                  case '2':
                    $t1 = date('Y-m-d',strtotime('-1 days'));
                    $t2 = date('Y-m-d',strtotime('-1 days'));
                    break;
                  case '3':
                    $t1 = date("Y-m-d",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y")));
                    $t2 = date("Y-m-d",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y")));
                    break;
                  case '4':
                    $t1 = date("Y-m-d",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1-7,date("Y")));
                    $t2 = date("Y-m-d",mktime(23,59,59,date("m"),date("d")-date("w")+7-7,date("Y")));
                    break;
                }
            }

            $pagesize = Request::get('limit',20);
            $pageNum = $this->request->get('offset');
            if($pageNum == 0){
                $pageNo = 1;
            }else{
                $pageNo = $pageNum/$pagesize+1;
            }

            $conn = new \app\common\Conn;
            $conn = $conn->index();
            
            $sql = "{call [THGameScoreDB].[dbo].[PHP_User_jy] (?,?,?,?,?,?)}";
            $params = array($cardType, $uid, $pagesize, $pageNo, $t1, $t2);
            $stmt = sqlsrv_query($conn, $sql, $params);
            $arr = array();
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                if(($row['TradeType'] == 3 or $row['TradeType'] == 4) && $row['TargetUserID'] == $uid ){ //转入
                    $row['Gold'] = $row['TargetGold'];
                    $row['Bank'] = $row['TargetBank'] + $row['SwapScore'];
                }else{
                    if($row['TradeType'] == 1){ //存款
                      $row['Gold'] = $row['SourceGold'] - $row['SwapScore'];
                      $row['Bank'] = $row['SourceBank'] + $row['SwapScore'];
                      $row['TargetGameID'] = '';
                    }else if($row['TradeType'] == 2){ //取款
                      $row['Gold'] = $row['SourceGold'] + $row['SwapScore'];
                      $row['Bank'] = $row['SourceBank'] - $row['SwapScore'];
                      $row['TargetGameID'] = '';
                    }else{  //转出
                      $row['Gold'] = $row['SourceGold'];
                      $row['Bank'] = $row['SourceBank'] - $row['SwapScore'];
                    }
                }
                $row['z_jb'] = $row['Gold'] + $row['Bank'];
                $row['Gold'] = number_format($row['Gold']);
                $row['Bank'] = number_format($row['Bank']);
                $row['Revenue'] = number_format($row['Revenue']);
                $row['SwapScore'] = number_format($row['SwapScore']);
                $row['z_jb'] = number_format($row['z_jb']);
               // $row['TargetNickName'] = iconv("gbk","UTF-8",$row['TargetNickName']); 
                //$row['SourceNickname'] = iconv("gbk","UTF-8",$row['SourceNickname']); 
                $arr[] = $row;
            }
            sqlsrv_free_stmt($stmt);
            if(count($arr)>0){
                $ct = $arr[0]['ct'];
            }else{
                $ct = 0;
            }
            $arr1['t1'] = $t1;
            $arr1['t2'] = $t2;
            // Log::write($t1.'-'.$t2, 'para');
            $result = array('total' => $ct, 'rows' => $arr, "extend" => ['arr1' => $arr1]);
            return json($result);
        }
        $this->assign('uid', $uid);
        return $this->view->fetch(); 
    }

    // 游戏记录
    public function gameRecord()
    {
        $uid = Request::get('uid',0);
        if($this->request->isAjax())
        {
            // $uid = Request::get('uid',0);
            $t1 = Request::get('t1') ?: date('Y-m-d', strtotime('-2 days'));
            $t2 = Request::get('t2') ?: date('Y-m-d');
            $t3 = $t2." 23:59:59";

            $pagesize = Request::get('limit',20);
            $pageNum = $this->request->get('offset');
            if($pageNum == 0){
                $pageNo = 1;
            }else{
                $pageNo = $pageNum/$pagesize+1;
            }

            $conn = new \app\common\Conn;
            $conn = $conn->index();
            
            $sql = "{call [THGameScoreDB].[dbo].[PHP_User_yxrecord] (?,?,?,?,?)}";
            $params = array($pagesize, $pageNo, $uid, $t1, $t3);
            $stmt = sqlsrv_query($conn, $sql, $params);
            $arr = array();
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                //$row['KindName'] = iconv('GBK', 'UTF-8', $row['KindName'] );
                //$row['ServerName'] = iconv('GBK', 'UTF-8', $row['ServerName'] );
                $arr[] = $row;
            }
            sqlsrv_free_stmt($stmt);
            if(count($arr)>0){
                $ct = $arr[0]['ct'];
            }else{
                $ct = 0;
            }
            $arr1['t1'] = $t1;
            $arr1['t2'] = $t2;
            $result = array('total' => $ct, 'rows' => $arr, "extend" => ['arr1' => $arr1]);
            return json($result);
        }
        $this->assign('uid', $uid);
        return $this->view->fetch();
    }

    // 进出记录
    public function inoutRecord()
    {
        $uid = Request::get('uid',0);
        if($this->request->isAjax())
        {
            $KindID = Request::get('KindID') ?: 0;
            $t1 = Request::get('t1') ?: date('Y-m-d');
            $t2 = Request::get('t2') ?: date('Y-m-d');
            $t3 = $t2." 23:59:59";

            $pagesize = Request::get('limit',20);
            $pageNum = $this->request->get('offset');
            if($pageNum == 0){
                $pageNo = 1;
            }else{
                $pageNo = $pageNum/$pagesize+1;
            }

            $conn = new \app\common\Conn;
            $conn = $conn->index();

            $sql = "{call [THGameScoreDB].[dbo].[PHP_User_inout] (?,?,?,?,?,?)}";
            $params = array($KindID,$uid, $pagesize, $pageNo, $t1, $t3);
            $stmt = sqlsrv_query($conn, $sql, $params);
            $arr = array();
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
               // $row['ServerName'] = iconv('GBK', 'utf-8', $row['ServerName']);
                //$row['KindName'] = iconv('GBK', 'utf-8', $row['KindName']);
                $row['leav_z'] = $row['EnterScore'] + $row['EnterInsure'] + $row['Score'] + $row['Insure'];
                $row['leav_gold'] = $row['EnterScore'] + $row['Score'];
                $row['leav_Insure'] = $row['EnterInsure'] + $row['Insure'];
                $row['Score'] = $row['Score'] + $row['Insure'];
                $row['leav_gold'] = number_format($row['leav_gold']);
                $row['leav_Insure'] = number_format($row['leav_Insure']);
                $row['leav_z'] = number_format($row['leav_z']);
                $row['Score'] = number_format($row['Score']);
                $row['Insure'] = number_format($row['Insure']);
                $row['PlayTimeCount'] = number_format($row['PlayTimeCount']);
                $row['EnterScore'] = number_format($row['EnterScore']);
                $row['EnterInsure'] = number_format($row['EnterInsure']);
                $arr[] = $row;
            }
            sqlsrv_free_stmt($stmt);
            if(count($arr)>0){
                $ct = $arr[0]['ct'];
            }else{
                $ct = 0;
            }
            $arr1['t1'] = $t1;
            $arr1['t2'] = $t2;
            $arr1['z_score'] = $arr[0]['scores'];
            $result = array('total' => $ct, 'rows' => $arr, "extend" => ['arr1' => $arr1]);
            return json($result);
        }
        $this->assign('uid', $uid);
        return $this->view->fetch();
    }

    // 德州游戏记录
    public function dezRecord()
    {
        $uid = Request::get('uid',0);
        if($this->request->isAjax())
        {
            $t1 = Request::get('t1') ?: date('Y-m-d');
            $t2 = Request::get('t2') ?: date('Y-m-d');
            $t3 = $t2." 23:59:59";

            $pagesize = Request::get('limit',20);
            $pageNum = $this->request->get('offset');
            if($pageNum == 0){
                $pageNo = 1;
            }else{
                $pageNo = $pageNum/$pagesize+1;
            }

            $conn = new \app\common\Conn;
            $conn = $conn->index();

            $sql = "{call [THGameScoreDB].[dbo].[PHP_User_dez] (?,?,?,?,?)}";
            $params = array($pagesize, $pageNo, $uid, $t1, $t3);
            $stmt = sqlsrv_query($conn, $sql, $params);
            $arr = array();
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                if($row['UserID1'] != $uid){
                    $uID = $row['UserID1'];
                    $NickName = $row['NickName1'];
                    $row['NickName1'] = $row['NickName2'];
                    $row['NickName2'] = $NickName;
                    $row['UserID1'] = $row['UserID2'];
                    $row['UserID2'] = $uID;
                    $GID = $row['GameID1'];
                    $row['GameID1'] = $row['GameID2'];
                    $row['GameID2'] = $GID;
                    $score = $row['Score1'];
                    $row['Score1'] = $row['Score2'];
                    $row['Score2'] = $score;
                    $mem = $row['Member1'];
                    $row['Member1'] =  $row['Member2'];
                    $row['Member2'] = $mem;
                }
               // $row['KindName'] = iconv('GBK', 'UTF-8', $row['KindName']);
               //// $row['NickName1'] = iconv('GBK', 'UTF-8', $row['NickName1']);
                //$row['NickName2'] = iconv('GBK', 'UTF-8', $row['NickName2']);
                $row['Score1'] = number_format($row['Score1']);
                $arr[] = $row;
            }
            sqlsrv_free_stmt($stmt);
            if(count($arr)>0){
                $ct = $arr[0]['ct'];
            }else{
                $ct = 0;
            }
            $arr1['t1'] = $t1;
            $arr1['t2'] = $t2;
            $result = array('total' => $ct, 'rows' => $arr, "extend" => ['arr1' => $arr1]);
            return json($result);
        }
        $this->assign('uid', $uid);
        return $this->view->fetch();
    }
	
    // 进出统计
    public function inoutStatistic()
    {
        $uid = Request::get('uid',0);
        if($this->request->isAjax())
        {
            $conn = new \app\common\Conn;
            $conn = $conn->index();

            $sql = "{call [THGameScoreDB].[dbo].[PHP_User_inoutStatistics] (?)}";
            $params = array($uid);
            $stmt = sqlsrv_query($conn, $sql, $params);
            $arr = array();
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                //$row['KindName'] = iconv('GBK','UTF-8',$row['KindName']);
                $arr[] = $row;
            }
            sqlsrv_free_stmt($stmt);
            $result = array('total' => 25, 'rows' => $arr, "extend" => ['para' => $arr[0]['z_ct']]);
            return json($result);
        }
        $this->assign('uid', $uid);
        return $this->view->fetch();
    }

    // 活跃用户
    public function activeUser()
    {
        if($this->request->isAjax())
        {
            $t1 = Request::get('startTime') ?: date('Y-m-d');
            $t2 = Request::get('endTime') ?: date('Y-m-d');
            $t3 = $t2." 23:59:59";

            $isvip = Request::get('isvip', 3);
            $gid   = Request::get('gid') ?: 0;
            $channel = Request::get('channel') ?: 0;
            $yun   = Request::get('yun') ?: 128;
            $sort = Request::get('sort');

            if($sort == 'LastLogonDate'){
                $sort = 1;
            }else{
                $sort = 2;
            }

            $pagesize = Request::get('limit',20);
            $pageNum = $this->request->get('offset');
            if($pageNum == 0){
                $pageNo = 1;
            }else{
                $pageNo = $pageNum/$pagesize+1;
            }

            $conn = new \app\common\Conn;
            $conn = $conn->index();

            $sql = "{call [THGameScoreDB].[dbo].[PHP_Active2User] (?,?,?,?,?,?,?,?,?)}";
            $params = array($gid, $isvip, $sort, $yun, $channel, $pagesize, $pageNo, $t1, $t3);
            $stmt = sqlsrv_query($conn, $sql, $params);
            $arr = array();
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $row['z_gold'] = $row['InsureScore'] + $row['Score'];
                $row['z_gold'] = number_format($row['z_gold']);
                $row['Score'] = number_format($row['Score']);
                $row['InsureScore'] = number_format($row['InsureScore']);
                if(substr($row['PlatformID'],0,1) == 1){
                  $row['device'] = '苹果';
                }else if(substr($row['PlatformID'],0,1) == 2){
                  $row['device'] = '安卓';
                }else{
                  $row['device'] = 'PC';
                }
                $row['NickName'] = iconv('GBK', 'utf-8', $row['NickName']);
                $arr[] = $row;
            }
            sqlsrv_free_stmt($stmt);
            if(count($arr)>0){
                $ct = $arr[0]['ct'];
                $arr1['ct'] = $arr[0]['ct'];
            }else{
                $ct = 0;
                $arr1['ct'] = 0;
            }
            $arr1['t1'] = $t1;
            $arr1['t2'] = $t2;
            
            $result = array('total' => $ct, 'rows' => $arr, "extend" => ['arr1' => $arr1]);
            return json($result);
        }
        return $this->view->fetch();
    }

    // bunko输赢查询
    public function bunkoQuery()
    {
        if($this->request->isAjax())
        {
            $t1 = Request::get('startTime') ?: date('Y-m-d');
            $t2 = Request::get('endTime') ?: date('Y-m-d');
            $t3 = $t2." 23:59:59";

            $types = Request::get('types') ?: 0;
            $gid = Request::get('gid') ?: 0;
            $gamesid = Request::get('gamesid');

            if($gid != ''){


            $gid = iconv('utf-8','GBK',$gid);

            if($types == 1){
                $LoginMID = $gid;
                $gameid = 0;
                $RegIP = "";
                $LastLoginIP = "";
                $RegMID = "";
                $PassCode = "";
                $Account = "";
            }else if($types == 2){
                $LoginMID = "";
                $gameid = $gid;
                $RegIP = "";
                $LastLoginIP = "";
                $RegMID = "";
                $PassCode = "";
                $Account = "";
            }else if($types == 3){
                $LoginMID = "";
                $gameid = 0;
                $RegIP = $gid;
                $LastLoginIP = "";
                $RegMID = "";
                $PassCode = "";
                $Account = "";
            }else if($types == 4){
                $LoginMID = "";
                $gameid = 0;
                $RegIP = "";
                $LastLoginIP = $gid;
                $RegMID = "";
                $PassCode = "";
                $Account = "";
            }else if($types == 5){
                $LoginMID = "";
                $gameid = 0;
                $RegIP = "";
                $LastLoginIP = "";
                $RegMID = $gid;
                $PassCode = "";
                $Account = "";
            }else if($types == 6){
                $LoginMID = "";
                $gameid = 0;
                $RegIP = "";
                $LastLoginIP = "";
                $RegMID = "";
                $PassCode = $gid;
                $Account = "";
            }else if($types == 7){
                $LoginMID = "";
                $gameid = 0;
                $RegIP = "";
                $LastLoginIP = "";
                $RegMID = "";
                $PassCode = "";
                $Account = $gid;
            }

            $pagesize = Request::get('limit',20);
            $pageNum = $this->request->get('offset');
            if($pageNum == 0){
                $pageNo = 1;
            }else{
                $pageNo = $pageNum/$pagesize+1;
            }

            $conn = new \app\common\Conn;
            $conn = $conn->index();

            $sql = "{call [THGameScoreDB].[dbo].[PHP_User_winsearch] (?,?,?,?,?,?,?,?,?,?,?,?)}";
            $params = array($gamesid, $pagesize, $pageNo, $gameid, $LoginMID, $RegIP, $LastLoginIP, $RegMID,  $PassCode, $Account,$t1, $t3);
            $stmt = sqlsrv_query($conn, $sql, $params);
            $arr = array();
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
               // $row['NickName'] = iconv('GBK', 'utf-8', $row['NickName']);
               // $row['KindName'] = iconv('GBK', 'utf-8', $row['KindName']);
               // $row['ServerName'] = iconv('GBK', 'utf-8', $row['ServerName']);
                $row['Score']    = number_format($row['Score']);
                $arr[] = $row;
            }
            sqlsrv_free_stmt($stmt);
          }else{
              $arr = array();
          }
            if(count($arr)>0){
                $ct = $arr[0]['ct'];
                $arr1['z_score'] = number_format($arr[0]['z_score']);
            }else{
                $ct = 0;
                $arr1['z_score'] = 0;
            }
            $arr1['t1'] = $t1;
            $arr1['t2'] = $t2;
            
            $result = array('total' => $ct, 'rows' => $arr, "extend" => ['arr1' => $arr1]);
            return json($result);
        }
        return $this->view->fetch();
    }

    // 金币排行
    public function goldRanking()
    {
        if($this->request->isAjax())
        {
            $pagesize = Request::get('limit',20);
            $pageNum = $this->request->get('offset');
            if($pageNum == 0){
                $pageNo = 1;
            }else{
                $pageNo = $pageNum/$pagesize+1;
            }

            $types = Request::get('types', 1);
            $gid   = Request::get('gid') ?: 100;

            $conn = new \app\common\Conn;
            $conn = $conn->index();
            $sql = "{call [THGameScoreDB].[dbo].[PHP_User_goldRank] (?,?,?,?)}";
            $params = array($gid, $types, $pagesize, $pageNo);
            $stmt = sqlsrv_query($conn, $sql, $params);
            $arr = array();
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                //$row['NickName'] = iconv('GBK', 'utf-8', $row['NickName']);
                $row['z_gold'] = number_format($row['Score']+$row['InsureScore']);
                $row['Score']    = number_format($row['Score']);
                $row['InsureScore']    = number_format($row['InsureScore']);
                $arr[] = $row;
            }
            sqlsrv_free_stmt($stmt);
            $result = array('total' => $gid, 'rows' => $arr);
            return json($result);
        }
        return $this->view->fetch();
    }

    // 输赢排行
    public function winlosingRanking()
    {
         if($this->request->isAjax())
        {
            $pagesize = Request::get('limit',20);
            $pageNum = $this->request->get('offset');
            if($pageNum == 0){
                $pageNo = 1;
            }else{
                $pageNo = $pageNum/$pagesize+1;
            }

            $t1 = Request::get('startTime') ?: date('Y-m-d');
            $t2 = Request::get('endTime') ?: date('Y-m-d');

            $gid = Request::get('gid') ?: 0;
            $iswin = Request::get('iswin') ?: 0;
            $tis = Request::get('tis') ?: 10;
            switch ($tis) {
              case '今天':
                $t1 = date('Y-m-d');
                $t2 = date('Y-m-d');
                $ti = 2;
                break;
              case '昨天':
                $t1 = date('Y-m-d',strtotime('-1 days'));
                $t2 = date('Y-m-d',strtotime('-1 days'));
                break;
              case '本周':
                $t1 = date("Y-m-d",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y")));
                $t2 = date('Y-m-d',strtotime('-1 days'));
                break;
            }

            $ti = 1;
            if($t1 == $t2 && $t1 == date('Y-m-d'))
            {
               $ti = 2;
            }else if($t2 >= date('Y-m-d')){
                $t2 = date('Y-m-d', strtotime('-1 days'));
            }

            $sql = "{call [THGameScoreDB].[dbo].[PHP_Index_winorder] (?, ?, ?, ?, ?, ?)}";
            $params = array($gid, $iswin, $pagesize, $pageNo, $t1, $t2);
            $data = Sqlsrv::getInstance()->queryProcedure($sql, $params);
            $ct = count($data);
            for ($i=0; $i < $ct; $i++) { 
                //$data[$i]['Accounts'] = iconv('GBK','UTF-8',$data[$i]['Accounts']);
               // $data[$i]['NickName'] = iconv('GBK','UTF-8',$data[$i]['NickName']);
                if($ti == 1)
                {
                   $data[$i]['GameSum'] = number_format($data[$i]['GameSum']-$data[$i]['DezSum']);
                }else{
                   $data[$i]['GameSum'] = number_format($data[$i]['scores']);
                }
            }
            $arr1 = [
                't1' => $t1,
                't2' => $t2
            ];  
            if($ct>0){
              $total = $data[0]['ct'];
            }else{
              $total = 0;
            }
            $result = array('total' => $total, 'rows' => $data, 'extend' => ['arr1' => $arr1]);
            return json($result);
         }
         return $this->view->fetch();
    }

}