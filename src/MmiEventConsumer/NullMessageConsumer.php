<?php

namespace MmiEventConsumer;

class NullMessageConsumer implements MessageConsumerInterface
{
    public function __construct()
    {
    }

    public function run(callable $callback): void
    {
    }
}
