<?php

namespace MmiEventConsumer;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class AmqpMessageConsumer implements MessageConsumerInterface
{
    private const CONNECTION_INSIST                = false;
    private const CONNECTION_PROTOCOL              = 'AMQPLAIN';
    private const CONNECTION_LOGIN_RESPONSE        = null;
    private const CONNECTION_LOCALE                = 'en_US';
    private const CONNECTION_TIMEOUT               = 5;
    private const CONNECTION_READ_WRITE_TIMEOUT    = 5;
    private const CONNECTION_CONTEXT               = null;
    private const CONNECTION_KEEPALIVE             = true;
    private const CONNECTION_HEARTBEAT             = 30;

    private const QUEUE_DURABLE     = true;
    private const QUEUE_PASSIVE     = false;
    private const QUEUE_AUTODELETE  = false;
    private const QUEUE_EXCLUSIVE   = false;
    private const QUEUE_ROUTING_KEY = '*';

    private const EXCHANGE_TYPE = 'topic';
    private const EXCHANGE_DURABLE = true;
    private const EXCHANGE_PASSIVE = false;
    private const EXCHANGE_AUTODELETE = false;

    private const CONSUMER_TAG          = 'mmi-event-consumer';
    private const CONSUMER_EXCLUSIVE    = false;
    private const CONSUMER_NOLOCAL      = false;
    private const CONSUMER_NOACK        = false;
    private const CONSUMER_NOWAIT       = false;

    public function __construct(
        private string $host,
        private int $port,
        private string $user,
        private string $password,
        private string $vhost,
    ) {
    }

    public function run(
        callable $callback,
        string $exchange = 'sample-exchange-name',
        string $queue = ''
    ): void {
        $connection = new AMQPStreamConnection(
            $this->host,
            $this->port,
            $this->user,
            $this->password,
            $this->vhost,
            self::CONNECTION_INSIST,
            self::CONNECTION_PROTOCOL,
            self::CONNECTION_LOGIN_RESPONSE,
            self::CONNECTION_LOCALE,
            self::CONNECTION_TIMEOUT,
            self::CONNECTION_READ_WRITE_TIMEOUT,
            self::CONNECTION_CONTEXT,
            self::CONNECTION_KEEPALIVE,
            self::CONNECTION_HEARTBEAT,
        );
        $channel = $connection->channel();

        $queueDefinition = $channel->queue_declare(
            $queue,
            self::QUEUE_PASSIVE,
            self::QUEUE_DURABLE,
            self::QUEUE_EXCLUSIVE,
            self::QUEUE_AUTODELETE,
        );

        if (!is_array($queueDefinition)) {
            return;
        }
        $calculatedQueueName = $queueDefinition[0];

        //create the exchange if it doesn't exist already
        $channel->exchange_declare(
            $exchange,
            self::EXCHANGE_TYPE,
            self::EXCHANGE_PASSIVE,
            self::EXCHANGE_DURABLE,
            self::EXCHANGE_AUTODELETE
        );

        $channel->queue_bind($calculatedQueueName, $exchange, self::QUEUE_ROUTING_KEY);

        $channel->basic_consume(
            $calculatedQueueName,
            self::CONSUMER_TAG,
            self::CONSUMER_NOLOCAL,
            self::CONSUMER_NOACK,
            self::CONSUMER_EXCLUSIVE,
            self::CONSUMER_NOWAIT,
            $callback
        );

        while ($channel->is_open()) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}
