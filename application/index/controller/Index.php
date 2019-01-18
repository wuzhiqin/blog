<?php
namespace app\index\controller;

use app\common\controller\Base;
use app\common\model\ArticleModel;
use app\common\model\NoticeModel;

class Index extends Base
{
    public function index()
    {
        //获取阅读排行和推荐
        $ranking = $this->ranking();
        //获取热度文章
        $ranking_data = $ranking['ranking_data'];
        //随机推荐
        $recommend_data = $ranking['recommend_data'];
        //获取最新文章
        $article_data = "SELECT `id`,`title`,`titlepic`,`content_desc`,`tags`,`create_time`,`pv` FROM `blog_article` WHERE `status` = 1 ORDER BY `update_time` desc LIMIT 0,11";
        $article_data = ArticleModel::query($article_data);
        $data = [
            'article_data'     =>    $article_data,
            'ranking_data'     =>    $ranking_data,
            'recommend_data'   =>    $recommend_data,
        ];
        return $this->view->fetch('',$data);
    }
    //关于我
    public function about()
    {
        $sql = "SELECT `id`,`title` FROM blog_diary ORDER BY `update_time` LIMIT 0,5";
        $about_data = NoticeModel::query($sql);
        return $this->view->fetch('',['about_data'=>$about_data]);
    }
}
