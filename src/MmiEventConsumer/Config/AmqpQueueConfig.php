<?php

namespace MmiEventConsumer\Config;

class AmqpQueueConfig
{
    public string   $name           = '';
    public string   $type           = 'topic';
    public bool     $durable        = true;
    public bool     $passive        = false;
    public bool     $autodelete     = false;
    public bool     $exclusive      = false;
    public bool     $nowait         = false;
}
