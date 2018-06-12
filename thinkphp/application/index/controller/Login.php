<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/6/7
 * Time: 13:39
 */

namespace app\index\controller;

use app\common\lib\Util;
use app\common\lib\Redis;
use app\common\lib\redis\Predis;
class Login
{
    /**
     * 登录
     */
    public function index(){
       // 获取用户输入的手机号码
        $phoneNum = intval($_GET['phone_num']);
        //获取用户输入的code
        $code = intval($_GET['code']);
       if (empty($phoneNum) || empty($code)){
           return Util::show(config('code.error'),'phone or code is error');
       }
       // redis code
        try{
            $redisCode = Predis::getInstance()->get(Redis::smsKey($phoneNum));
        }catch (\Exception $e){
           return $e->getMessage();
        }
        if ($redisCode == $code){
            //验证码正确
            //可以建一个表
            //写入redis
            $data = [
                'user' => $phoneNum,
                'srcKey' => md5(Redis::userKey($phoneNum)),
                'time' => time(),
                'isLogin' => true
            ];
            Predis::getInstance()->set(Redis::userKey($phoneNum),$data);
            return Util::show(config('code.success'),'ok',$data);
        }else{
            return Util::show(config('code.error'),'error','验证码错误');
        }
    }
}