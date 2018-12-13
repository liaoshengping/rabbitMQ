<?php

/**
 * PHP amqp(RabbitMQ) Demo-3
 * @author  yuansir <yuansir@live.cn/yuanxuxu.com>
 */
$exchangeName = 'logs';

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
$queue = new AMQPQueue($channel);
$queue->setFlags(AMQP_EXCLUSIVE);
$queue->declare();
$queue->bind($exchangeName, '');

echo ('[*] Waiting for messages. To exit press CTRL+C');

while (true) {
    $queue->consume('callback');
}
$connection->disconnect();

function callback($envelope, $queue)
{
    $msg = $envelope->getBody();
    var_dump(" [x] Received:" . $msg);
    $queue->nack($envelope->getDeliveryTag());
}