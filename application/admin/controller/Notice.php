<?php
/*
 * 公告的增删改查控制器
 */
namespace app\admin\controller;

use app\common\controller\Base;
use think\Request;
use app\common\model\NoticeModel;

class Notice extends Base
{
    //显示公告信息
    public function index()
    {
        $this->antiJump();
        $count = NoticeModel::all();
        $data = NoticeModel::order('create_time','desc')->paginate(8);
        $count = count($count);
        $index_data = [
            'data'           =>  $data,
            'count'          =>  $count,
            'notice_active'  =>  'active',
        ];
        return $this->view->fetch('',$index_data);
    }
    //显示公告表单
    public function add()
    {
        $this->antiJump();
        $this->view->assign('notice_active','active'); //笨拙的方法，菜单被选中状态
        return $this->view->fetch();
    }
    //接收日记表单数据并入库
    public function addHandle()
    {
        if(Request::instance()->isAjax()){
            $data = Request::instance()->post();
            $rule = 'app\common\validate\Notice'; //日记发布验证规则
            $res = $this->validate($data,$rule);
            if(true !== $res){
                return ['status' => -1,'message'=>$res]; //验证规则不通过
            }else{
                if(NoticeModel::create($data)){
                    return ['status' => 1,'message'=>'日记发布成功！'];
                }else{
                    return ['status' => 0,'message'=>'日记发布失败，请检查！'];
                }
            }
        }else{
            return $this->error('非法请求!','/admin/Notice/index');
        }
    }
    //修改数据页面
    public function editdate()
    {
        $data = NoticeModel::get($_GET['id']);
        $editdate = [
            'notice_active'   =>   'active',
            'data'             =>   $data,
        ];
        return $this->view->fetch('',$editdate);
    }
    //接收数据并更新
    public function update()
    {
        if(Request::instance()->isAjax()){
            $data = Request::instance()->post();
            unset($data['files']);
            $rule = 'app\common\validate\Notice'; //文章发布验证规则
            $res = $this->validate($data,$rule);
            if(true !== $res){
                return ['status' => -1,'message'=>$res]; //验证规则不通过
            }else{
                if(NoticeModel::update($data)){
                    return ['status' => 1,'message'=>'日记修改成功！'];
                }else{
                    return ['status' => 0,'message'=>'日记修改失败，请检查！'];
                }
            }
        }else{
            return $this->error('非法请求!','/admin/Article/index');
        }
    }
    //删除一条日记
    public function delete()
    {
        if(Request::instance()->isAjax()){
            $data = Request::instance()->post();
            //根据主键删除
            if(NoticeModel::destroy($data)){
                return ['status' => 1];
            }
        }else{
            return $this->error('非法请求!','/admin/Notice/index');
        }
    }
    //删除选中日记
    public function deleteAll()
    {
        if(Request::instance()->isAjax()){
            $data = Request::instance()->post();
            //根据主键删除
            if(NoticeModel::destroy($data['checkbox'])){
                return ['status' => 1];
            }
        }else{
            return $this->error('非法请求!','/admin/Notice/index');
        }
    }
}