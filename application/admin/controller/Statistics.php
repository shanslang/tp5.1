<?php

namespace app\admin\controller;

use think\Controller;
use think\facade\Session;
use think\facade\Request;
use think\facade\Log;

class Statistics extends Controller
{
	protected function initialize()
	{
		if(!Session::has('admin')){
        	$this->error('请登录呦！', 'Login/index');
        }
	}

	public function getRecharge()
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
  			$time1 = date("Y-m-d",strtotime("-19 days"));

  			$start_date = Request::get('startTime') ?? $time1;
	    	$end_date = Request::get('endTime') ?? date('Y-m-d');

	    	if(!$start_date){
	    		$start_date = $time1;
	    	}
	    	if(!$end_date){
	    		$end_date = date('Y-m-d');
	    	}

	    	// Log::write($start_date,'start_date');

	    	$conn = new \app\common\Conn;
			$conn = $conn->index();
	    	$sql = "{call [THGameScoreDB].[dbo].[PHP_User_chargeCount] (?,?,?,?)}";
			$params = array($start_date, $end_date, $pagesize, $pageNo);
			$stmt = sqlsrv_query( $conn, $sql, $params);
			$arr = array();
			while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
				foreach ($row as $key => $value) {
					if($key != 'dates' and $key != 'curday'){
						$row[$key] = number_format($value);
					}
				}
				$arr[] = $row;
			}
			sqlsrv_free_stmt( $stmt);
			if(empty($arr)){
				$ct = 0;
				$z_sums = 0;
			}else{
				$ct = $arr[0]['ct'];
				$z_sums = number_format($arr[0]['z_sums']);
			}
			$result = array('total' => $ct, 'rows' => $arr, "extend" => ['z_sums' => $z_sums]);
			$this->assign('z_sums', $z_sums);
	  		return json($result);
		}
		return $this->view->fetch();
	}

	public function chartRecharge()
	{
		if($this->request->isAjax())
		{
			$time1 = date("Y-m-d",strtotime("-6 days"));

  			$start_date = Request::get('startTime') ?? $time1;
	    	$end_date = Request::get('endTime') ?? date('Y-m-d');

	    	$days = (strtotime($end_date)-strtotime($start_date))/86400;
	    	Log::write($days, 'DAYS');
	    	if($days > 6){
	    		$start_date = date('Y-m-d', strtotime('-1 week', strtotime($end_date)));
	    	}else if($days<1){
	    		$arrs['status'] = 2;
	    		return json($arrs);
	    	}

	    	$pagesize = 7;
	    	$pageNo = 1;

	    	$conn = new \app\common\Conn;
			$conn = $conn->index();
	    	$sql = "{call [THGameScoreDB].[dbo].[PHP_User_chargeCount] (?,?,?,?)}";
			$params = array($start_date, $end_date, $pagesize, $pageNo);
			$stmt = sqlsrv_query( $conn, $sql, $params);

			$legend = array('七分微信','ZCY支付宝','春启向阳支付宝','春启向阳快捷','七分支付宝');
			$arr = array();
			$ct = count($legend);
			for($i=0;$i< $ct; $i++){
				$arr[$i]['name'] = $legend[$i];
				$arr[$i]['type'] = 'line';
				$arr[$i]['stack'] = '总量';
				$arr[$i]['areaStyle'] = array();
			}

			$dates = array();
			while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
				$dates[] = $row['dates'];

				$arr[0]['data'][] = $row['3'] ?? 0;
				$arr[1]['data'][] = $row['35'] ?? 0;	
				$arr[2]['data'][] = $row['43'] ?? 0;
				$arr[3]['data'][] = $row['9'] ?? 0;
				$arr[4]['data'][] = $row['42'] ?? 0;
			}
			sqlsrv_free_stmt($stmt);
			$arrs['status'] = 1;
			$arrs['dates'] = $dates;
			$arrs['arr'] = $arr;
			$arrs['legend'] = $legend;
			// $z_data = json_encode($arrs,JSON_UNESCAPED_UNICODE);
			// Log::write($z_data, '总数据');
			return json($arrs);
		}
		return $this->view->fetch();
	}

	public function datas()
	{
		$pagesize = 7;
    	$pageNo = 1;

    	$time1 = date("Y-m-d",strtotime("-6 days"));

		$start_date = Request::get('startTime') ?? $time1;
    	$end_date = Request::get('endTime') ?? date('Y-m-d');

    	$conn = new \app\common\Conn;
		$conn = $conn->index();
    	$sql = "{call [THGameScoreDB].[dbo].[PHP_User_chargeCount] (?,?,?,?)}";
		$params = array($start_date, $end_date, $pagesize, $pageNo);
		$stmt = sqlsrv_query( $conn, $sql, $params);

		$arr = array();
		$arr[0]['name'] = '充值总';
		$arr[1]['name'] = '实卡';
		$arr[2]['name'] = '春启向阳支付宝';
		$arr[3]['name'] = '春启向阳快捷';
		$arr[4]['name'] = '七分支付宝';
		for ($i=0; $i < 5; $i++) { 
			$arr[$i]['type'] = 'line';
			$arr[$i]['stack'] = '总量';
			$arr[$i]['areaStyle'] = array();
		}

		$dates = array();
		while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
			$dates[] = $row['dates'];

			$arr[0]['data'][] = $row['sums'] ?? 0;
			$arr[1]['data'][] = $row['1'] ?? 0;	
			$arr[2]['data'][] = $row['43'] ?? 0;
			$arr[3]['data'][] = $row['9'] ?? 0;
			$arr[4]['data'][] = $row['42'] ?? 0;
		}
		sqlsrv_free_stmt( $stmt);

		$arrs['dates'] = $dates;
		$arrs['arr'] = $arr;
		$hh = json_encode($arrs,JSON_UNESCAPED_UNICODE);
		echo $hh;

	}
}