<?php
/*
 * 栏目的增删改查控制器
 */
namespace app\admin\controller;

use app\common\controller\Base;
use think\Request;
use app\common\model\ArticleModel;

class Category extends Base
{
    //显示栏目信息
    public function index()
    {
        $this->view->assign('category_active','active'); //笨拙的方法，菜单被选中状态
        return $this->view->fetch();
    }
    //接收栏目表单数据并入库
    public function addHandle()
    {
        if(Request::instance()->isAjax()){
            $data = Request::instance()->post();
            $rule = 'app\common\validate\Article'; //文章发布验证规则
            $res = $this->validate($data,$rule);
            if(true !== $res){
                return ['status' => -1,'message'=>$res]; //验证规则不通过
            }else{
                if(ArticleModel::create($data)){
                    return ['status' => 1,'message'=>'文章发布成功！'];
                }else{
                    return ['status' => 0,'message'=>'文章发布失败，请检查！'];
                }
            }
        }else{
            return $this->error('非法请求!','/admin/Article/index');
        }
    }
    //修改数据页面
    public function editdate()
    {
        return $this->view->fetch();
    }
    //接收数据并更新
    public function update()
    {
        if(Request::instance()->isAjax()){
            $data = Request::instance()->post();
            $rule = 'app\common\validate\Article'; //文章发布验证规则
            $res = $this->validate($data,$rule);
            if(true !== $res){
                return ['status' => -1,'message'=>$res]; //验证规则不通过
            }else{
                if(ArticleModel::update($data)){
                    return ['status' => 1,'message'=>'文章修改成功！'];
                }else{
                    return ['status' => 0,'message'=>'文章修改失败，请检查！'];
                }
            }
        }else{
            return $this->error('非法请求!','/admin/Article/index');
        }
    }
}