<?php
/*
 * admin测试模块
 */
namespace app\admin\controller;

use app\common\controller\Base;
use app\common\model\LoginRecordModel;
use app\common\messages\SendMessages;
use app\common\model\AdminUserModel;

use  qc\api\QC;

class Test extends Base
{

    public function tt()
    {
        session_start();
        /* PHP SDK
         * @version 2.0.0
         * @author connect@qq.com
         * @copyright © 2013, Tencent Corporation. All rights reserved.
         */

        $qc = new QC();
        $qc->qq_login();
    }

    //异地登录测试
    public function test()
    {
        //这次登录的地址
        $citys = $this->ipAddress('137.25.215.0');
        $citys = $citys['city'];
       //获取数据库中上次登录的地址
       $address = LoginRecordModel::where('admin','admin')->order('login_time','desc')->limit(1)->value('address');
       $phone = AdminUserModel::where('admin_name','admin')->value('admin_phone');
       dump($address);
       if($address != ""){
           if($address != $citys){
               $SendMessages = new SendMessages();
               $ee = $SendMessages->sendTemplateSMS($phone,array("***您的账号在($address)登录,如非本人操作,请修改密码***",'10'),"1");
           }
       }     
    }
    
}