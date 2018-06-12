<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/6/7
 * Time: 13:39
 */
namespace app\index\controller;


use app\common\lib\Util;

class Send
{
    /**
     * 发送验证码
     */
    public function index(){
        // input('get.phone_num') 只能拿到第一次的值
        // 使用原生php获取
        $phoneNum = intval($_GET['phone_num']);
        if (empty($phoneNum)){
            // status 0 1 message data
            return Util::show(config('code.error'),'error','号码是空的');
        }
        // 生成一个随机数
        $code = rand(100000,999999);
        $taskData = [
            //method 每次去执行Task的时候，每次都要有这个method这个变量，代表这个任务是走sendSms这个方法
            'method' => 'sendSms',
            'data'  =>[
                'phone'  => $phoneNum,
                'code'   => $code,
            ]
        ];
        $_POST['http_server']->task($taskData);
        return Util::show(config('code.success'),'ok','验证码发送成功，请注意查收');

//        $result = $_POST['http_server']->task($taskData);
//        if ($result === true){
//            return Util::show(config('code.success'),'ok','验证码发送成功，请注意查收');
//        }else{
//            //出现这个异常 有两种情况，一个可能是阿里云未发送短信，第二种可能是Redis连接失败
//            return Util::show(config('code.error'),'error','网络错误，请稍后再试');
//        }
    }
}