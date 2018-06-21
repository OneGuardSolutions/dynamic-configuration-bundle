<?php

namespace OneGuard\Bundle\DynamicConfigurationBundle\Exception;

use PHPUnit\Framework\TestCase;

class ProtectedReferenceExceptionTest extends TestCase {
    public function testConstruct() {
        $throwable = new \Exception();
        $exception = new ProtectedReferenceException("test message", 123, $throwable);

        $this->assertEquals("test message", $exception->getMessage());
        $this->assertEquals(123, $exception->getCode());
        $this->assertSame($throwable, $exception->getPrevious());

        $this->assertEquals(400, $exception->getStatusCode());
    }
}
