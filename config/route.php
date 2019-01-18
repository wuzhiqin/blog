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

//自定义路由规则，main的访问
use think\Route;
Route::rule('about','index/index/about');
Route::rule('lists','index/lists/lists');
Route::rule('keyword','index/lists/keyword');
Route::rule('tags','index/lists/tags');
Route::rule('slowLifeView','index/lists/slowLifeView');
Route::rule('slowLife','index/lists/slowLife');
Route::rule('listview','index/lists/listview');

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];
