<?php
/*
 * blog_article表
 * 文章
 */
namespace app\common\model;
use think\Model;

class ArticleModel extends Model
{
    protected $table = 'blog_article'; //默认数据表
    protected $pk = 'id'; //默认主键
    //定义时间戳字段名：默认create_time和update_time,如果一致可省略
    //如果想关闭某时间戳字段，将值设置为false即可：$create_time = false;
    protected $autoWriteTimestamp = true;     //自动时间戳
    protected $createTime = 'create_time';    //创建时间字段
    protected $updateTime = 'update_time';    //更新时间字段
    protected $dateFormat = 'Y-m-d';  //修改日期默认格式
    
    //开启自动设置
    protected $auto = []; //无论是新增还是更新都要设置的字段
    protected $insert = ['create_time'];
    protected $update = ['update_time'];
}