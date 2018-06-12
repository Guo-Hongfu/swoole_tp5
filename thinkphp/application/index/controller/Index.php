<?php
namespace app\index\controller;

use app\common\lib\redis\Predis;
use app\common\lib\Redis;

class Index
{
    public function index() {
        return '';
    }

    public function hello() {
        try{
//            $redis = new \Redis();
//            $redis->connect('127.0.0.1',6379);
//            $redis->set('test','hello world');
//            return $redis->get('test');
            $result = Predis::getInstance()->set(Redis::smsKey('1234'),'4567890');
            $res = Predis::getInstance()->get(Redis::smsKey('1234'));
            var_dump($result);
            return "res:{$res}".PHP_EOL."result:{$result}";
        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }
    public function time(){
        echo time();
    }
}
