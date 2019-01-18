<?php
/*
 * login_record表的用户模型
 */
namespace app\common\model;
use think\Model;

class LoginRecordModel extends Model
{
    protected $table = 'login_record'; //默认数据表
    protected $pk = 'id'; //默认主键
    //定义时间戳字段名：默认create_time和update_time,如果一致可省略
    //如果想关闭某时间戳字段，将值设置为false即可：$create_time = false;
    protected $autoWriteTimestamp = true;     //自动时间戳
    protected $createTime = 'login_time';    //创建时间字段
    protected $updateTime = false;
    protected $dateFormat = 'Y-m-d H:i:s';  //修改日期默认格式
    
    //开启自动设置
    protected $auto = [];
    protected $insert = ['login_time'];
}