<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/6/5
 * Time: 17:05
 */

$redisClient = new swoole_redis; // swoole redis
$redisClient->connect('127.0.0.1',6379,function (swoole_redis $redisClient,$result){
    echo "connect".PHP_EOL;
    var_dump($result);

    // 同步redis  (new Redis())->set('key',2)
    //设置值
//    $redisClient->set('guo',time(),function (swoole_redis $redisClient,$result){
//        var_dump($result);
//    });

//    //获取值
//    $redisClient->get('guo',function (swoole_redis $redisClient,$result){
//        var_dump($result);
//        //关闭Redis连接，不接受任何参数
//        $redisClient->close();
//    });

    //获取key  获取所有的key:keys('*') 匹配key中含有u的key: keys('*u*')
    $redisClient->keys('*u*',function (swoole_redis $redisClient,$result){
        var_dump($result);
    });
});

echo "start".PHP_EOL;