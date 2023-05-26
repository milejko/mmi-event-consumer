<?php

namespace MmiEventConsumer\Command;

use Mmi\Command\CommandAbstract;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Event consumer command (listener)
 */
class EventConsumerCommand extends CommandAbstract
{
    /**
     * Constructor
     */
    public function __construct()
    {
        
    }

    /**
     * Metoda uruchamiająca
     * @throws DbException
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        return 0;
    }
}
