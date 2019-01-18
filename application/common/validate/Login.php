<?php
/*
 * 登录表的验证器
 */
namespace app\common\validate;

use think\Validate;

class Login extends Validate
{
    //当前验证规则
    protected $rule = [
        'admin_name|姓名'=>[
            'require',
            'length' => '5,20',
            'chsAlphaNum', //汉字，字母和数字
        ],
        'admin_password|密码'=>[
            'require',
            'length' => '5,18',
            'alphaNum',
        ],
    ];
}