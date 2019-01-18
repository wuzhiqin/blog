<?php
/*
 * 文章发布验证器
 */
namespace app\common\validate;

use think\Validate;

class Article extends Validate
{
    //当前验证规则
    protected $rule = [
        'title|标题'=>[
            'require',
            'length' => '1,88',
        ],
        'content_desc|内容描述'=>[
            'require',
            'length' => '5,500',
        ],
        'content|内容'=>[
            'require',
        ],
        'tags|标签'=>[
            'length' => '2,24',
            'chsAlphaNum',
        ],
        'keyword|关键字'=>[
            'length' => '2,20',
            'chsAlphaNum',
        ],
    ];
}