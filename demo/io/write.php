<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/6/5
 * Time: 10:52
 */

//异步写文件
$content = date("Ymd H:i:s").PHP_EOL;
swoole_async_writefile(__DIR__."/2.txt",$content,function ($filename){
    // todo
    echo "success".PHP_EOL;
},FILE_APPEND);
//FILE_APPEND表示追加到文件末尾
echo "start".PHP_EOL;