<?php
namespace app\admin\Controller;

use think\Controller;
use think\facade\Session;
use think\facade\Request;
use think\Db;

class Vip extends Controller
{
	protected function initialize()
    {
        if(!Session::has('admin')){
      	    $this->error('请登录呦！', 'Login/index');
        }
    }

    // 转账记录
    public function transList()
    {
    	if($this->request->isAjax())
    	{
    		$t1 = Request::get('startTime') ?: date('Y-m-d');
            $t2 = Request::get('endTime') ?: date('Y-m-d');
            $t3 = $t2." 23:59:59";

            $gid = Request::get('gid') ?: 0;
            $channel = Request::get('channel') ?: 0;
            $tranmin = Request::get('tranmin') ?: 0;
            $tranmax = Request::get('tranmax') ?: 0;
            $type = Request::get('ids') ?: 6;

            $pagesize = Request::get('limit',20);
            $pageNum = $this->request->get('offset');
            if($pageNum == 0){
                $pageNo = 1;
            }else{
                $pageNo = $pageNum/$pagesize+1;
            }

            $sql = "{call [THGameScoreDB].[dbo].[PHP_VIp_trans] (?,?,?,?,?,?,?,?,?)}";
            $params = array($t1, $t3, $gid, $channel, $tranmin, $tranmax, $pagesize, $pageNo, $type);

            $conn = new \app\common\Conn;
            $conn = $conn->index();

            $stmt = sqlsrv_query($conn, $sql, $params);
            $arr = array();
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            	$row['SwapScore'] = number_format(abs($row['SwapScore']));
				$row['Revenue'] = number_format($row['Revenue']);
				$row['Sourcename'] = iconv('GBK', 'UTF-8', $row['Sourcename']);
				$row['Targetname'] = iconv('GBK', 'UTF-8', $row['Targetname']);
				$arr[] = $row;
            }
            sqlsrv_free_stmt($stmt);
            if(count($arr)>0){
                $ct = $arr[0]['ct'];
                $arr1['zr_sum'] = number_format($arr[0]['z_zr']);
                $arr1['zc_sum'] = number_format($arr[0]['z_zc']);
                $arr1['z_ss'] = number_format($arr[0]['z_ss']);
            }else{
                $ct = 0;
                $arr1['zr_sum'] = 0;
                $arr1['zc_sum'] = 0;
                $arr1['z_ss'] = 0;
            }
            $arr1['t1'] = $t1;
            $arr1['t2'] = $t2;
            
            $result = array('total' => $ct, 'rows' => $arr, "extend" => ['arr1' => $arr1]);
            return json($result);
    	}
    	return $this->view->fetch();
    }
}