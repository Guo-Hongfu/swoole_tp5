<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/6/9
 * Time: 11:19
 */
namespace app\admin\controller;


use app\common\lib\redis\Predis;
use app\common\lib\Util;

class Live
{
    public function push() {
        if (empty($_GET)){
            return Util::show(config('code.error'),'没有发送数据error');
        }
        // admin是否登录  token
        // 这是一个测试的数据
        $teams = [
            1 => [
                'name' => '马刺',
                'logo' => '/live/imgs/team.1.png'
            ],
            2 => [
                'name' => '火箭',
                'logo' => '/live/imgs/team2.png'
            ],
        ];

        $data = [
            'type' => intval($_GET['type']),
            'title' => !empty($teams[$_GET['team_id']]['name'])?$teams[$_GET['team_id']]['name']:'直播员',
            'logo' => !empty($teams[$_GET['team_id']]['logo'])?$teams[$_GET['team_id']]['logo']:'',
            'content' => !empty($_GET['content'])?$_GET['content']:'',
            'image' => !empty($_GET['image'])?$_GET['image']:'',
        ];
        $taskData = [
            'method' => 'pushLive',
            'data' => $data
        ];
        $_POST['http_server']->task($taskData);
        return Util::show(config('code.success'),'ok');
    }
}