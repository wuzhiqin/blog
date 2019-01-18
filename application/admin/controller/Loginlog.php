<?php
namespace app\admin\controller;

use app\common\controller\Base;
use app\common\model\LoginRecordModel;
use think\Request;
use think\Session;
class Loginlog extends Base
{
    //登录记录首页
    public function index()
    {
        $this->antiJump();
        $count = LoginRecordModel::all();
        $data = LoginRecordModel::paginate(8);
        $count = count($count);
        $index_data = [
            'data'           =>  $data,
            'count'          =>  $count,
            'loginlog_active'  =>  'active',
        ];
        return $this->view->fetch('',$index_data);
        return $this->view->fetch();
    }
    //删除单条登录记录
    public function delete()
    {
        if(Request::instance()->isAjax()){
            $data = Request::instance()->post();
            //根据主键删除
            if(LoginRecordModel::destroy($data)){
                return ['status' => 1];
            }
        }else{
            return $this->error('非法请求!','/admin/Loginlog/index');
        }
    }
    //清除所有登录记录
    public function deleteAll()
    {
        $sql = "DELETE FROM login_record WHERE 1=1";
        LoginRecordModel::query($sql);
        return ['status' => 1];
    }
    //清除本人登录记录
    public function deleteMyself()
    {
        $sql = "DELETE FROM login_record WHERE `admin`=?"; 
        $admin_info = Session::get('admin_info');
        LoginRecordModel::query($sql,[$admin_info['admin_name']]);
        return ['status' => 1];

    }
}