<?php
/*
 * 短信模块
 */
namespace app\common\messages;


class SendMessages
{


/**
  * 发送模板短信
  * @param to 手机号码集合,用英文逗号分开
  * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
  * @param $tempId 模板Id,测试应用和未上线应用使用测试模板请填写1，正式应用上线后填写已申请审核通过的模板ID
  */       
 public function sendTemplateSMS($to,$datas,$tempId)
{
        //主帐号,对应开官网发者主账号下的 ACCOUNT SID
    $accountSid= '8a216da862764869016293fc3b500d5c';

    //主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
    $accountToken= '49462f4cb8dc4a20b1aefa8454a2a24e';

    //应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
    //在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
    $appId='8a216da862764869016293fc3b9c0d62';

    //请求地址
    //沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
    //生产环境（用户应用上线使用）：app.cloopen.com
    $serverIP='app.cloopen.com';


    //请求端口，生产环境和沙盒环境一致
    $serverPort='8883';

    //REST版本号，在官网文档REST介绍中获得。
    $softVersion='2013-12-26';

     // 初始化REST SDK
     $rest = new REST($serverIP, $serverPort, $softVersion);
     $rest->setAccount($accountSid,$accountToken);
     $rest->setAppId($appId);
    
     // 发送模板短信
     $sendSuccess = "Sending TemplateSMS to $to <br/>";
     $result = $rest->sendTemplateSMS($to,$datas,$tempId);
     if($result == NULL ) {
         $sendError = "result error!";
         break;
     }
     if($result->statusCode!=0) {
         $error_code = "error code :" . $result->statusCode . "<br>";
         $error_msg = "error msg :" . $result->statusMsg . "<br>";
         //TODO 添加错误处理逻辑
     }else{
         $success = "Sendind TemplateSMS success!<br/>";
         // 获取返回信息
         $smsmessage = $result->TemplateSMS;
         $dateCreated = "dateCreated:".$smsmessage->dateCreated."<br/>";
         $smsMessageSid = "smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
         //TODO 添加成功处理逻辑
     }
}
}



//Demo调用 
		//**************************************举例说明***********************************************************************
		//*假设您用测试Demo的APP ID，则需使用默认模板ID 1，发送手机号是13800000000，传入参数为6532和5，则调用方式为           *
		//*result = sendTemplateSMS("13800000000" ,array('6532','5'),"1");																		  *
		//*则13800000000手机号收到的短信内容是：【云通讯】您使用的是云通讯短信模板，您的验证码是6532，请于5分钟内正确输入     *
		//*********************************************************************************************************************