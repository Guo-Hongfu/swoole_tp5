<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/6/5
 * Time: 10:00
 */
/**
 * 读取文件
 * __DIR__ 指向当前执行的php脚本所在的目录
 * 1.txt和read.php在同一级目录下。
 */
swoole_async_readfile(__DIR__."/1.txt",function ($filename,$fileContent){
    echo  "filename:".$filename.PHP_EOL;
    echo 'content:'.$fileContent.PHP_EOL;
});

echo "start".PHP_EOL;