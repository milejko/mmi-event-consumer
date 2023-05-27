<?php

namespace CmsEventPublisher;

interface MessageConsumerInterface
{
    public function runConsumer(callable $callback): void;
}