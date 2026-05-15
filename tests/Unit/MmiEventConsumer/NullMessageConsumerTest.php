<?php

namespace Tests\Unit\MmiEventConsumer;

use MmiEventConsumer\NullMessageConsumer;
use PHPUnit\Framework\TestCase;

class NullMessageConsumerTest extends TestCase
{
    public function testIfMessageContainsGivenText(): void
    {
        $nullConsumer = new NullMessageConsumer();
        $callbackCalled = false;
        $nullConsumer->run(
            function () use (&$callbackCalled) {
                $callbackCalled = true;
            }
        );
        $this->assertFalse($callbackCalled);
    }
}
