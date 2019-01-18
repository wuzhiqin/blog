<?php
namespace app\index\controller;

use app\common\controller\Base;
use app\common\model\ArticleModel;
use app\common\model\NoticeModel;

class Lists extends Base
{
    //学无止境
    public function lists()
    {
        //获取阅读排行和推荐
        $ranking = $this->ranking();
        //获取热度文章
        $ranking_data = $ranking['ranking_data'];
        //随机推荐
        $recommend_data = $ranking['recommend_data'];
        //获取文章
        $article_data = ArticleModel::where('status',1)->order('create_time','desc')->paginate(8);
        $data = [
            'article_data'     =>    $article_data,
            'ranking_data'     =>    $ranking_data,
            'recommend_data'   =>    $recommend_data,
        ];
        return $this->view->fetch('',$data);
    }
    //阅读文章
    public function listview()
    {
        $id = $_GET['id'];
        //根据id获取文章内容
        $article_data = ArticleModel::get($id);
        //获取阅读排行和推荐
        $ranking = $this->ranking();
        //获取热度文章
        $ranking_data = $ranking['ranking_data'];
        //随机推荐
        $recommend_data = $ranking['recommend_data'];
        //更新阅读量
        $article_pv = $article_data['pv'] +1;
        ArticleModel::where('id', $article_data['id'])
        ->update(['pv' => $article_pv]);
        //根据标签推荐阅读
        $tags = $article_data['tags'];
        $recommend = "SELECT `title`,`id` FROM `blog_article` WHERE `tags` = '$tags' ORDER BY `create_time` DESC LIMIT 0,6";
        $recommend = ArticleModel::query($recommend);
        //获取上一条数据
        $lastdata = "SELECT `title`,`id` FROM blog_article WHERE id > $id";  
        $lastdata = ArticleModel::query($lastdata);
        //获取下一条数据
        $nextdata = "SELECT `title`,`id` FROM blog_article WHERE id < $id";  
        $nextdata = ArticleModel::query($nextdata);
        $data = [
            'article_data'         =>       $article_data,
            'lastdata'             =>       $lastdata,
            'nextdata'             =>       $nextdata,
            'recommend'            =>       $recommend,
            'ranking_data'         =>       $ranking_data,
            'recommend_data'       =>       $recommend_data,
        ];
        return $this->view->fetch('',$data);
    }
    //慢生活
    public function slowLife()
    {
        //获取阅读排行和推荐
        $ranking = $this->ranking();
        //获取热度文章
        $ranking_data = $ranking['ranking_data'];
        //随机推荐
        $recommend_data = $ranking['recommend_data'];
        //获取慢生活文章
        $notice_data = NoticeModel::where('status',1)->order('create_time','desc')->paginate(8);
        $data = [
            'notice_data'          =>       $notice_data,
            'ranking_data'         =>       $ranking_data,
            'recommend_data'       =>       $recommend_data,
        ];
        return $this->view->fetch('',$data);
    }
    //慢生活阅读
    public function slowLifeView(){
        $id = $_GET['id'];
        $article_data = NoticeModel::get($id);   //根据id获取文章内容
        //获取阅读排行和推荐
        $ranking = $this->ranking();
        //获取热度文章
        $ranking_data = $ranking['ranking_data'];
        //随机推荐
        $recommend_data = $ranking['recommend_data'];
        //更新阅读量
        $article_pv = $article_data['pv'] +1;
        NoticeModel::where('id', $article_data['id'])
        ->update(['pv' => $article_pv]);
        //获取上一条数据
        $lastdata = "SELECT `title`,`id` FROM blog_article WHERE id > $id";
        $lastdata = NoticeModel::query($lastdata);
        //获取下一条数据
        $nextdata = "SELECT `title`,`id` FROM blog_article WHERE id < $id";
        $nextdata = NoticeModel::query($nextdata);
        $data = [
            'article_data'         =>       $article_data,
            'lastdata'             =>       $lastdata,
            'nextdata'             =>       $nextdata,
            'navs'                 =>       '慢生活',
            'ranking_data'         =>       $ranking_data,
            'recommend_data'       =>       $recommend_data,
        ];
        return $this->view->fetch('listview',$data);
    }
    //根据关键字，获取内容
    public function keyword()
    {
        //获取阅读排行和推荐
        $ranking = $this->ranking();
        //获取热度文章
        $ranking_data = $ranking['ranking_data'];
        //随机推荐
        $recommend_data = $ranking['recommend_data'];
        //关键字获取内容
        $keyword_data = $_GET['keyword'];
        $article_data = ArticleModel::order('create_time','desc')->where('keyword',$keyword_data)->paginate(8);
        $data = [
            'article_data'      =>    $article_data,
            'title'             =>    $keyword_data,
            'ranking_data'      =>    $ranking_data,
            'recommend_data'    =>    $recommend_data,
        ];
        return $this->view->fetch('lists',$data);
    }
    //根据标签，获取内容
    public function tags()
    {
        //获取阅读排行和推荐
        $ranking = $this->ranking();
        //获取热度文章
        $ranking_data = $ranking['ranking_data'];
        //随机推荐
        $recommend_data = $ranking['recommend_data'];
        //标签获取内容
        $tags_data = $_GET['tags'];
        $article_data = ArticleModel::order('create_time','desc')->where('tags',$tags_data)->paginate(8);
        $data = [
            'article_data'       =>    $article_data,
            'title'              =>    $tags_data,
            'ranking_data'       =>    $ranking_data,
            'recommend_data'     =>    $recommend_data,
        ];
        return $this->view->fetch('lists',$data);
    }
}