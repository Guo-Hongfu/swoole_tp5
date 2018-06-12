<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/6/8
 * Time: 14:32
 * 代表的是 swoole 里面后续 所有 task异步任务都放到这里来
 */
namespace app\common\lib\task;
use app\common\lib\Redis;
use app\common\lib\redis\Predis;
use app\common\lib\ali\Sms;
class Task
{
    /**
     * @param $data
     * 异步向手机发送验证码
     * @param $serv swoole_server对象
     * @return bool
     */
    public function sendSms($data,$serv){
        try {
            $response = Sms::sendSms($data['phone'], $data['code']);
        }catch (\Exception $e) {
            // todo
            return false;
        }

        // 如果发送成功 把验证码记录到redis里面 , 有效时间为 redis 配置中的 out_time
        if($response->Code === "OK") {
            Predis::getInstance()->set(Redis::smsKey($data['phone']), $data['code'], config('redis.out_time'));
        }else {
            return false;
        }
        return true;
    }

    /**
     * @param $data
     * 通过task机制发送赛况实时数据给客户端
     * *@param $serv swoole_server对象
     */
    public function pushLive($data,$serv){
        // 获取连接的用户
        //2.数据组织好, push到直播页面
        $clients = Predis::getInstance()->sMembers(config('redis.live_game_key'));
        foreach ($clients as $fd){
            $serv->push($fd,json_encode($data));
        }
        //1.赛况的基本信息入库
        // todo

    }
}