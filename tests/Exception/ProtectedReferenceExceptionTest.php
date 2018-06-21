<?php

/*
 * This file is part of the OneGuard DynamicConfigurationBundle.
 *
 * (c) OneGuard <contact@oneguard.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
        $this->assertEquals([], $exception->getHeaders());
    }
}
