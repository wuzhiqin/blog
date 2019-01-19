<?php
/**
 * Created by PhpStorm.
 * User: w1248
 * Date: 2019/1/17
 * Time: 11:00
 */

namespace app\api\controller;


use think\Controller;

class Login extends Controller
{
    private $redirectUri = 'http://wuzhiqin.xyz/api/login/check';   //回调地址
    private $appId = '101546764';                                      //appId
    private $appKey = 'ac4fa03beaf1ada12e7e8306f6b61b4c';           //秘钥
    //qq登录
    public function check()
    {
        $data = $_GET;
        if (empty($data['code']) | empty($data['state'])){
            $this->redirect('/index');
        }else{
            $state = $data['state'];
            $code = $data['code'];
        }

        //1.获取token
        $tokenUrl = 'https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&client_id='.$this->appId.'&client_secret='.$this->appKey.'&code='.$code.'&redirect_uri='.$this->redirectUri;
        $getTokenRes = file_get_contents($tokenUrl);
        if (!stripos($getTokenRes,'error')) return $getTokenRes;
        $getTokenRes = explode('&',$getTokenRes);
        $token = substr($getTokenRes[0],13);

        //2.获取openId
        $openIdUrl = 'https://graph.qq.com/oauth2.0/me?access_token='.$token;
        $getOpenIdRes = file_get_contents($openIdUrl);
        if (!stripos($getOpenIdRes,'error')) return $getOpenIdRes;
        //检测是否获取到
        preg_match('/^callback\((.+)\);$/',$getOpenIdRes,$getOpenIdRes);
        $getOpenIdRes = json_decode($getOpenIdRes[1],1);

        //3.获取用户信息
        $getUserInfoUrl = 'https://graph.qq.com/user/get_user_info?access_token='.$token.'&oauth_consumer_key='.$this->appId.'&openid='.$getOpenIdRes['openid'];
        $userInfo = file_get_contents($getUserInfoUrl);
        if (!stripos($userInfo,'error')) return $getOpenIdRes;
        $userInfo = json_decode($userInfo,1);
        dump($userInfo);

    }

}