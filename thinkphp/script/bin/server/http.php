<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/6/8
 * Time: 13:54
 * 封装的Http_server
 * 面向对象思想
 */

class Http{
    const HOST = "0.0.0.0";
    const PORT = 8811;
    public $http = null;
    public function __construct() {
        $this->http = new swoole_http_server(self::HOST,self::PORT);
        $this->http->set([
            'worker_num' =>5,
            'task_worker_num' =>5,
            //支持静态文件
            'enable_static_handler' => true,
            'document_root' => "/home/wwwroot/default/swoole/thinkphp/public/static/",
        ]);
        $this->http->on("WorkerStart",[$this,'onWorkerStart']);
        $this->http->on("request",[$this,'onRequest']);
        $this->http->on("task",[$this,'onTask']);
        $this->http->on("finish",[$this,'onFinish']);
        $this->http->on("close",[$this,'onClose']);
        $this->http->start();
    }

    /**
     * @param $server
     * @param $worker_id
     * WorderStart的一个回调
     */
    public function onWorkerStart($server,$worker_id){
        // 定义应用目录 加载TP5框架内容
        define('APP_PATH', __DIR__ . '/../application/');
        // 加载框架里面文件，1. 加载基础文件
//        require __DIR__ . '/../thinkphp/start.php';
        require __DIR__ . '/../thinkphp/base.php';
    }

    /**
     * @param $request
     * @param $response
     * Request的一个回调
     */
    public function onRequest($request, $response){
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
        $_POST['http_server'] = $this->http;
        ob_start(); //开启缓存区
        try{
            think\App::run()->send();
        }catch (\Exception $e){
            // todo
        }
        $res = ob_get_contents();
        ob_end_clean();
        $response->end($res);
    }


    /**
     * @param $serv
     * @param $taskId
     * @param $workerId
     * @param $data
     * @return string
     * Task异步任务
     */
    public function onTask($serv,$taskId,$workerId,$data){
        // 分发 Task 任务机制，让不同的任务走不同的逻辑
        // $data =[
        //       'method'=> '',
        //       'data'  => [ ]
        //   ]
       $obj = new app\common\lib\task\Task;
        $method = $data['method'];
        // todo 判断$method是否存在
        $flag = $obj->$method($data['data']);
        return $flag; //告诉worker
    }

    /**
     * @param $serv
     * @param $taskId
     * @param $data
     * $data数据是onTask return的数据
     */
    public function onFinish($serv,$taskId,$data){
        echo "taskId:{$taskId}\n";
        echo "finish-data-success:{$data}\n";
    }
    /**
     * close事件
     * @param $ws
     * @param $fd
     */
    public function onClose($ws,$fd){
        echo "clientId:{$fd}\n";
    }
}

new Http();