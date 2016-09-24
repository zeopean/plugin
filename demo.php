<?php
/**
 * Created by PhpStorm.
 * User: zeopean
 * Date: 16/9/24
 * Time: 上午8:01
 */
require_once 'functions.php';

$array = ['name'=>'zeopean'];


$array = array_add($array,'age',23);
// array(2) { ["name"]=> string(7) "zeopean" ["age"]=> int(23) }

$array = array_divide($array);
//array(2) { [0]=> array(2) { [0]=> string(4) "name" [1]=> string(3) "age" } [1]=> array(2) { [0]=> string(7) "zeopean" [1]=> int(23) } }

$array = ['foo'=>['bar'=>'baz']];
$array = array_dot($array);
//array(1) { ["foo.bar"]=> string(3) "baz" }

$array = [
    'name'  => 'zeopean',
    'age'   => 23
];
$array = array_except($array,['age']);
//array(1) { ["name"]=> string(7) "zeopean" }

$array = [
    ['developer' => ['name' => 'Taylor']],
    ['developer' => ['name' => 'Dayle']]
];
$array = array_fetch($array ,'developer.name');

$array = [200,300,400];
$value = array_first($array , function($key , $val){
   return $val >= 250;
});
// 300
$array = [200,300,400];
$value = array_last($array , function($key , $val){
    return $val >= 250;
});
//400

$array = ['name' => 'Joe', 'languages' => ['PHP', 'Ruby']];
$array = array_flatten($array);

$array = ['names' => ['joe' => ['programmer']]];

$array = array_forget($array, 'names.joe');
//NULL

$array = ['names' => ['joe' => ['programmer']]];
$value = array_get($array , 'name.joe','default-value');
//default-value
$value = array_get($array , 'names.joe','default-value');
//array(1) { [0]=> string(10) "programmer" }

$array = ['name' => 'Joe', 'age' => 27, 'votes' => 1];
$array = array_only($array ,['name','age']);
//array(2) { ["name"]=> string(3) "Joe" ["age"]=> int(27) }

$array = ['name' => 'Joe', 'age' => 27, 'votes' => 1];
$name  = array_pull($array , 'name');
//echo $name;
//Joe
//array(2) { ["age"]=> int(27) ["votes"]=> int(1) }

$array = [100, '200', 300, '400', 500];

$array = array_where($array, function($key, $value)
{
    return is_string($value);
});
//array(2) { [1]=> string(3) "200" [3]=> string(3) "400" }

$array = head($array);
//string(3) "200"

$array = last([100, '200', 300, '400', 500]);
//int(500)


var_dump($array);