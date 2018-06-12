<?php
namespace app\common\lib;
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/6/7
 * Time: 17:49
 */
class Util
{
    /**
     * API输出格式
     * @param $status
     * @param string $message
     * @param array $data
     * @return string
     */
    public static function show($status,$message='',$data=[]){
        $result = [
            'status' => $status,
            'message' => $message,
            'data'   => $data,
        ];
        return json_encode($result);
    }
}