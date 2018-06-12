<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/6/4
 * Time: 13:32
 * PATH_INFO访问: http://119.23.54.180:8811/?s=index/index/hello
 */
$http = new swoole_http_server("0.0.0.0", 8811);

//这个配置是为了读取静态文件。目录是document_root的配置目录
$http->set([
    'enable_static_handler' => true,
    'document_root' => "/home/wwwroot/default/swoole/thinkphp/public/static/",
    'worker_num' =>5
]);

$http->on('WorkerStart',function (swoole_server $server,$worker_id){
    //加载TP框架代码，放在onRequest之上TP代码的话就需要每次重启http_server，这个性能比下面的高一点
    // 定义应用目录
    define('APP_PATH', __DIR__ . '/../application/');
    // 加载框架里面文件，1. 加载基础文件
    require __DIR__ . '/../thinkphp/base.php';
});

$http->on('request', function ($request, $response) use ($http){

    //加载TP框架代码，放在onRequest下就不用每次重启http_server
//    // 定义应用目录
//    define('APP_PATH', __DIR__ . '/../application/');
//    // 加载框架里面文件，1. 加载基础文件
//    require __DIR__ . '/../thinkphp/base.php';
//    print_r($request->server);
    $_REQUEST=[];
    if (isset($request->server)){
        foreach ($request->server as $k => $v){
            $_REQUEST[strtoupper($k)] = $v;
        }
    }
    if (isset($request->header)){
        foreach ($request->header as $k => $v){
            $_REQUEST[strtoupper($k)] = $v;
        }
    }
    $_GET=[];
    if (isset($request->get)){
        foreach ($request->get as $k => $v){
            $_GET[$k] = $v;
        }
    }
    $_POST=[];
    if (isset($request->post)){
        foreach ($request->post as $k => $v){
            $_POST[$k] = $v;
        }
    }
    ob_start(); //开启缓存区
    try{
        think\App::run()->send();

    }catch (\Exception $e){
        // todo
    }
//    echo request()->action().PHP_EOL;
    $res = ob_get_contents();
    ob_end_clean();
    $response->end($res);
//    $http->close();
});
$http->start();


//$http->on('request', function ($request, $response) {
//    $content = [
//        'date:' => date("Ymd H:i:s"),
//        'get:' => $request->get,
//        'post:' => $request->post,
//        'header:' => $request->header,
//    ];
//    swoole_async_writefile(__DIR__ . '/access.log', json_encode($content) . PHP_EOL,
//        function ($filename) {
//            //todo
//            // linux命令 tail -f access.log  实时查看
//        },FILE_APPEND);
//    $response->cookie("singwa", "xsssss", time() + 1800);
//    $response->end("<h1>HTTPServer</h1>" . json_encode($request->get));
//});
//$http->start();