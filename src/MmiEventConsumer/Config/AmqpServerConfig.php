<?php

namespace CmsEventPublisher\Config;

class AmqpServerConfig
{
    public string   $host;
    public int      $port   = 5672;
    public string   $user;
    public string   $password;
    public string   $vhost  = '';
}
