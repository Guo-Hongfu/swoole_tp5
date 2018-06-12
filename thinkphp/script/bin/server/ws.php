<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/6/4
 * Time: 14:37
 */
class Ws {
    const HOST = "0.0.0.0";
    const PORT = 8811;
    const CHART_PORT = 8812;

    public $ws = null;
    public function __construct() {
        $this->ws = new swoole_websocket_server(self::HOST, self::PORT);
        // 监听端口
        $this->ws->listen(self::HOST,self::CHART_PORT,SWOOLE_SOCK_TCP);
        $this->ws->set([
            'worker_num' =>5,
            'task_worker_num' =>5,
            //支持静态文件
            'enable_static_handler' => true,
            'document_root' => "/home/wwwroot/default/swoole/thinkphp/public/static",
        ]);
        $this->ws->on("start",[$this,'onStart']);
        $this->ws->on("open",[$this,'onOpen']);
        $this->ws->on("message",[$this,'onMessage']);
        $this->ws->on("workerStart",[$this,'onWorkerStart']);
        $this->ws->on("request",[$this,'onRequest']);
        $this->ws->on("task",[$this,'onTask']);
        $this->ws->on("finish",[$this,'onFinish']);
        $this->ws->on("close",[$this,'onClose']);
        $this->ws->start();
    }

    /**
     * @param $server
     */
    public function onStart($server){
        // 进程别名
        swoole_set_process_name("live_master");
}
    /**
     * @param $server
     * @param $worker_id
     * WorderStart的一个回调
     */
    public function onWorkerStart($server,$worker_id){
        // 定义应用目录 加载TP5框架内容
        define('APP_PATH', __DIR__ . '/../../../application/');
        // 加载框架里面文件，1. 加载基础文件
//        require __DIR__ . '/../thinkphp/base.php';
        require __DIR__ . '/../../../thinkphp/start.php';
        //重启ws，清空的redis 直播场景的redis的key 值
        if (app\common\lib\redis\Predis::getInstance()->sMembers(config('redis.live_game_key'))){
            app\common\lib\redis\Predis::getInstance()->del(config('redis.live_game_key'));
        }
    }

    /**
     * @param $request
     * @param $response
     * Request的一个回调
     */
    public function onRequest($request, $response){
        // 对于谷歌浏览器，加载了图标
        if($request->server['request_uri'] == '/favicon.ico') {
            $response->status(404);
            $response->end();
            return ;
        }
        $_SERVER  =  [];
        if(isset($request->server)) {
            foreach($request->server as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }
        if(isset($request->header)) {
            foreach($request->header as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }

        $_GET = [];
        if(isset($request->get)) {
            foreach($request->get as $k => $v) {
                $_GET[$k] = $v;
            }
        }

        $_FILES=[];
        if(isset($request->files)) {
            foreach($request->files as $k => $v) {
                $_FILES[$k] = $v;
            }
        }
        $_POST = [];
        if(isset($request->post)) {
            foreach($request->post as $k => $v) {
                $_POST[$k] = $v;
            }
        }
        $this->writeLog();
        $_POST['http_server'] = $this->ws;

        ob_start();
        // 执行应用并响应
        try {
            \think\App::run()->send();
            }catch (\Exception $e) {
            // todo
        }

        $res = ob_get_contents();
        ob_end_clean();
        $response->end($res);
    }


    /**
     * 监听ws消息事件
     * @param $ws
     * @param $frame
     */
    public function onMessage(swoole_websocket_server $ws,$frame){
        echo "ser-push-message:{$frame->data}\n";
        $ws->push($frame->fd,"server-push:".date("Y-m-d H:i:s"));
    }

    /**
     * @param $serv
     * @param $taskId
     * @param $workerId
     * @param $data
     * @return string
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
        $flag = $obj->$method($data['data'],$serv);
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
     * 监听ws连接事件
     * @param $ws
     * @param $request
     */
    public function onOpen($ws,$request){
        // 把 fd 客户端的id($request->fd) 放在 redis 的有序集合中去
        \app\common\lib\redis\Predis::getInstance()->sAdd(config('redis.live_game_key'),$request->fd);
        var_dump($request->fd);
    }
    /**
     * close事件
     * @param $ws
     * @param $fd
     */
    public function onClose($ws,$fd){
        // 客户端关闭，在 redis的有序集合中就把它(存储的$fd)删掉
        app\common\lib\redis\Predis::getInstance()->sRem(config('redis.live_game_key'),$fd);
        echo "clientId:{$fd}\n";
    }
    /**
     * 记录日志
     * */
    public function writeLog(){
        $datas = array_merge(['date' => date("Ymd H:i:s")],$_GET,$_POST,$_SERVER);
        $logs = "";
        foreach ($datas as $key=>$value){
            $logs .= $key . ':'.$value.'';
        }
        //异步io写入文件
        swoole_async_writefile(APP_PATH.'../runtime/log/'.date("Ym")."/".date("d")."_access.log",$logs.PHP_EOL,function ($filename){
            //TODO
        },FILE_APPEND);
    }

}

$obj = new Ws();

//20台机器记录了日志  agent->spark （agent解析日志） (计算) -》数据库 elasticsearch (分布式的数据库，不是我们用的Mysql);
// 日志深度挖掘

//(信号源) 3种
// sigterm 停止服务用的。结合linux用的
// sigusr1 用于处理worker进程
// usr2