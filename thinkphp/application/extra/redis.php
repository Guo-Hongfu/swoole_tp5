<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/6/7
 * Time: 18:05
 */

//Redis的相关配置

return [
    //主机
    'host' => '127.0.0.1',
    //端口
    'port' => 6379,
    //失效时间 3600秒
    'out_time' => 120,
    //连接超时时间
    'timeOut' => 10,
    //直播场景的redis的key
    'live_game_key' => 'live_game_key',
];