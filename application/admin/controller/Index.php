<?php
namespace app\admin\controller;
use think\Session;
use think\Request;
use app\common\controller\Base;
use app\common\model\AdminUserModel;
use think\Validate;
use app\common\model\LoginRecordModel;
use app\common\messages\SendMessages;

class Index extends Base
{
    //管理员登录界面
    public function index()
    {
        //退出登录的处理
        if(isset($_GET['outLogin'])){
            Session::clear();
        } 
        //防止重复登录
        $this->isLogin();
        return $this->view->fetch();
    }
    public function main()
    {
        $this->antiJump();
        //统计文章数量
        $article_count = $this->articleCount();
        //获取管理员最后一次登录的记录
        $logout = "SELECT * FROM login_record WHERE `admin` = ? GROUP BY login_time DESC LIMIT 1,1";
        $admin_info = Session::get('admin_info');
        $logout = LoginRecordModel::query($logout,[$admin_info['admin_name']]);
        if(isset($logout[0])){
            //初始化时间戳格式
            $logout[0]['login_time'] = date('Y-m-d  H:i:s',$logout[0]['login_time']);
        }        
        //获取登录次数
        $login_count = "SELECT count(admin)as mycount FROM login_record WHERE `admin` = ?";
        $login_count = LoginRecordModel::query($login_count,[$admin_info['admin_name']]);
        if(isset($login_count[0])){
            $login_count = $login_count[0]['mycount'];
        }       
        //获取管理员人数
        $admin_count = count(AdminUserModel::all());
        $main_data = [
            'article_count'   =>    $article_count,
            'main_active'     =>    'active',       //菜单选中状态
            'login_count'     =>    $login_count,
            'logout'          =>    $logout,
            'admin_count'     =>    $admin_count,
        ];
        return $this->view->fetch('main',$main_data);
    } 
    
    //管理员登录验证
    public function loginCheck(){     
        if(Request::instance()->isAjax()){
            $data = Request::instance()->post();
            //验证码验证
            if(!captcha_check($data['captcha'])){
                return ['status' => 2,'message' => '验证码有误！'];
                break;
            }         
            $rule = 'app\common\validate\Login';  //自定义的验证规则
            //开始验证
            $res = $this->validate($data, $rule);
            if(true !== $res){//false：表示不通过验证规则
                return ['status' => -1,'message' => $res];
            }else{
                //执行查询
                $result = AdminUserModel::get(function($query) use($data){
                $query->where('admin_name',$data['admin_name'])
                ->where('admin_password',$data['admin_password']);
                });
                if(null == $result){
                    $record['host_ip'] = $_SERVER['REMOTE_ADDR']; //录入登录记录
                    $citys = $this->ipAddress($record['host_ip']);//获取登录地址数组
                    $record['address'] = $citys['city'];                
                    $res = LoginRecordModel::create($record);
                    return ['status' => 0,'message' => '用户或密码有误！'];
                }else{
                    $host_ip = $_SERVER['REMOTE_ADDR'];
                    $citys = $this->ipAddress($host_ip);//获取登录地址数组
                    $now_address = $citys['city'];          //在哪个城市登录
                    //获取数据库中上次登录的地址
                    $last_address = LoginRecordModel::where('admin','admin')->order('login_time','desc')->limit(1)->value('address');
                    //获取对应管理员的手机号码，号码不存在则不用发送通知
                    $phone = AdminUserModel::where('admin_name','admin')->value('admin_phone');
                    //对比上次登录的地址(城市)，不同则发送短信通知管理员
                    if($phone != null){                         //判断手机号码
                        if($last_address != null){              //判断是否是第一次登录
                            if($now_address != $last_address){  //判断上次登录和这次登录的地址是否一致
                                $SendMessages = new SendMessages();
                                $SendMessages->sendTemplateSMS($phone,array("***您的账号在($now_address)登录,如非本人操作,请修改密码***",'10'),"1");
                            }
                        }
                    }
                     
                    //登录数据集合
                    $record = [
                        'host_ip'    =>  $host_ip,
                        'admin'      =>  $data['admin_name'],
                        'address'    =>  $now_address,
                        'state'      =>  1,    
                    ];//录入登录记录
                    $res = LoginRecordModel::create($record);
                    Session::set('admin_info',$result);
                    //获取用户登录最新6条记录
                    $admin_record = "select * from login_record where admin=? group by login_time desc limit 0,6";
                    $admin_info = Session::get('admin_info');
                    $admin_record = LoginRecordModel::query($admin_record,[$admin_info['admin_name']]);
                    Session::set('admin_record',$admin_record);
                    return ['status' => 1,'message' => '登录成功！'];
                }
            }
        }else{
            $this->error('非法请求','/admin/index/index');
        }
        
    }
    
    //验证码
    public function getCaptcha()
    {
        $config =    [
            // 验证码字体大小
            'fontSize'    =>    18,
            // 验证码位数
            'length'      =>    4,
            // 关闭验证码杂点
            'useNoise'    =>    true,
            // 验证码图片高度
            'imageH'      =>    34,
            // 验证码图片宽度
            'imageW'      =>   100,
    
        ];
        $captcha = new \think\captcha\Captcha($config);
        return $captcha->entry();
    }
    
}
