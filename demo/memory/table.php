<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/6/6
 * Time: 10:29
 */

// 创建内存表
$table = new swoole_table(1024);

// 内存表增加一列
$table->column('id',$table::TYPE_INT,4);
$table->column('name',$table::TYPE_STRING,64);
$table->column('age',$table::TYPE_INT,3);
$table->create();

$table->set('yi_chang_1',['id' => 1,'name'=>'test1','age'=>20]);
//另一种设置方案
//$table['yi_chang_2'] = [
//    'id' => 2,
//    'name' => 'yi_chang_2',
//    'age'  => 30
//];

// age + 2 原子增加操作
$table->incr('yi_chang_1','age',2);

// age - 2 原子减操作
$table->decr('yi_chang_1','age',2);

print_r($table->get('yi_chang_1'));

// 删除

echo "delete start:".PHP_EOL;
$table->del('yi_chang_1');
print_r($table['yi_chang_1']);
//另一种获取方案
//print_r($table['yi_chang_1']);
//print_r($table->get('yi_chang_2'));