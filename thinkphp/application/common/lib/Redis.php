<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/6/7
 * Time: 18:07
 * Redis 类库
 */
namespace app\common\lib;



class Redis
{
    /**
     * 验证码 redis key 的前缀设置为sms
     * 用户 key 前缀设置为user
     * @var string
     */
    public static $pre = "sms_";
    public static $userpre = "user";
    //设置key,根据传入的参数来设置。比如在登录的时候，传入的手机号码
    // 存储验证码的key值
    public static function smsKey($phone){
        return self::$pre.$phone;
    }

    /**
     * @param $phone
     * 存储user key
     * @return string
     */
    public static function userKey($phone){
        return self::$userpre.$phone;
    }
}