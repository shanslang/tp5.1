<?php
namespace app\common;

class Conn
{
	public function index()
	{
	    $serverName = config('database.hostname');
	    $Database = config('database.database');
	    $uid = config('database.username');
	    $psw = config('database.password');
		$connectionInfo = array( "Database"=>$Database, "UID"=>$uid, "PWD"=>$psw);
		$conn = sqlsrv_connect($serverName, $connectionInfo);
		return $conn;
	}
  
  	public static function setCharu($strings)
	{
		//return iconv('GBK', 'utf-8', $strings);
         return $strings;
	}

	public static function setCharg($strings)
	{
		//return iconv('utf-8', 'GBK', $strings);
        return $strings;
	}
}