<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\facade\Request as res2;

class TryTest extends Controller
{

	protected $middleware = [
		'Hello' => ['only'	=> ['wares'] ],
	];

	public function say($hh)
	{
		echo 'hh'.$hh;
	}

	/**
	*使用注解路由,localhost:8080/tp5/public/index.php/annotation/2
	*@route('annotation/:r')
	*/
	public function annotationRoute($r)
	{
		return 'znnotation,'.$r;
	}

	/**
	 * @param int $name 数字类型
	 * @route('anno/:name','get')
	 *	->pattern(['name' => '\d+'])
	 */
	public function annotationPattern($name)
	{
		return 'This is annotation pattern'.$name;
	}

	public function hqu($id)
	{
		return 'get'.$id;
	}

	public function tjiao($id)
	{
		return 'post'.$id;
	}

	public function delss($id)
	{
		return 'del'.$id;
	}

	public function getShortcut()
	{
		return 'shortcut';
	}

	public function getFu()
	{
		return json(['name'=>'thinkphp','status'=>1]);  
	}

	public function getEvents()
	{
		// $event = \think\facade\App::controller('Blog', 'event');
		$event = controller('Blog', 'event');
		echo $event->update(0);
		return $event->delete(33);
	}

	public function facadeSta()
	{
		echo \think\facade\App::action('Blog/update', ['id' => 5], 'event');
	}

	public function handleFacade()
	{
		echo action('Blog/update',['id' => 6], 'event');
	}

	public function wares(Request $request)
	{
		var_dump($request);
		echo $request->hh;
		return $request->hello;
	}

	public function reFacase()
	{
		// return res2::param('name');
		echo request()->time();
		echo '<br/>'.request()->query();
		echo '<br/>'.time();
		return request()->param('name');
	}

	public function varIs()
	{
		// dump(res2::has('name','get'));
		// return res2::param('name');
		// return json(res2::param());
		// dump(res2::param(false));
		// dump(res2::param(true));
		// $hh = res2::only(['name' => 'hh']);
		echo input('route.name');
		echo res2::header('user-agent');
		// dump($hh);
	}

	public function archive($year, $month='01')
	{

		// return response('year='.$year.'&month='.$month);
		return redirect('red')->with('name','tp5');
	}

	public function red()
	{
		$name = session('name');
		return 'hello,'.$name.'!';
	}

	public function inUrl()
	{
		if(session('?complete')){
			session('complete', null);
			return '重定向完成，回到原点';
		}else{
			return redirect('redUlr')
				->with('name', 'hhhh')
				->remember();
		}
	}

	public function redUlr()
	{
		$name = session('name');
		return 'hello, '.$name . '! <br/><a href="index.php/index/TryTest/restore">点击回到来源地址</a>';
	}

	public function restore()
	{
		session('complete', true);
		return redirect()->restore();
	}

	public function download()
	{
		return download(env('root_path').'application/index/controller/hh.png', 'my');
		// $data = 'this is a test file';
		// return download($data, 'test.txt',true);
	}
}