<?php
namespace app\index\model;

use think\Model;

/**
 * 
 */
class Gamescoreinfo extends Model
{
	protected $table = '[THTreasureDB].[dbo].[GameScoreInfo]';
	protected $pk = 'UserID';
	protected $connection = 'db_connect1';
}