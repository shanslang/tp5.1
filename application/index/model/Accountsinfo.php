<?php

namespace app\index\model;

use think\Model;

class Accountsinfo extends Model
{
	// protected $connection = 'db_connect1';
	protected $pk = 'UserID';  // 设置主键

	static function staFun()
	{
		return 45;
	}

	public function seQuery()
	{
		return 'hh';
	}

	// 账号账号搜索器
	public function searchAccountsAttr($query, $value, $data)
	{
		$query->where('Accounts','like',$value.'%');
	}

	// 时间字段搜索器
	public function searchRegisterDateAttr($query,$value,$data)
	{
		$query->whereBetweenTime('RegisterDate', $value[0], $value[1]);
	}

	public function searchAccountsTwoAttr($query,$value,$data)
	{
		// echo 'value='.$value.'<br/>';
		// dump($data);exit;
		$query->where('Accounts','like','%'.$data['Accounts'].'%');
		if(isset($data['sort'])){
			$query->order($data['sort']);
		}
	}

	public function searchLastLogonDateAttr($query, $value, $data)
	{
		$query->whereBetweenTime('LastLogonDate',$value[0], $value[1]);
	}

	// 查询封装
	public function scopeSsl($query)
	{
		$query->where('Accounts','sslssl')->field('GameID,Accounts');
	}

	// 查询封装
	public function scopeUserID($query)
	{
		$query->where('UserID','=',61454)->limit(10);
	}

	//带参数的查询封装
	public function scopeAcc($query, $Accounts)
	{
		$query->where('Accounts','like', '%'.$Accounts.'%');
	}

	public function scopeUid($query, $uid)
	{
		$query->where('UserID','=',$uid);
	}

	// 定义全局查询范围
	public function base($query)
	{
		$query->where('IsAndroid',0);
	}

	// 关联模型
	public function profile()
	{
		return $this->hasOne('Accountstask','UserID','UserID');  // 关联模型名,外键，主键
	}

	// 一对一关联模型
	public function userScores()
	{
		return $this->hasOne('Gamescoreinfo','UserID','UserID');
	}
}