<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Usertable;
use think\facade\{Session, Log};
use think\facade\Request;
use think\Db;
use app\common\Sqlsrv;

/**
 * 
 */
class Setmultiplex extends Controller
{
	protected $mid = 0;
	protected function initialize()
    {
        if(!Session::has('admin')){
        	$this->error('请登录呦！', 'Login/index');
        }else{
            $this->mid = Session::get('admin.UserID');
        }
    }

    // 设置虫子
    public function setCheat()
    {
    	if($this->request->isAjax()){
	    	$users = array_unique(Request::post('arr'));
            $type = 1;
	    	if(count($users) > 1){
	    	    $type = 2;
            }
	    	$users = implode(',', $users);
	    	$grade = Request::post('grade');
            $sql = "{call [THGameScoreDB].[dbo].[PHP_record_wormSets] (?, ?, ?, ?)}";
            $params = array($type, $this->mid, $grade, $users);
            $row = Sqlsrv::getInstance()->setProcedure($sql, $params);
	    	$res = 1;
	    	return json($res);
		}
    }

    // 封号
    public function fhao()
    {
        if($this->request->isAjax())
        {
            $type  = Request::post('type');
            $users = Request::post('arr');
            $users = implode(',', $users);
            $res   = Usertable::where('UserID','in', $users)->update(['Nullity' => $type]);
            return $res;
        }
    }

    // 游戏名
    public function gameName()
    {
        if($this->request->isAjax())
        {
            $res = Db::connect('db_connect2')->cache('gameName',600)->query('PHP_UserYxname');
            return json($res);
        }
    }

    // 背包物品
    public function backpackType()
    {
        if($this->request->isAjax())
        {
            $res = Db::table('PackageGoodsInfo')->cache('gameName',43200)->field('GoodsID,GoodsName')->select();
            return json($res);
        }
    }

    // 修改运维VIP
    public function editYunVip()
    {
        if($this->request->isAjax())
        {   
            $vip = Request::post('grade') ?: 0;
            if($vip < 0 or $vip > 127){
                return 3;
            }
            $arr = Request::post('arr');
            $users = implode(',', $arr);
            $res = Usertable::where('UserID','in', $users)->update(['VipServer' => $vip]);
            return 1;
        }
    }

    /**
     * @return \think\response\Json
     * @throws \think\Exception
     * 实卡类型
     */
    public function globalLivcard()
    {
        if($this->request->isPost())
        {
            $data = Db::connect('db_connect1')->table('GlobalLivcard')->cache('cardPrice',3600)->select();
            return json($data);
        }
    }
}