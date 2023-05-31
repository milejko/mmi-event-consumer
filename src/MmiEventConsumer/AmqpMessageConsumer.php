<?php

namespace MmiEventConsumer;

use MmiEventConsumer\Config\AmqpConsumerConfig;
use MmiEventConsumer\Config\AmqpExchangeConfig;
use MmiEventConsumer\Config\AmqpQueueConfig;
use MmiEventConsumer\Config\AmqpServerConfig;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class AmqpMessageConsumer implements MessageConsumerInterface
{
    public function __construct(
        private AmqpServerConfig $amqpServerConfig,
        private AmqpExchangeConfig $amqpExchangeConfig,
        private AmqpQueueConfig $amqpQueueConfig = new AmqpQueueConfig(),
        private AmqpConsumerConfig $amqpConsumerConfig = new AmqpConsumerConfig(),
    ) {
    }

    public function run(callable $callback): void
    {
        $connection = new AMQPStreamConnection(
            $this->amqpServerConfig->host,
            $this->amqpServerConfig->port,
            $this->amqpServerConfig->user,
            $this->amqpServerConfig->password,
            $this->amqpServerConfig->vhost
        );
        $channel = $connection->channel();

        $channel->exchange_declare(
            $this->amqpExchangeConfig->name,
            $this->amqpExchangeConfig->type,
            $this->amqpExchangeConfig->passive,
            $this->amqpExchangeConfig->durable,
            $this->amqpExchangeConfig->autodelete,
            $this->amqpExchangeConfig->internal,
            $this->amqpExchangeConfig->nowait,
        );

        $queueDefinition = $channel->queue_declare(
            $this->amqpQueueConfig->name,
            $this->amqpQueueConfig->passive,
            $this->amqpQueueConfig->durable,
            $this->amqpQueueConfig->exclusive,
            $this->amqpQueueConfig->autodelete,
            $this->amqpQueueConfig->nowait,
        );
        if (!is_array($queueDefinition)) {
            return;
        }
        $queueName = $queueDefinition[0];

        $channel->basic_consume(
            $queueName,
            $this->amqpConsumerConfig->tag,
            $this->amqpConsumerConfig->nolocal,
            $this->amqpConsumerConfig->noack,
            $this->amqpConsumerConfig->exclusive,
            $this->amqpConsumerConfig->nowait,
            $callback
        );

        while ($channel->is_open()) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}
