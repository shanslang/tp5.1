<?php
namespace app\common;
use think\facade\Log;

class Sqlsrv
{
	public $conn = '';
	private static $_instance = null;

	public static function getInstance()
	{
		if(empty(self::$_instance))
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct()
	{
		$serverName = config('database.hostname');
	    $Database = config('database.database');
	    $uid = config('database.username');
	    $psw = config('database.password');
	    $connectionInfo = array( "Database"=>$Database, "UID"=>$uid, "PWD"=>$psw);
	    try{
	    	$this->conn = sqlsrv_connect($serverName, $connectionInfo);
	    }catch(\Exception $e){
	    	$msg = $e->getMessage();
	    	Log::write($msg, 'SqlsrvConn');
	    }
		
		if($this->conn === false)
		{
			$err = json_endoce(sqlsrv_errors());
			Log::write($err, 'SqlsrvConn');
			return $err;
		}
	}

	public function queryProcedure($sql, $params = [])
	{
		$stmt = sqlsrv_query($this->conn, $sql, $params);
		if($stmt === false)
		{
			$err = json_encode(sqlsrv_errors());
			return $err;
		}
		$arr = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        	$arr[] = $row;
        }
        sqlsrv_free_stmt($stmt);  
        return $arr;
	}

	public function setProcedure($sql, $params = [])
	{
		$stmt = sqlsrv_query($this->conn, $sql, $params);
		if($stmt === false)
		{
			$err = json_encode(sqlsrv_errors());
			return $err;
		}
		$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        sqlsrv_free_stmt($stmt);  
        return $row;
	}
}