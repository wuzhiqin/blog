<?php
/*
 * 测试模块
 */
namespace app\index\controller;

use app\common\controller\Base;

class Test extends Base
{
    //测试注册用户的验证器
    public function test()
    {
        $data = [
            'name' => 'wuzhiqin',
            'email'=> '1244@qq.cn',
            'password' => 'sad15412',
            'mobilephone'=> '13112568950'
        ];
        $rule = 'app\common\validate\User'; 
        $res = $this->validate($data, $rule);
        if(true !== $res){//false
            return $res;
        }else{
            echo '正确';
        }
    }
    
}