<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/6/6
 * Time: 9:37
 */

$process = new swoole_process(function (swoole_process $pro){
    //todo
    $pro->exec('/usr/local/php/bin/php',[__DIR__.'/../server/http_server.php']);
},false);

//命令查看进程间的关系 ps aux | grep process.php
//命令 pstree -p 17947 (17947是父进程id)
// process.php是父进程  pid（http_server.php）是子进程
$pid = $process->start();
echo $pid.PHP_EOL;

swoole_process::wait();