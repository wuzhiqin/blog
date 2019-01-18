<?php
/*
 * 文章发布验证器
 */
namespace app\common\validate;

use think\Validate;

class Notice extends Validate
{
    //当前验证规则
    protected $rule = [
        'title|标题'=>[
            'require',
            'length' => '1,80',
        ],
        'content_desc|内容描述'=>[
            'require',
            'length' => '5,500'
        ],
        'content|内容'=>[
            'require',
        ],
        'keyword|关键字'=>[
            'length' => '2,28',
            'chsAlphaNum',
        ],
    ];
}