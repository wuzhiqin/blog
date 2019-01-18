<?php
/*
 * 基础控制器
 */
namespace app\common\controller;

use think\Session;
use think\Controller;
use app\common\model\ArticleModel;

class Base extends Controller
{
    /*
     * 初始化方法
     * 创建常量，公共方法
     * 在所有的方法之前被调用
     */
    //防止重复登录
    public function isLogin()
    {
        if(Session::has("admin_info")){
            $this->error('您已登录!','index/main');
        }
    }
    //防止跳墙
    public function antiJump()
    {
        if(!Session::has("admin_info")){
            $this->error('非法请求!','/admin/index/index');
        }
    }
    //统计文章数量
    public function articleCount()
    {
        $article_count = "SELECT count(id)as mycount FROM blog_article";
        return ArticleModel::query($article_count);
    }
    //阅读排行和推荐
    public function ranking()
    {
        //阅读
        $ranking_data = "SELECT `id`,`title`,`content_desc` FROM blog_article WHERE `status` = 1 ORDER BY pv DESC LIMIT 0,2";
        $ranking_data = ArticleModel::query($ranking_data);
        //推荐
        $recommend_data = ArticleModel::all([rand(1,23),rand(1,23),rand(1,23)]);
        return $data = [
            'ranking_data'     =>      $ranking_data,
            'recommend_data'   =>      $recommend_data,
        ];
    }
    
    //获取登录地址
    public function ipAddress($data)
    {
	    $url="http://ip.taobao.com/service/getIpInfo.php?ip=".$data;//淘宝接口需要填写ip
        $ip=json_decode(file_get_contents($url));  
        if((string)$ip->code=='1'){
           return false;
        }
        //返回数组数据
	    return (array)$ip->data;
    } 
    
}