<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/6/4
 * Time: 14:01
 */
$server = new swoole_websocket_server("0.0.0.0", 8812);

//这个配置是为了读取静态文件。目录是document_root的配置目录
$server->set([
    'enable_static_handler' => true,
    'document_root' => "/home/wwwroot/default/swoole/data",
]);
//回调函数onOpen，监听websocket连接打开事件
$server->on('open','onOpen');
function onOpen($server,$request){
    print_r($request->fd);
}

// 监听websocket消息事件
$server->on('message', function (swoole_websocket_server $server, $frame) {
    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    $server->push($frame->fd, "成功发送数据到客户端");
});

$server->on('close', function ($ser, $fd) {
    echo "client {$fd} closed\n";
});

$server->start();