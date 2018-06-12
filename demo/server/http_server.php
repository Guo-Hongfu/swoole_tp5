<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/6/4
 * Time: 13:32
 */
$http = new swoole_http_server("0.0.0.0", 8811);

//这个配置是为了读取静态文件。目录是document_root的配置目录
$http->set([
    'enable_static_handler' => true,
    'document_root' => "/home/wwwroot/default/swoole/data",
]);
$http->on('request', function ($request, $response) {
    $content = [
        'date:' => date("Ymd H:i:s"),
        'get:' => $request->get,
        'post:' => $request->post,
        'header:' => $request->header,
    ];
    swoole_async_writefile(__DIR__ . '/access.log', json_encode($content) . PHP_EOL,
        function ($filename) {
            //todo
            // linux命令 tail -f access.log  实时查看
        },FILE_APPEND);
    $response->cookie("singwa", "xsssss", time() + 1800);
    $response->end("<h1>HTTPServer</h1>" . json_encode($request->get));
});
$http->start();