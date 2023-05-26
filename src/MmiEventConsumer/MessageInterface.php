<?php

namespace MmiEventConsumer;

interface MessageInterface
{
    public function getContent(): string;

    public function getRoute(): string;
}
