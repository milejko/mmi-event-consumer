<?php

namespace CmsEventPublisher;

class AmqpConfig
{
    public string $host;
    public int $port = 5672;
    public string $user;
    public string $password;
    public string $vhost;

    public string $exchangeName;
    public string $exchangeType = 'topic';
    public bool $exchangeDurable = true;
    public bool $exchangePassive = false;
    public bool $exchangeAutodelete = false;

    public string $queueName = '';
    public string $queueType = 'topic';
    public bool $queueDurable = true;
    public bool $queuePassive = false;
    public bool $queueAutodelete = true;
    public bool $queueExclusive = true;

    public string $consumerTag = '';
}