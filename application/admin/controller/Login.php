<?php

namespace app\admin\controller;

use think\Controller;
use think\captcha\Captcha;
use think\Validate;
use think\facade\Log;
use think\Db;
// use PDO;
use think\facade\Session;

/**
 * 
 */
class Login extends Controller
{
	
	public function index()
	{
		$url = $this->request->get('url', 'Login/index');

        if(Session::has('admin')){
            $this->success('已登录过呦', 'Index/getOnlineUser');
        }

		if ($this->request->isPost()) {
			$username = $this->request->post('username');
            $password = $this->request->post('psw');
            $keeplogin = $this->request->post('keeplogin');
            $token = $this->request->post('__token__');
            $captcha = $this->request->post('captcha');

            $rule = [
                'username'  => 'require|length:3,30',
                'psw'       => 'require|length:3,30',
                '__token__' => 'token',
                'captcha'   => 'require|captcha',
            ];
            $data = [
                'username'  => $username,
                'psw'  		=> $password,
                '__token__' => $token,
                'captcha'   => $captcha,
            ];
            $validate = new Validate($rule, [], ['username' => '用户名', 'psw' => '密码', 'captcha' => '验证码']);
            $result = $validate->check($data);
            if (!$result) {
            	Log::write($validate->getError().'user = '.$username,'Login日志');
                $this->error($validate->getError(), $url, ['token' => $this->request->token()]);
            }
            $ip = $this->request->ip();
            $psw = md5($password);

			$conn = new \app\common\Conn;
			$conn = $conn->index(); 
            $sql = "{call [THGameScoreDB].[dbo].[PHP_Login_userCheck] (?,?,?)}";
            $params = array($username, $psw, $ip);
		    $stmt = sqlsrv_query( $conn, $sql, $params);
		    $row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);
            sqlsrv_free_stmt( $stmt);

            if($row['ret'] > 0){
                $row['msg'] = iconv('GBK', 'UTF-8', $row['msg']);
                Log::write($row['msg'].'user = '.$username,'Login日志');
                $this->error($row['msg']);
            }
		    
            Session::set('admin',$row);

            $this->success('登录成功','Index/getOnlineUser','',0);
		}else{
			return $this->fetch();
		}
		
	}

	public function loginout()
	{
		if(Session::has('admin')){
            Session::delete('admin');
        }
        $this->success('退出成功', 'index','',0);
	}

	public function verify()
    {
        $captcha = new Captcha();
        return $captcha->entry();    
    }
}