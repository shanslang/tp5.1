<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\Accountsinfo;
use app\index\model\Attrother;
use app\index\model\Accountstask;
use think\facade\Log;
use think\captcha\Captcha;


/**
 * 
 */
class ConnSql extends Controller
{

	public function _empty($name)
	{
		return '地址错误';
	}

	public function index()
	{
		// $hh = Db::table('AccountsInfo')->where('UserID','like',61454)->select();
		// $hh = db('Accountsinfo')->where('UserID','61454')->value('NickName');
		// $hh = db('Accountsinfo')->where('infoMobile','13687173533')->column('NickName,GameID','UserID');
		// $hh=db('Accountsinfo')->where('infoMobile','13687173533')->cursor();
		// foreach ($hh as $user) {
		// 	echo $user['UserID'];
		// }
		// $hh = db('Accountsinfo')->where('infoMobile','13687173533')->nolock()->select();
		// $hh = Db::table('Accountsinfo')->whereRaw("UserID=:UserID",['UserID' => [61456,\PDO::PARAM_INT]])->select();
		// $hh = Db::table('Accountsinfo')->field(['UserID','left(NickName,2)' => 'nick'])->whereRaw("UserID=:UserID",['UserID' => [61456,\PDO::PARAM_INT]])->select();
		// $hh = Db::table('Accountsinfo')->field(true)->whereRaw("UserID=:UserID",['UserID' => [61456,\PDO::PARAM_INT]])->select();
		// $hh = Db::table('Accountsinfo')->field('GameID',true)->whereRaw("UserID=:UserID",['UserID' => [61456,\PDO::PARAM_INT]])->select();
		// $hh = Db::table('Accountsinfo')->field(['GameID','UserID'])->whereRaw("infoMobile=:infoMobile",['infoMobile' => '13687173533'])->limit(2)->select();
		// $hh = Db::table('Accountsinfo')->field(['UserID'])->whereRaw("UserID<=:UserID",['UserID' => [100,\PDO::PARAM_INT]])->limit(10)->page(1)->select();
		// $hh = Db::table('Accountsinfo(nolock)')
		// 	->alias('a')
		// 	->leftJoin('[THTreasureDB].[dbo].[ShareDetailInfo](nolock) b','a.UserID = b.UserID')
		// 	->field(['a.GameID','b.DetailID'])
		// 	->limit(10)
		// 	->select();
		
		// $hh = Db::table('Accountsinfo(nolock)')->cache('key',360)->find();
		// $caches = \think\facade\Cache::get('key');
		// var_dump($caches);
		// dump($hh);

		// $subsql = Db::table('Accountsinfo(nolock)')
		// 		->whereRaw('UserID=:UserID',['UserID' => [61456,\PDO::PARAM_INT]])
		// 		->field('max(GameID) hh')
		// 		->group('UserID')
		// 		->buildSql();
		// var_dump($subsql);

		// db('Accountsinfo')->where('infoMobile','13687173533')->chunk(1, function($users){
		// 	dump($users);
		// },'UserID','desc');
		// Db::event('before_find', function($query){
		// 	// var_dump($query);
		// 	echo \think\facade\App::version(); // 查看tp的具体版本号
		// 	echo "<br/>hh<br/>";
		// });

		// $res = Db::table('[THTreasureDB].[dbo].[RecordUserInout](nolock)')->field('ID')->whereExists(function($query){
		// 	$query->table('Accountsinfo(nolock)')->field('UserID')->whereRaw('infoMobile=:infoMobile',['infoMobile' => '13687173533']);
		// })->find();

		// $res = Db::query("select ID FROM [THTreasureDB].[dbo].[Shop](nolock) where [isUse] = ? and [Type] = ?",[1,1]);
		// $res = Db::query("select ID FROM [THTreasureDB].[dbo].[Shop](nolock) where isUse=:isUse and  Type=:Type",['Type' => 2, 'isUse' => 1]);

		// $res = Db::name('Accountsinfo(nolock)')->field(['NickName','GameID','Isandroid'])->withAttr('IsAndroid', function($value, $data){
		// 	// var_dump($data);
		// 	// $
		// 	// echo '<br/>----------';
		// 	$text = [1 => '机器人hh', 0 => '真人'];
		// 	return $text[$data['Isandroid']];
		// })->limit(10)->select();
		// var_dump($res);

		// 监听sql
		// $sql = "select top 10 NickName from Accountsinfo(nolock) where IsAndroid=0";
		// Db::listen(function($sql, $time, $explain){
		// 	echo $sql.'['.$time.'s]';
		// 	dump($explain);
		// });
		// Db::query($sql);

		//调用存储过程
		// $sql2 = '[THGameScoreDB].[dbo].[PHP_IntRoomList]';
		// Db::listen(function($sql, $time, $explain){
		// 	echo $sql.'['.$time.'s]';
		// 	dump($explain);
		// });
		// $result = Db::query($sql2);
		// foreach ($result as $res) {
		// 	var_dump($res);
		// }
		// dump($result);

		// 使用数据集
		// $res = Db::name('Accountsinfo(nolock)')->fetchCollection()->field(['NickName','GameID'])->limit(10)->select();
		// $item = 4020
		// echo count($res);
		// dump($res);

	}

	// 使用模型查询
	public function useModel()
	{
		// $user = Accountsinfo::get(61456);
		// $user = Accountsinfo::where('infoMobile', '13687173533')->field(['GameID','NickName'])->find();
		// $user = Accountsinfo::where('infoMobile', '3567356675436')->findOrEmpty();
		// echo $user->NickName;
		// dump($user);
		// echo Accountsinfo::staFun();  // 调用静态方法
		// $hh = new Accountsinfo();
		// return $hh->seQuery();

		// $hh = Accountsinfo::where('IsAndroid',0)->field(['NickName','GameID'])->limit(3)->all();
		// dump($hh);
		// foreach ($hh as $key => $value) {
		// 	echo $value->NickName;
		// }

		// $user = Accountsinfo::getByNickname('shzshz');
		// dump($user);

		// $ct = Accountsinfo::where('IsAndroid',0)->count();
		// echo $ct;

		// 使用游标查询
		// foreach (Accountsinfo::where('GameID','in',[61456,61454])->cursor() as $user) {
		// 	dump($user);
		// 	echo $user->UserID;
		// }

		// 缓存查询
		// $user = Accountsinfo::get(61454,'','getca');
		// dump($user);

		// $user = Attrother::get(1);
		// echo $user->Type;
		// echo '<br/>'.$user->getData('Type'); //定义了获取器的情况下，获取数据表中的原始数据

		// $user = Attrother::get(1);
		// echo $user->TypeText;

		// 动态获取器
		// $hh = Accountsinfo::withAttr('IsAndroid', function($value, $data){
		// 	$hh = [0=>'真人',1=>'机器人'];
		// 	return $hh[$data['IsAndroid']];
		// })->field(['IsAndroid','NickName','GameID'])->where('UserID','in',[1,61454,61456])->select();
		// dump($hh);
		// foreach ($hh as $key => $value) {
		// 	echo $value->IsAndroid.'='.$value->getData('IsAndroid').'<br/>';
		// }

		// 账号，时间搜索器
		// $hh = Accountsinfo::withSearch(['Accounts','RegisterDate'], [
		// 	'Accounts'			=> 'kiss',
		// 	'RegisterDate'		=> ['2018-02-03','2018-04-09']
		// ])->select();
		// dump($hh);

		// $hh = Accountsinfo::withSearch(['AccountsTwo','LastLogonDate','IsAndroid'], [
		// 	'Accounts'		    => 'ssl',
		// 	'LastLogonDate'		=> ['2019-01-01','2019-03-09'],
		// 	'IsAndroid'			=> 0,
		// 	'sort'				=> ['UserID' => 'desc'],
		// ])->select();
		// dump($hh);


		// 查询封装
		// $hh = Accountsinfo::scope('ssl')->find();
		// $hh = Accountsinfo::scope(['ssl','UserID'])->select();
		// $hh = Accountsinfo::acc('ssl')->uid(61454)->select();
		// $hh = Accountsinfo::scope(function($query){
		// 	$query->where('UserID','=',61454);
		// })->select();

		// $hh = Accountsinfo::get(61454);
		// $hh = Accountsinfo::useGlobalScope(false)->where('UserID',61454)->select();

		// $hh = Accountsinfo::find(61454);
		// dump($hh->append(['status_text'])->toArray());

		// 关联模型
		$hh = Accountsinfo::get(61456);
		$hh2 = $hh->profile;
		// dump($hh2);
		echo $hh->profile->TaskID;
	}

	// 使用关联模型
	public function useGlmx()
	{
		// $hh = Accountstask::get(31);
		// echo $hh->user->NickName;

		$hh = Accountstask::where('ID','>',34)->limit(10)->select();
		// dump($hh);
		foreach ($hh as $profile) {
			echo $profile->user->NickName.' ';
		}
	}

	// 一对一模型
	public function hasOneModel()
	{
		// $hh = Accountsinfo::get(61454);
		// echo $hh->user_scores->Score;

		// $hh = Accountsinfo::hasWhere('userScores', ['Score'=>960852])->select();
		// $hh = Accountsinfo::hasWhere('userScores', function($query){
		// 	$query->where('Score','=',960852);
		// })->select();
		// dump($hh);

		// 预载入查询
		// $hh = Accountsinfo::with('user_scores')->limit(3)->select();
		// $hh = Accountsinfo::with(['user_scores' => function($query){
		// 	$query->field('UserID,Score,LastLogonIP');
		// }])->limit(2)->select();
		// foreach ($hh as $user) {
		// 	echo $user->user_scores->Score.'<br/>';
		// }

		$this->assign('nums',9);
		$hh['kk'] = 9;
		return $this->fetch('has_one_model',$hh);
	}

	public function hhs()
	{
		// exception('异常消息', 10006);
		Log::write('测试日志信息，这是警告级别，并且实时写入','notice');
		$content = '{name}-{email}';
		return $this->display($content, ['name' => 'thinkphp', 'email' => 'thinkphp@qq.com']);
	}

	public function verify()
    {
        $captcha = new Captcha();
        return $captcha->entry();    
    }

    public function htmlPage()
    {
    	// return $this->fetch('html_page');
    	echo config('database.hostname');
    }

    public function conns()
    {
    	$test = new \app\common\Conn;
		return $test->index(); 
    }
	
}