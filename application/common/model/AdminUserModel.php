<?php
/*
 * blog_user表的用户模型
 */
namespace app\common\model;
use think\Model;

class AdminUserModel extends Model
{
    protected $pk = 'admin_id'; //默认主键
    protected $table = 'blog_admin'; //默认数据表
    
    protected  $autoWriteTimestamp = true; //自动时间戳

    
    //获取器
//     public function getAdminPowerAttr($value)
//     {
//         $admin_power = ['1' => '启用','0' => '禁用'];
//         return $admin_power[$value];
//     }
}
