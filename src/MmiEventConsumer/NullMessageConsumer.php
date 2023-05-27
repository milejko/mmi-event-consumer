<?php

namespace CmsEventPublisher;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class NullMessageConsumer implements MessageConsumerInterface
{
    public function __construct()
    {
    }

    public function runConsumer(callable $callback): void
    {
    }
}
