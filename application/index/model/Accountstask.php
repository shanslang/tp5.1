<?php

namespace app\index\model;

use think\Model;

class Accountstask extends Model
{
	protected $pk = 'ID';

	public function user()
	{
		return $this->belongsTo('Accountsinfo','UserID','UserID');
	}
}
