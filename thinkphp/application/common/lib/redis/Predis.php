<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/6/8
 * Time: 11:25
 * Redis 封装Redis基类
 */
namespace app\common\lib\redis;


class Predis
{
    public $redis = "";
    /**
     * 定义单例模式的变量
     * @var null
     */
    private static $_instance = null;

    public static function getInstance() {
        if (empty(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct(){
        $this->redis = new \Redis();
        $result = $this->redis->connect(config('redis.host'),config('redis.port'),config('redis.timeOut'));
        if ($result === false) {
            throw new \Exception('redis connect error');
        }
    }

    /**
     * set
     * @param $key
     * @param $value
     * @param int $time
     * @return bool|string
     */
    public function set($key, $value, $time = 0) {
        if (!$key) {
            return '';
        }
        if (is_array($value)) {
            $value = json_encode($value);
        }
        if (!$time) {
            return $this->redis->set($key, $value);
        }

        return $this->redis->setex($key, $time, $value);
    }

    /**
     * get
     * @param $key
     * @return bool|string
     */
    public function get($key) {
        if (!$key) {
            return '';
        }

        return $this->redis->get($key);
    }
    /**
     * @param $name
     * @param $arguments
     * @return array
     * 实例化这个类的时候，调用类里面的一个方法，要是没有要调用的方法，就走 __call 魔术方法
     */
    public function __call($name, $arguments) {
        //echo $name.PHP_EOL;
        //print_r($arguments);
        $count = count($arguments);
        if (!$name){
            return '';
        }
        if ($count == 1){
            return $this->redis->$name($arguments[0]);
        }elseif ($count == 2){
            return $this->redis->$name($arguments[0], $arguments[1]);
        }else{
            return '';
        }
    }
//    /**
//     * @param $key
//     * @param $value
//     * 操作有序集合的添加方法
//     * 把value添加到有序集合中去
//     * @return bool
//     */
//    public function sAdd($key,$value){
//        return $this->redis->sAdd($key,$value);
//    }
//
//    /**
//     * @param $key
//     * @param $value
//     * @return int
//     * 删除有序集合key 中的 value 值
//     */
//    public function sRem($key,$value){
//        return $this->redis->sRem($key,$value);
//    }
//    /**
//     * @param $key
//     * @return array
//     * 操作有序集合的获取方法
//     * 获取有序集合
//     */
//    public function sMembers($key) {
//        return $this->redis->sMembers($key);
//    }
//
//    // 删除
//    public function del($key){
//        return $this->redis->del($key);
//    }

}