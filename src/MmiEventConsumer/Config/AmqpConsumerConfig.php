<?php

namespace CmsEventPublisher\Config;

class AmqpConsumerConfig
{
    public string   $tag            = '';
    public bool     $exclusive      = false;
    public bool     $nowait         = false;
    public bool     $noack          = false;
    public bool     $nolocal        = false;
}
