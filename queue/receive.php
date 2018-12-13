<?php
/**
 * @author liaoshengping@haoxiaec.com
 * @Time: 2018/12/12 -17:18
 * @Version 1.0
 * @Describe:
 * 1:
 * 2:
 * ...
 */
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
$connect = new AMQPConnection($host);
$connect->connect();
$channel = new AMQPChannel($connect);
$exchange = new AMQPExchange($channel);
$exchange->setName($exchangeName);
$exchange->setType(AMQP_EX_TYPE_FANOUT);
$exchange->setFlags(AMQP_DURABLE);
$exchange->declareExchange();
$queue = new AMQPQueue($channel);
$queue->setName($queueName);
$queue->setFlags(AMQP_DURABLE);
$queue->declareQueue();
$queue->bind($exchangeName,$key);

echo "Message:\n";
while (true) {
    $queue->consume('processMessage');
//自动ACK应答
    //$queue->consume('processMessage', AMQP_AUTOACK);
}
/*
* 消费回调函数
* 处理消息
*/
function processMessage($envelope, $q)
{
    $msg = $envelope->getBody();
    echo $msg . "\n"; //处理消息
    $q->ack($envelope->getDeliveryTag()); //手动发送ACK应答
}
