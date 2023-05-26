<?php

namespace CmsEventPublisher;

interface MessageConsumerInterface
{
    public function consume(): MessageInterface;
}
