<?php

use MmiEventConsumer\AmqpMessageConsumer;
use MmiEventConsumer\MessageConsumerInterface;
use MmiEventConsumer\NullMessageConsumer;
use Psr\Container\ContainerInterface;

use function DI\env;

return [
    'mmi.consumer.queue.enabled'    => env('MMI_CONSUMER_QUEUE_ENABLED', false),
    'mmi.consumer.queue.host'       => env('MMI_CONSUMER_QUEUE_HOST', 'localhost'),
    'mmi.consumer.queue.port'       => env('MMI_CONSUMER_QUEUE_PORT', 5672),
    'mmi.consumer.queue.vhost'      => env('MMI_CONSUMER_QUEUE_VHOST', ''),
    'mmi.consumer.queue.username'   => env('MMI_CONSUMER_QUEUE_USERNAME', ''),
    'mmi.consumer.queue.password'   => env('MMI_CONSUMER_QUEUE_PASSWORD', ''),

    //message publisher
    MessageConsumerInterface::class => function (ContainerInterface $container) {
        //rabbit not enabled (using file publisher)
        if (!$container->get('mmi.consumer.queue.enabled')) {
            return new NullMessageConsumer();
        }
        //AMQP publisher
        return new AmqpMessageConsumer(
            $container->get('mmi.consumer.queue.host'),
            $container->get('mmi.consumer.queue.port'),
            $container->get('mmi.consumer.queue.username'),
            $container->get('mmi.consumer.queue.password'),
            $container->get('mmi.consumer.queue.vhost'),
        );
    }
];
