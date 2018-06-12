<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/6/6
 * Time: 11:57
 */

//协程Redis  协程客户端必须使用在 onRequest,onReceive, onConnect 的回调里面。并发性能好。

$http = new swoole_http_server('0.0.0.0',8001);

$http->on('request',function ($request,$response){
    //获取redis 里面的 key 的内容，然后输出浏览器
    $redis = new Swoole\Coroutine\Redis();
    $redis->connect('127.0.0.1',6379);
    $value = $redis->get($request->get['a']);
    $response->header("Content-Type","text/plain");
    $response->end($value);
});

$http->start();

/**
 * 1 redis
 * 2 mysql
 *
 */
