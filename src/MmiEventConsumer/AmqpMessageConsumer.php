<?php

namespace CmsEventPublisher;

use CmsEventPublisher\Config\AmqpConsumerConfig;
use CmsEventPublisher\Config\AmqpExchangeConfig;
use CmsEventPublisher\Config\AmqpQueueConfig;
use CmsEventPublisher\Config\AmqpServerConfig;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class AmqpMessageConsumer
{
    public function __construct(
        private AmqpServerConfig $amqpServerConfig,
        private AmqpExchangeConfig $amqpExchangeConfig = new AmqpExchangeConfig(),
        private AmqpQueueConfig $amqpQueueConfig = new AmqpQueueConfig(),
        private AmqpConsumerConfig $amqpConsumerConfig = new AmqpConsumerConfig(),
    )
    {
    }

    public function runConsumer(string $exchangeName, string $queueName, callable $callback): void
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
            $exchangeName,
            $this->amqpExchangeConfig->type,
            $this->amqpExchangeConfig->passive,
            $this->amqpExchangeConfig->durable,
            $this->amqpExchangeConfig->autodelete,
            $this->amqpExchangeConfig->internal,
            $this->amqpExchangeConfig->nowait,
        );

        $channel->queue_declare(
            $queueName,
            $this->amqpQueueConfig->passive,
            $this->amqpQueueConfig->durable,
            $this->amqpQueueConfig->exclusive,
            $this->amqpQueueConfig->autodelete,
            $this->amqpQueueConfig->nowait,
        );

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
