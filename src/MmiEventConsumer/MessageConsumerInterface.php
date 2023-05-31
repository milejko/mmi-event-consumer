<?php

namespace MmiEventConsumer;

interface MessageConsumerInterface
{
    public function run(callable $callback): void;
}
