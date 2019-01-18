<?php
/*
 * 文章的增删改查控制器
 */
namespace app\admin\controller;

use app\common\controller\Base;
use think\Request;
use app\common\model\ArticleModel;

class Article extends Base
{
    //显示文章信息
    public function index()
    {
        $this->antiJump();
        //获取文章数量
        $article_count = $this->articleCount();
        //分页，每页显示8条
        $data = ArticleModel::order('create_time','desc')->paginate(8);
        $indexdata = [
            'data'              =>    $data,
            'count'             =>    $article_count,
            'article_active'    =>    'active',
        ];
        return $this->view->fetch('',$indexdata);
    }
    //显示文章表单
    public function add()
    {
        $this->antiJump();
        $this->view->assign('article_active','active'); //笨拙的方法，菜单被选中状态
        return $this->view->fetch();
    }
    //接收文章表单数据并入库
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
        $data = ArticleModel::get($_GET['id']);
        $editdate = [
            'article_active'   =>   'active',
            'data'             =>   $data,
        ];
        return $this->view->fetch('',$editdate);
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
    //删除一条文章
    public function delete()
    {
        if(Request::instance()->isAjax()){
            $data = Request::instance()->post();
            if(ArticleModel::destroy($data)){
                return ['status' => 1];
            }
        }else{
            return $this->error('非法请求!','/admin/Article/index');
        }
    }
    //删除选中文章
    public function deleteAll()
    {
        if(Request::instance()->isAjax()){
            $data = Request::instance()->post();
            if(ArticleModel::destroy($data['checkbox'])){
                return ['status' => 1];
            }
        }else{
            return $this->error('非法请求!','/admin/Article/index');
        }
    }
}