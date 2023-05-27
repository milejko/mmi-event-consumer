<?php

namespace CmsEventPublisher\Config;

class AmqpExchangeConfig
{
    public string   $name;
    public string   $type       = 'topic';
    public bool     $durable    = true;
    public bool     $passive    = false;
    public bool     $autodelete = false;
    public bool     $nowait     = false;
    public bool     $internal   = false;
}
