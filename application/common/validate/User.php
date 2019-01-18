<?php
/*
 * 注册表的验证器
 */
namespace app\common\validate;

use think\Validate;

class User extends Validate
{
    //当前验证规则
    protected $rule = [
        'name|姓名'=>[
            'require',
            'length' => '5,20',
            'chsAlphaNum', //汉字，字母和数字
        ],
        'email|邮箱'=> [
            'require',
            'email',
            //'unique' => 'blog_user',
        ],
        'password|密码'=>[
            'require',
            'min'       =>   6,
            'max'       =>   12,
            'alphaNum',
        ],  
        ['mobilephone',
            'require|max:11|/^1[3-8]{1}[0-9]{9}$/',
        '请输入手机号码|手机号码不能超过11个字符|手机号码格式不正确'],
    ];
}