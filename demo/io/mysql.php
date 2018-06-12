<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/6/5
 * Time: 15:05
 */

class AysMysql {
    /**
     * @var string
     */
    public $dbSource = "";
    /**
     * @var array
     * 定义一个成员属性
     * mysql的配置 dbConfig
     */
    public $dbConfig =[];
    public function __construct() {
        //new swoole_mysql;
        $this->dbSource=new Swoole\Mysql;

        $this->dbConfig = [
            'host' => '127.0.0.1',
            'port' => 3306,
            'user' => 'root',
            'password' => 'guo12157',
            'database' => 'swoole',
            'charset'  => 'utf8'
        ];
    }

    public function update(){

    }
    public function add(){

    }

    /**
     * mysql执行逻辑
     * @param $id
     * @param $username
     * @return bool
     */
    public function execute($id,$username){
        // 连接mysql  connect
        $this->dbSource->connect($this->dbConfig,function ($db,$result) use ($username){
            if ($result === false){
                var_dump($db->connect_error);
                //todo
            }
//            $sql = "select * from test where id=1";
            $sql = "update test set `username` = '".$username."' WHERE id=1";
            //query(add select update delete)
            $db->query($sql,function ($db,$result){
                // select=> result 返回的是 查询的结果内容

                if ($result === false){
                    // todo
                    var_dump($db->error);
                }elseif ($result === true){ // add update delete => result 返回的是bool类型
                    // todo
                }else{
                    var_dump($result);
                }
                $db->close();
            });
        });
        return true;
    }
}

$obj = new AysMysql();
$res = $obj->execute(1,'guo');
var_dump($res).PHP_EOL;
echo  '更新数据'.PHP_EOL;