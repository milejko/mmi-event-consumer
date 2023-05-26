<?php

namespace CmsEventPublisher;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class AmqpMessageConsumer
{
    public function __construct(private AmqpConfig $amqpConfig)
    {
    }

    public function runConsumer(callable $callback): void
    {
        $connection = new AMQPStreamConnection(
            $this->amqpConfig->host,
            $this->amqpConfig->port,
            $this->amqpConfig->user,
            $this->amqpConfig->password,
            $this->amqpConfig->vhost
        );
        $channel = $connection->channel();
        //create the exchange if it doesn't exist already
        $channel->exchange_declare(
            $this->amqpConfig->exchangeName,
            $this->amqpConfig->exchangeType,
            $this->amqpConfig->exchangePassive,
            $this->amqpConfig->exchangeDurable,
            $this->amqpConfig->exchangeAutodelete,
        );
        //create the queue if it doesn't exist already
        $channel->queue_declare(
            $this->amqpConfig->queueName,
            $this->amqpConfig->queuePassive,
            $this->amqpConfig->queueDurable,
            $this->amqpConfig->queueExclusive,
            $this->amqpConfig->queueAutodelete,
        );
        $channel->basic_consume(
            $this->amqpConfig->queueName,
            $this->amqpConfig->consumerTag,
            false,
            false,
            false,
            false,
            $callback
        );

        while ($channel->is_open()) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}
