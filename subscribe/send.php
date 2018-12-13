<?php
/**
 * 博客地址：https://yuanxuxu.com/2013/05/31/rabbitmqguan-fang-zhong-wen-ru-men-jiao-cheng-phpban--di-san-bu-fen-fa-bu--ding-yue--publishsubscr/
 * PHP amqp(RabbitMQ) Demo-3
 * @author  yuansir <yuansir@live.cn/yuanxuxu.com>
 */

$exchangeName = 'logs';
$message = empty($argv[1]) ? 'info:Hello World!' : ' ' . $argv[1];

$connection = new AMQPConnection([
    'host'     => '127.0.0.1',
    'port'     => '5672',
    'vhost'    => '/',
    'login'    => 'guest',
    'password' => 'guest'
]);
$connection->connect() or die("Cannot connect to the broker!\n");

$channel = new AMQPChannel($connection);
$exchange = new AMQPExchange($channel);
$exchange->setName($exchangeName);
$exchange->setType(AMQP_EX_TYPE_FANOUT);
$exchange->declare();

$exchange->publish($message, '');
var_dump("[x] Sent $message");

$connection->disconnect();