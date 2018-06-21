<?php

/*
 * This file is part of the OneGuard DynamicConfigurationBundle.
 *
 * (c) OneGuard <contact@oneguard.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OneGuard\Bundle\DynamicConfigurationBundle\Entity;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class ConfigurationValueTest extends TestCase {
    public function testGetAndSetKey() {
        $value = new ConfigurationValue();
        $value->setKey('test.key');

        $this->assertEquals('test.key', $value->getKey());
    }

    public function testGetAndSetValue() {
        $value = new ConfigurationValue();
        $value->setValue('test.value');

        $this->assertEquals('test.value', $value->getValue());
    }
}
