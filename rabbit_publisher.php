<?php
//配置信息
$conn_args = array(
    'host' => '127.0.0.2',
    'port' => '5672',
    'login' => 'guest',
    'password' => 'guest',
    'vhost'=>'/'
);
$e_name = 'e_liaosp'; //交换机名
//$q_name = 'q_linvo'; //无需队列名
$k_route = 'key_1'; //路由key

//创建连接和channel
$conn = new AMQPConnection($conn_args);
if (!$conn->connect()) {
    die("Cannot connect to the broker!\n");
}
$channel = new AMQPChannel($conn);



//创建交换机对象
$ex = new AMQPExchange($channel);
$ex->setName($e_name);
date_default_timezone_set("Asia/Shanghai");
//发送消息
//$channel->startTransaction(); //开始事务
for($i=0; $i<2; ++$i){
//    sleep(1);//休眠1秒
    $msg =$i;
    //消息内容
    $message = "TEST MESSAGE!".date("h:i:sa");
    echo "Send Message:$i".'消息'.$ex->publish($msg, $k_route)."\n";
}
//$ex->publish('完成', $k_route);
//$channel->commitTransaction(); //提交事务

$conn->disconnect();
?>
