<?php
/**
 * @author liaoshengping@haoxiaec.com
 * @Time: 2018/12/12 -16:12
 * @Version 1.0
 * @Describe:
 * 1:
 * 2:
 * ...
 */
//host info
$host =[
    'host' => '127.0.0.1',
    'port' => '5672',
    'login' => 'guest',
    'password' => 'guest',
    'vhost'=>'/'
];
$exchangeName = 'orders';
$queueName ='order_id';
$key='waning';
//connect
try{
    $connect = new AMQPConnection($host);
    $connect->connect();
    if(!$connect){die('Connet Rabbit Fail!!! 检查之后服务器端口，再重试');};
//创建通道
    $channel = new AMQPChannel($connect);
//创建交换机
    $exchange = new AMQPExchange($channel);
    /*
     * 设置交换机的信息
     */
    $exchange->setName($exchangeName);
//这个设置，决定了是哪种模式   //类型  AMQP_EX_TYPE_FANOUT（一条消息通知多个消费者）订阅  AMQP_EX_TYPE_DIRECT  （直接消耗掉）队列
    $exchange->setType(AMQP_EX_TYPE_FANOUT);
    $exchange->setFlags(AMQP_DURABLE); //1.AMQP_DURABLE持久化   2.AMQP_EXCLUSIVE 当与消费者（consumer）断开连接的时候，这个队列应当被删除。我们可以使用 exclusive 标识。
    $exchange->declareExchange();
//创建了交换机
//    $queue = new AMQPQueue($channel);
//    $queue->setName($queueName);
//    $queue->setFlags(AMQP_DURABLE);
//    $queue->declareQueue();
//    $queue->bind($exchangeName,$key);
    $exchange->publish('hello',$key);
}catch (AMQPConnectionException $exception){
    echo $exception;exit();
}
$connect->disconnect();