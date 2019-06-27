<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Request;
use think\Response;

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

// Route::get('hello/:name', 'index/hello');

// 访问http://localhost:8080/tp5/public/index.php/hh/hh.html
// Route::get('hh/:hh', 'TryTest/say')
// 	->ext('html');

// Route::get('hh/:hh', 'index/TryTest/say')
// 	->before(['\app\index\behavior\UserCheck']);

// Route::get('hh/:hh','index/TryTest/say')->response([
// 	'\app\index\behavior\Json',
// ]);

// Route::rule('hh/:hh','TryTest/say')
// 	->middleware('Auth');

// Route::get('hh/:hh', 'index/TryTest/say')->response(function($response){
// 	$response->contentType('application/json');
// });

// Route::get('hh/:hh', function(){
// 	return 'hello hhhh9';
// });


// Route::option('ext','html')->option('cache', 10);


// 访问http://localhost:8080/tp5/public/index.php/hh/hh.html 或 http://localhost:8080/tp5/public/index.php/hh/hh.xml
// Route::get('hh/:hh', 'TryTest/say')
// 	->ext('html|xml');

// 访问http://localhost:8080/tp5/public/index.php/hh/ss.hhh  （只要后缀不是denyExt()里的，加不加后缀都行）
// Route::get('hh/:hh', 'TryTest/say')
// 	->denyExt('jpg|png|gif');

//访问http://localhost:8080/tp5/public/index.php/hh/hh
// Route::get('hh/:hh', 'TryTest/say');


// Route::get('hh/:hh','TryTest/say',['ext'=>'html','https'=>true]);

// Route::get('index/:hh', 'TryTest/say')
//     ->ext('html')
//     ->https();

// 访问http://localhost:8080/tp5/public/index.php/hh/kk
// Route::rule('hh/:hh', function(Request $request, $hh){
// 	$method = $request->method(0);
// 	return '['.$method.']Hello,'.$hh;
// });

// Route::get('hh/:hh', function(Response $response, $hh){
// 	// var_dump($response);
// 	return $response
// 		->data('Hello,' . $hh)
// 		->code(200)
// 		->contentType('text/plain');
// });

// 访问http://localhost:8080/tp5/public/index.php/kj/shortcut
// Route::controller('kj','index/TryTest');

// Route::resource('blog','index/Blog');


Route::get('static', response()->code(404));

return [

];
