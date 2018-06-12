<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/6/4
 * Time: 10:52
 */
//创建Server对象，监听 127.0.0.1:9502端口，类型为SWOOLE_SOCK_UDP
$serv = new swoole_server("127.0.0.1", 9502, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);

$serv->set([
    'worker_num' => 8, // worker进程数 cpu核数的1-4倍
    'max_request' => 10000,
]);
//监听数据接收事件
$serv->on('Packet', function ($serv, $data, $clientInfo) {
    $serv->sendto($clientInfo['address'], $clientInfo['port'], "Server ".$data);
    var_dump($clientInfo);
});

//启动服务器
$serv->start();