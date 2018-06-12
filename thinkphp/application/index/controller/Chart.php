<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/6/11
 * Time: 13:42
 */
namespace app\index\controller;


use app\common\lib\Util;
use app\common\lib\redis\Predis;
use think\Controller;

class Chart extends Controller
{
    public function index(){
        print_r(config('app_debug'));
        return $this->fetch();
    }
    public function chart(){

        if (empty($_POST['game_id'])){
            return Util::show(config('code.error'),'error');
        }
        if (empty($_POST['content'])){
            return Util::show(config('code.error'),'error');
        }
        // 统计在线人数
        $clients = Predis::getInstance()->sMembers(config('redis.live_game_key'));
        // todo 验证 content 是否合法，过滤不合法的词语
        $data = [
            'user' => '用户'.rand(0,2000),
            'content' => $_POST['content'],
            'user_count' => count($clients)
        ];
        foreach ($_POST['http_server']->ports[1]->connections as $fd){
            $_POST['http_server']->push($fd,json_encode($data));
        }
        return Util::show(config('code.success'),'ok');
    }
}