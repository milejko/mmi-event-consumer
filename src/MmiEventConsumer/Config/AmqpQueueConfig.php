<?php

namespace CmsEventPublisher\Config;

class AmqpQueueConfig
{
    public string   $name           = '';
    public string   $type           = 'topic';
    public bool     $durable        = true;
    public bool     $passive        = false;
    public bool     $autodelete     = true;
    public bool     $exclusive      = true;
    public bool     $nowait         = false;
}
