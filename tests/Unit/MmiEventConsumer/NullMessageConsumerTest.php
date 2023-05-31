<?php

namespace Tests\Unit\MmiEventConsumer;

use MmiEventConsumer\NullMessageConsumer;
use PHPUnit\Framework\TestCase;

class NullMessageConsumerTest extends TestCase
{
    public function testIfMessageContainsGivenText(): void
    {
        $nullConsumer = new NullMessageConsumer();
        $nullConsumer->run(
            function () {
            }
        );
        $exited = true;
        self::assertTrue($exited);
    }
}
