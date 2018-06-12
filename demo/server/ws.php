<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/6/4
 * Time: 14:37
 */
class Ws {
    const HOST = "0.0.0.0";
    const PORT = 8812;
    public $ws = null;
    public function __construct() {
        $this->ws = new swoole_websocket_server("0.0.0.0", 8812);
        $this->ws->set([
           'worker_num' => 2,
            'task_worker_num'=>2
        ]);
        $this->ws->on("open",[$this,'onOpen']);
        $this->ws->on("message",[$this,'onMessage']);
        $this->ws->on("task",[$this,'onTask']);
        $this->ws->on("finish",[$this,'onFinish']);
        $this->ws->on("close",[$this,'onClose']);
        $this->ws->start();
    }

    /**
     * 监听ws连接事件
     * @param $ws
     * @param $request
     */
    public function onOpen($ws,$request){
        var_dump($request->fd);
        if($request->fd == 1){
            //每两秒执行
            swoole_timer_tick(2000,function ($timer_id){
                echo "2s:timerId:{$timer_id}\n";
            });
        }
    }

    /**
     * 监听ws消息事件
     * @param $ws
     * @param $frame
     */
    public function onMessage($ws,$frame){
        echo "ser-push-message:{$frame->data}\n";
        //todo 10s
        $data = [
           'task' => 1,
            'fd'  => $frame->fd
        ];
//        $ws->task($data);

        swoole_timer_after(5000,function () use($ws,$frame){
            echo  "5s-after\n";
            $ws->push($frame->fd,"server-time-after:");
        });
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
        print_r($data);
        //耗时场景 10s
        sleep(10);
        return "on task finish"; //告诉worker
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

$obj = new Ws();