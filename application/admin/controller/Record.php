<?php

namespace app\admin\controller;

use think\{Controller, Db};
use think\facade\{Log, Session, Validate, Request};
use app\common\{Conn, Sqlsrv, Operation};
use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory};

class Record extends Controller
{
	  private $mid;
	  protected function initialize()
    {
        if(!Session::has('admin')){
          	$this->error('请登录呦！', 'Login/index');
        }
        $this->mid = Session::get('admin.UserID');
    }

    // 充值未游戏
    public function recUngame()
    {
    	if($this->request->isAjax())
		{
			$tim1 = Request::get('tim1') ?: date('Y-m-d',strtotime('-3 days'));
			$tim2 = Request::get('tim2') ?: date('Y-m-d');

			$t1 = $tim1." 00:00:00";
			$t2 = $tim2." 23:59:59";

			$pagesize = Request::get('limit',20);
	    	$pageNum  = $this->request->get('offset');
  			if($pageNum == 0){
  				   $pageNo = 1;
  			}else{
  				   $pageNo = $pageNum/$pagesize+1;
  			}

  			$sql = "{call [THGameScoreDB].[dbo].[PHP_record_recUngame] (?, ?, ?, ?)}";
  			$params = array($pagesize, $pageNo, $t1, $t2);
  			$arr    = Sqlsrv::getInstance()->queryProcedure($sql, $params);
			$ct = 0;
			for ($i=0; $i < count($arr); $i++) { 
				$arr[$i]['NickName'] = Conn::setCharu($arr[$i]['NickName']);
				$arr[$i]['Accounts'] = Conn::setCharu($arr[$i]['Accounts']);
                $arr[$i]['z_score'] = number_format($arr[$i]['InsureScore']+$arr[$i]['Score']);
                $arr[$i]['InsureScore'] = number_format($arr[$i]['InsureScore']);
                $arr[$i]['Score'] = number_format($arr[$i]['Score']);
                $arr[$i]['zr'] = number_format($arr[$i]['ZrPtSum']+$arr[$i]['ZrVipSum']);
                $arr[$i]['zc'] = number_format($arr[$i]['ZcVipSum']+$arr[$i]['ZcPtSum']);
			}
			if(count($arr) > 0)
			{
				$ct = $arr[0]['ct'];
			}

			$result = array('total' => $ct, 'rows' => $arr);
    	  	return json($result);
		}
		return $this->view->fetch();
    }

    // 用户背包
    public function userBackpack()
    {
    	if($this->request->isAjax())
		{
			$gid = Request::get('gid') ?: 0;
			$type = Request::get('type') ?: 0;

			$pagesize = Request::get('limit',20);
	    	$pageNum  = $this->request->get('offset');
  			if($pageNum == 0){
  				   $pageNo = 1;
  			}else{
  				   $pageNo = $pageNum/$pagesize+1;
  			}

  			$sql = "{call [THGameScoreDB].[dbo].[PHP_record_userBackpack] (?, ?, ?, ?)}";
  			$params = array($pagesize, $pageNo, $gid, $type);
  			$arr    = Sqlsrv::getInstance()->queryProcedure($sql, $params);
			$ct = 0;
			for ($i=0; $i < count($arr); $i++) { 
				$arr[$i]['NickName'] = Conn::setCharu($arr[$i]['NickName']);
				$arr[$i]['Accounts'] = Conn::setCharu($arr[$i]['Accounts']);
				$arr[$i]['GoodsName'] = Conn::setCharu($arr[$i]['GoodsName']);
			}
			if(count($arr) > 0)
			{
				$ct = $arr[0]['ct'];
			}

			$result = array('total' => $ct, 'rows' => $arr);
    	  	return json($result);
		}
		return $this->view->fetch();
    }

    //  背包赠送
    public function sendBack()
    {
    	if($this->request->isPost())
    	{
    		  $grade = $this->request->post('grade');
    		  $wpsl = $this->request->post('wpsl');
    		  $arr = array_unique($this->request->post('arr'));
          $uid = Db::table('AccountsPackage')->field('UserID')->where('GoodsID', $grade)->where('UserID','in',$arr)->select();
          $uarr = array();
          $rearr = array();
          $resone = $this->mid.'赠送';
          for ($i=0; $i < count($arr); $i++) { 
            $rearr[$i]['UserID'] = $arr[$i];
            $rearr[$i]['RecordType'] = 2;
            $rearr[$i]['GoodsID'] = $grade;
            $rearr[$i]['GoodsCount'] = $wpsl;
            $rearr[$i]['RecordNote'] = $resone;
            $rearr[$i]['RecodrdDate'] = date('Y-m-d H:i:s');
          }
          for($i = 0;$i<count($uid); $i++)
          {
              $uarr[$i] =  $uid[$i]['UserID'];
          }
          $res = 0;
          $ins = 0;
          if(count($uarr)>0)
          {
            $res = Db::table('AccountsPackage')->where('GoodsID', $grade)->where('UserID','in',$arr)->setInc('GoodsCount', $wpsl);
            Log::write($res, 'RES');
          }
          $udiff = array_diff($arr, $uarr);
          if(count($udiff)>0){
              $data = array();
              for($i = 0; $i < count($udiff);$i++){
                $data[$i]['UserID'] = $udiff[$i];
                $data[$i]['GoodsID'] = $grade;
                $data[$i]['GoodsCount'] = $wpsl;
              }
              $ins = Db::table('AccountsPackage')->insertAll($data);
              Log::write($ins, 'INS');
          }
          $res2 = Db::table('PackageRecord')->insertAll($rearr);
          return $res+$ins;
    	}
    }

    //  背包物品删除
    public function backDel()
    {
        if($this->request->isPost()){
          $arr = $this->request->post('arr');
          $gid = $this->request->post('gid');
          $type = 2;
          if(count($arr) > 1)
          {
            $type = 1;
          }
          $Uids = implode(',', $arr);
          $goods = implode(',', $gid);
          $sql = "{call [THGameScoreDB].[dbo].[PHP_User_backDel] (?, ?, ?, ?)}";
          $params = array($Uids, $goods, $this->mid, $type);
          $row = Sqlsrv::getInstance()->setProcedure($sql, $params);
          return $row['ret'];
        }
    }

    public function backpackRecord()
    {
        if($this->request->isAjax())
        {
            $tim1 = Request::get('t1') ?: date('Y-m-d',strtotime('-3 days'));
            $tim2 = Request::get('t2') ?: date('Y-m-d');

            $t1 = $tim1." 00:00:00";
            $t2 = $tim2." 23:59:59";

            $pagesize = Request::get('limit',20);
            $pageNum  = $this->request->get('offset');
            if($pageNum == 0){
                 $pageNo = 1;
            }else{
                 $pageNo = $pageNum/$pagesize+1;
            }

            $isImport = 1;
            $gooid = $this->request->get('gooid');
            $remake = Conn::setCharg($this->request->get('remake'));
            $type = $this->request->get('type');
            $txts = $this->request->get('txts');
            $gid = 0;
            $accounts = '';
            if($type == 1){
              $gid = $txts;
            }else{
              $accounts =  Conn::setCharg($txts);
            }

           
            $sql = "{call [THGameScoreDB].[dbo].[PHP_User_backpackRecord] (?, ?, ?, ?, ?, ?, ?, ?, ?)}";
            $params = array($isImport, $pagesize, $pageNo,$gid,$accounts,$remake,$gooid, $t1, $t2);

            $arr    = Sqlsrv::getInstance()->queryProcedure($sql, $params);

            $ct = 0;
            $z_sl = 0;
            for ($i=0; $i < count($arr); $i++) { 
                $arr[$i]['NickName'] = Conn::setCharu($arr[$i]['NickName']);
                $arr[$i]['Accounts'] = Conn::setCharu($arr[$i]['Accounts']);
                $arr[$i]['GoodsName'] = Conn::setCharu($arr[$i]['GoodsName']);
                $arr[$i]['RecordNote'] = Conn::setCharu($arr[$i]['RecordNote']);
                $arr[$i]['GoodsCount'] = number_format($arr[$i]['GoodsCount']);
            }
            if(count($arr) > 0)
            {
              $ct = $arr[0]['ct'];
              $z_sl = number_format($arr[0]['z_sl']);
            }

            $result = array('total' => $ct, 'rows' => $arr, 'extend' => ['z_sl' => $z_sl]);
            return json($result);
        }
        return $this->view->fetch();
    }
  
    // 导出
    public function backpackRecordExport()
    {
        if($this->request->isPost())
        {
            $datap = input('post.');
			//Log::write($datap, 'Gdata');
            $tim1 = $datap['obj']['t1'] ?: date('Y-m-d',strtotime('-3 days'));
            $tim2 = $datap['obj']['t2'] ?: date('Y-m-d');

            $t1 = $tim1." 00:00:00";
            $t2 = $tim2." 23:59:59";

            $pagesize = 20;
            $pageNo = 1;

            $isImport = 0;
            $gooid = $datap['obj']['gooid'];
            $remake = Conn::setCharg($datap['obj']['remake']);
            $type = $datap['obj']['type'];
            $txts = $datap['obj']['txts'];
            $gid = 0;
            $accounts = '';
            if($type == 1){
              $gid = $txts;
            }else{
              $accounts =  Conn::setCharg($txts);
            }
            $sqlex = "{call [THGameScoreDB].[dbo].[PHP_User_backpackRecord] (?, ?, ?, ?, ?, ?, ?, ?, ?)}";
            $paramsex = array($isImport, $pagesize, $pageNo,$gid,$accounts,$remake,$gooid, $t1, $t2);
            $arr_ex   = Sqlsrv::getInstance()->queryProcedure($sqlex, $paramsex);
            $ex_row = count($arr_ex);
          	// Log::write($arr_ex, 'Export');
            if($ex_row > 0){
                $key_arr = array_keys($arr_ex[0]);
               // Log::write($key_arr, 'key_arr');
                $arrs_1 = array();
                foreach($arr_ex as $key => $val){
                	$arrs_1[] = array_values($val);
                }
                //Log::write($arrs_1, 'arrs_1');
            	$ex_fun = new Operation();
              	$hh = $ex_fun->test($arrs_1,$key_arr,'背包记录', '背包记录');
               // $title = ['id', 'name', 'sex'];
                Log::write($hh, 'rerurnnn');
              	return $hh;
            }        
           // echo 1;
        }
    }

   

    // 红包记录
    public function redRecord()
    {
        if($this->request->isAjax())
        {
            $tim1 = Request::get('t1') ?: date('Y-m-d',strtotime('-3 days'));
            $tim2 = Request::get('t2') ?: date('Y-m-d');

            $pagesize = Request::get('limit',20);
            $pageNum  = $this->request->get('offset');
            if($pageNum == 0){
                 $pageNo = 1;
            }else{
                 $pageNo = $pageNum/$pagesize+1;
            }

            $gooid = $this->request->get('gooid');
            $type = $this->request->get('type');
            $txts = $this->request->get('txts');
         
            $sql = "{call [THTreasureDB].[dbo].[PHP_record_RedRecord] (?, ?, ?, ?, ?, ?, ?)}";
            $params = array($pagesize, $pageNo,$tim1,$tim2,$gooid,$type, $txts);

            $arr    = Sqlsrv::getInstance()->queryProcedure($sql, $params);

            $ct = 0;
            $z_sl = 0;
            if(count($arr) > 0)
            {
              $ct = $arr[0]['ct'];
              $z_sl = number_format($arr[0]['z_amount']);
            }
            $result = array('total' => $ct, 'rows' => $arr, 'extend' => ['z_sl' => $z_sl]);
            return json($result); 
        }
        return $this->view->fetch();
    }
  
    // 话费记录
    public function fareRecord()
    {
        if($this->request->isAjax())
        {
            $tim1 = Request::get('t1') ?: date('Y-m-d',strtotime('-3 days'));
            $tim2 = Request::get('t2') ?: date('Y-m-d');

            $pagesize = Request::get('limit',20);
            $pageNum  = $this->request->get('offset');
            if($pageNum == 0){
                 $pageNo = 1;
            }else{
                 $pageNo = $pageNum/$pagesize+1;
            }

            $success = $this->request->get('success');
            $type = $this->request->get('type');
            $txts = $this->request->get('txts');
           
            $sql = "{call [THGameScoreDB].[dbo].[PHP_record_fareRecord] (?, ?, ?, ?, ?, ?, ?)}";
            $params = array($pagesize, $pageNo,$tim1,$tim2,$success,$type, $txts);
			Log::write($params, 'Params');
            $arr    = Sqlsrv::getInstance()->queryProcedure($sql, $params);

            $ct = 0;
            $z_sl = 0;
            if(count($arr) > 0)
            {
                $ct = $arr[0]['ct'];
                $z_sl = number_format($arr[0]['moneys']);
            }

            $result = array('total' => $ct, 'rows' => $arr, 'extend' => ['z_sl' => $z_sl]);
            return json($result); 
        }
        return $this->view->fetch();
    }
  
    // 话费记录修改
    public function editFare()
    {
        if($this->request->isPost())
        {
            $rid = $this->request->post('reid');
            $remark = $this->request->post('remark');
            $rs = Db::table('PackageExchangeFee')->where('RecordID', $rid)->update(['Success' => 1, 'SuccessNote' => $remark]);
          	// Log::write($rs, 'RS');
            return $rs;
        }
    }
  
    // 虫子用户
    public function worn()
    {
        if($this->request->isAjax())
        {
            $pagesize = Request::get('limit',20);
            $pageNum  = $this->request->get('offset');
            if($pageNum == 0){
                 $pageNo = 1;
            }else{
                 $pageNo = $pageNum/$pagesize+1;
            }

            $gid = $this->request->get('gid') ?: 0;
            $CheatLevel = $this->request->get('CheatLevel') ?: 0;
           
            $sql = "{call [THGameScoreDB].[dbo].[PHP_Plat_worm] (?, ?, ?, ?)}";
            $params = array($pagesize, $pageNo,$gid,$CheatLevel);

            $arr    = Sqlsrv::getInstance()->queryProcedure($sql, $params);

            $ct = 0;
            if(count($arr) > 0)
            {
                $ct = $arr[0]['ct'];
            }

            $result = array('total' => $ct, 'rows' => $arr);
            Log::write($result,'res');
            return json($result); 
        }
        return $this->view->fetch();
    }
  
  	// 设置虫子
    public function setCheat()
    {
        if($this->request->isPost())
        {
            $hs = $this->request->post('arr');
            //Log::write($hs, 'cshs');
            $gid = $hs['gid'] ?: 0;
            $xz = $hs['xz'] ?? array();
            $glpc = $hs['glpc'] ?? 0;
            $type = 1;
            if(in_array(1,$xz) && in_array(2,$xz) && !in_array(3,$xz)){
              $type = 1;
            }else if(!in_array(1,$xz) && in_array(2,$xz) && in_array(3,$xz)){
              $type = 2;
            }else if(in_array(1,$xz) && !in_array(2,$xz) && in_array(3,$xz)){
              $type = 3;
            }else if(!in_array(1,$xz) && !in_array(2,$xz) && in_array(3,$xz)){
              $type = 4;
            }else if(!in_array(1,$xz) && in_array(2,$xz) && !in_array(3,$xz)){
              $type = 5;
            }else if(in_array(1,$xz) && !in_array(2,$xz) && !in_array(3,$xz)){
              $type = 6;
            }else if(count($xz) == 3){
              $type = 7;
            }else{
              $type = 8;
            }
            $sb = 0;
            $sql = "{call [THGameScoreDB].[dbo].[PHP_plat_wormSet] (?, ?, ?, ?, ?, ?)}";
            $params = array($type, $hs['CheatLevel'],$gid,$this->mid,$sb,$glpc);
            $row    = Sqlsrv::getInstance()->setProcedure($sql, $params);
            return json($row);
        }
    }
  
    // 虫子设定记录表
    public function wornSetrecord()
    {
       if($this->request->isAjax())
       {
          $pagesize = Request::get('limit',20);
          $pageNum  = $this->request->get('offset');
        // Log::write($pageNum,'page');
          if($pageNum == 0){
               $pageNo = 1;
          }else{
               $pageNo = $pageNum/$pagesize+1;
          }
          $sql = "{call [THGameScoreDB].[dbo].[PHP_plat_worm1Set] (?, ?)}";
          $params = array($pagesize, $pageNo);

          $arr    = Sqlsrv::getInstance()->queryProcedure($sql, $params);

          $ct = 0;
          if(count($arr) > 0)
          {
              $ct = $arr[0]['ct'];
          }

          $result = array('total' => $ct, 'rows' => $arr);
          return json($result);
       }
       return $this->view->fetch();
    }
  
    // 删除虫子设置
    public function delWormset(){
        if($this->request->isPost())
        {
            $ck = $_POST['arr'];
            Log::write($ck,'ck');
            $type = 1;
            if(count($ck)>1){
              $type = 2;
            }
            $ids = implode(',', $ck);

            $sql = "{call [THGameScoreDB].[dbo].[PHP_Plat_delWormset] (?, ?)}";
            $params = array($type, $ids);
            $row    = Sqlsrv::getInstance()->setProcedure($sql, $params);
            return json($row); 
        }        
    }
  
    // 关联虫子保护用户
    public function glwornbh()
    {
        if($this->request->isAjax())
        {
            $tim1 = Request::get('t1') ?: date('Y-m-d',strtotime('-3 days'));
            $tim2 = Request::get('t2') ?: date('Y-m-d');

            $pagesize = Request::get('limit',20);
            $pageNum  = $this->request->get('offset');
            if($pageNum == 0){
                 $pageNo = 1;
            }else{
                 $pageNo = $pageNum/$pagesize+1;
            }

            $gid = $this->request->get('gid');
           
            $sql = "{call [THGameScoreDB].[dbo].[PHP_Supply_glwormbh] (?, ?, ?, ?, ?)}";
            $params = array($pagesize, $pageNo, $gid, $tim1,$tim2);

            $arr    = Sqlsrv::getInstance()->queryProcedure($sql, $params);
            $rs = count($arr);
            for($i=0;$i<$rs;$i++)
            {
                $arr[$i]['z_score'] = number_format($arr[$i]['Score']+$arr[$i]['InsureScore']);
                $arr[$i]['Score'] = number_format($arr[$i]['Score']);
                $arr[$i]['InsureScore'] = number_format($arr[$i]['InsureScore']);
            }

            $ct = 0;
            if(count($arr) > 0)
            {
                $ct = $arr[0]['ct'];
            }

            $result = array('total' => $ct, 'rows' => $arr);
            return json($result); 
        }
        return $this->view->fetch();
    }
  
    // 删除关联保护用户
    public function delRelation(){
        if($this->request->isPost())
        {
            $ck = $_POST['arr'];
            Log::write($ck,'ckgl');
            $type = 1;
            if(count($ck)>1){
              $type = 2;
            }
            $ids = implode(',', $ck);

            $sql = "{call [THGameScoreDB].[dbo].[PHP_Supply_del_glwormbh] (?, ?)}";
            $params = array($ids, $type);
            $row    = Sqlsrv::getInstance()->setProcedure($sql, $params);
            return json($row); 
        }        
    }
  
    // 虫子等级胜率表
    public function wornSet()
    {
       if($this->request->isAjax()){
           $arr = Db::connect('db_connect2')->query('PHP_Plat_wormGrade');
           $result = array('rows' => $arr);
           return json($result);
       }
       return $this->view->fetch();
    }

    /**
     * @return bool
     * @throws \think\Exception
     * 虫子胜率修改
     */
    public function wornGradeSet()
    {
        if($this->request->isPost())
        {
            $rule = [
                'wormG'  => 'require|number|between:1,3',
                'sl'     => 'require|number|between:0,1000',
            ];
            $validate    = Validate::make($rule);
            $result      = $validate->check(Request::post());
            if(!$result){
                $returnd['msg'] = $validate->getError();
                $returnd['ret'] = 1;
                return json($returnd);
            }
            $up = Db::connect('db_connect3')->table('CheatUserCFG')->where('CheatLevel', Request::post('wormG'))->update([
                'nControlPer' => Request::post('sl'),
                'LastModify'  => date('Y-m-d H:i:s')
                ]);
            if($up > 0)
            {
                $returnd['msg'] = '修改成功';
                $returnd['ret'] = 0;
                return json($returnd);
            }else{
                $returnd['msg'] = '请重试';
                $returnd['ret'] = 1;
                return json($returnd);
            }
        }
    }

    /**
     * @return array|string|\think\response\Json
     * @throws \Exception
     * 实卡批次信息
     */
    public function realCard()
    {
        if($this->request->isAjax())
        {
            $rule = [
                'cardnum'   => 'max:31',
                'f_cha'     => 'max:31',
                't1'        => 'date',
                't2'        => 'date',
            ];
            $cs       = array('cardnum' => Request::get('cardnum'),
                'f_cha'     => Request::get('f_cha'),
                't1'        => Request::get('t1') ?: date('Y-m-d', strtotime('-1 week')),
                't2'        => Request::get('t2') ?: date('Y-m-d')
                );
            $validate = Validate::make($rule);
            $res      = $validate->check($cs);
            if(!$res)
            {
                Log::write($validate->getError(), 'realCard cs err');
                $result = array('total' => 0, 'rows' => array(), 'extend' => ['status' => 1, 'errmsg' => $validate->getError(), 't2' => date('Y-m-d')]);
                return json($result);
            }
            $pagesize = Request::get('limit',20);
            $pageNum  = $this->request->get('offset');
            if($pageNum == 0){
                $pageNo = 1;
            }else{
                $pageNo = $pageNum/$pagesize+1;
            }
            $sql    = "{call [THGameScoreDB].[dbo].[PHP_record_CardList] (?, ?, ?, ?, ?, ?)}";
            $params = array($pagesize, $pageNo, $cs['t1'], $cs['t2'], $cs['cardnum'], $cs['f_cha']);
            $arr    = Sqlsrv::getInstance()->queryProcedure($sql, $params);
            $rs     = count($arr);
            for($i=0;$i<$rs;$i++)
            {
                $arr[$i]['z_score'] = number_format($arr[$i]['BuildCount']*$arr[$i]['CardPrice']);
                $arr[$i]['Score']   = number_format($arr[$i]['Score']);
            }

            $ct = 0;
            if(count($arr) > 0)
            {
                $ct = $arr[0]['ct'];
            }

            $result = array('total' => $ct, 'rows' => $arr, 'extend' => ['status' => 0, 'errmsg' => '', 't2' => date('Y-m-d')]);
            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * @return string
     * @throws \think\Exception
     * 实卡生成页面
     */
    public function buildCard()
    {
        $res = db::connect('db_connect1')->table('GlobalLivcard')->select();
        $expireDate = date('Y-m-d', strtotime('+1 month'));
        $this->assign('expireDate', $expireDate);
        $this->assign('data', $res);
        return $this->view->fetch();
    }

    /**
     * @throws \think\Exception
     * 实卡生成
     */
    public function buildCard2()
    {
        if($this->request->isPost())
        {
            $rule = [
                'p_cardType'    => 'require|number',
                'cardCount'     => 'require|number|between:1,10000',
                'p_diamondCt'   => 'require|float|min:1',
                'p_goldCt'      => 'require|number|min:0',
                'useRange'      => 'require|number|between:0,2',
                'p_Sales'       => 'max:15',
                'p_expireDate'  => 'require|date',
                'p_beginChar'   => 'alpha|max:1',
                'pswType'       => 'require',
                'p_priceCard'   => 'require|float|min:0'
            ];
            $validate = Validate::make($rule);
            $res      = $validate->check(Request::post());
            if(!$res)
            {
                Log::write(Request::post(), 'buildCard2 cs err');
                return $this->error($validate->getError());
            }

            Db::connect('db_connect1')->transaction(function () {
                $isChar = Db::connect('db_connect2')->query('PHP_QueryLetter');
                $beginChar = Request::post('p_beginChar');
                if(!empty($isChar))
                {
                    if($beginChar == '')
                    {
                        $beginChar = chr(rand(97,122));
                        if(in_array($beginChar, $isChar))
                        {
                            $beginChar = chr(rand(97,122));
                        }
                    }else{
                        if(in_array($beginChar, $isChar))
                        {
                            return $this->error('请换个开始字符');
                        }
                    }
                }
                $cardLen     = 15; // 长度必须为15
                $carnum1     = $beginChar.date('md');
                $carlen2     = $cardLen - strlen($carnum1);
                $admibname   = Session::get('admin.Username');
                $remark      = "管理员【{$admibname}】于 ".date('Y-m-d')." 生成";
                $data_Stream = array(
                    'AdminName'     => $admibname,
                    'CardTypeID'    => Request::post('p_cardType'),
                    'CardPrice'     => Request::post('p_priceCard'),
                    'Currency'      => Request::post('p_diamondCt'),
                    'Score'         => Request::post('p_goldCt'),
                    'BuildCount'    => Request::post('cardCount'),
                    'BuildAddr'     => $this->request->ip(),
                    'BuildDate'     => date('Y-m-d H:i:s'),
                    'DownLoadCount' => 0,
                    'NoteInfo'      => $remark
                );
                Log::write($data_Stream, 'cscs');
                $buildId = Db::connect('db_connect1')->table('LivcardBuildStream')->insertGetId($data_Stream);
                Log::write($buildId, 'buildId');
                $buildTime = date('Y-m-d H:i:s');
                $data_Associator = [
                        'SerialID'   => '',
                        'Password'   => '',
                        'BuildID'    => $buildId,
                        'CardTypeID' => Request::post('p_cardType'),
                        'CardPrice'  => Request::post('p_priceCard'),
                        'Currency'   => Request::post('p_diamondCt'),
                        'Score'      => Request::post('p_goldCt'),
                        'ValidDate'  => Request::post('p_expireDate'),
                        'BuildDate'  => $buildTime,
                        'UseRange'   => Request::post('useRange'),
                        'SalesPerson' => Request::post('p_Sales'),
                        'Nullity'    => 0,
                ];

                $build_ct = Request::post('cardCount');
                $insert_data = array();
                for($i=0; $i < $build_ct; $i++){
                    $data_Associator['SerialID'] = $carnum1.Operation::buildRandom($carlen2);
                    $insert_data[$i] = $data_Associator;
                }
                $insert_data = array_unique($insert_data);
                $ct = $build_ct - count($insert_data);
                for($i=0; $i < $ct; $i++){
                    $data_Associator['SerialID'] = Operation::buildRandom($carlen2);
                    $insert_data[] = $data_Associator;
                }
//                Log::write($insert_data, 'data2');
                $res = Db::connect('db_connect1')->table('LivcardAssociator')->data($insert_data)->limit(1000)->insertAll();
                Log::write($res, 'build res');
            });
            if($res>0){
                return $this->success('生成成功');
            }else{
                return $this->error('生成失败');
            }
        }
    }
}