<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/6/11
 * Time: 15:52
 * 监控服务 ws http 8811
 */
class server
{
    const PORT = 8811;
    public function port(){
//        $shell = "netstat -anp | grep ".self::PORT;
        $shell = "netstat -anp 2>/dev/bull | grep ".self::PORT . " | grep LISTEN | wc -l";
        $result = shell_exec($shell);
        echo $result;
        if ($result != 1){
            // 发送报警服务， 短信
            echo date("Ymd H:i:s")."服务挂了".PHP_EOL;
        }else{
            echo date("Ymd H:i:s")."服务已开启".PHP_EOL;
        }
    }
}

//2秒执行一次
swoole_timer_tick(2000,function ($timer_id){
    (new Server())->port();
    echo 'start:2s执行一次';
});
