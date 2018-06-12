<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/6/6
 * Time: 10:07
 */


/**
 * Swoole 进程使用场景
 * 执行多个url,
 * 引入swoole process
 * 按需开启N个子进程执行
 */
echo  'process-start-time:'.date("Ymd H:i:s").PHP_EOL;
$workers=[];
$url = [
    'http://baidu.com',
    'http://sina.com.cn',
    'http://qq.com',
    'http://taobao.com'
];

//swoole方案

for ($i=0; $i<4; $i++){
    //子进程
    $process = new swoole_process(function (swoole_process $worker) use($i,$url){
        //function 传入的第一个参数是swoole_process对象
        // curl
        $content = curlData($url[$i]);

//        echo  $content.PHP_EOL;
        // 把内容写进管道
        $worker->write($content);
    },true);
    $pid = $process->start();
    $workers[$pid] = $process;
}


foreach ($workers as $process ) {
    //获取管道中的数据
    echo $process->read();
}
/**
 * @param $url
 * @return string
 * 模拟请求URL的内容 耗时1s
 */
function curlData($url){
    // curl file_get_contents
    sleep(1);
    return $url."success".PHP_EOL;
}

echo "process-end-date:".date('Ymd H:i:s').PHP_EOL;