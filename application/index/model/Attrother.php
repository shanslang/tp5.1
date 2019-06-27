<?php

namespace app\index\model;

use think\Model;

class Attrother extends Model
{
	protected $connection = 'db_connect1';
	protected $table = 'Shop';
	protected $pk = 'ID';

	// 获取器定义存在的字段
	public function getTypeAttr($value)
	{
		$type = [1=>'钻石',2=>'金币',3=>'特惠礼包',4=>'首次礼包'];
		return $type[$value];
	}

	// 获取器定义不存在的字段
	public function getTypeTextAttr($value,$data)
	{
		$types = [1=>'钻石2',2=>'金币2',3=>'特惠礼包2',4=>'首次礼包2'];
		return $types[$data['Type']];
	}

	
}