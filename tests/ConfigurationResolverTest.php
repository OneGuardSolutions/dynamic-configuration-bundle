<?php

namespace OneGuard\Bundle\DynamicConfigurationBundle;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use OneGuard\Bundle\DynamicConfigurationBundle\Entity\ConfigurationValue;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ConfigurationResolverTest extends TestCase {
	public function testResolve() {
		$value = new ConfigurationValue();
		$value->setKey('test.property');
		$value->setValue('test.property.value');

		$repo = $this->getMockBuilder(ServiceEntityRepositoryInterface::class)
			->disableOriginalConstructor()
			->setMethods(['find'])
			->getMockForAbstractClass();
		$repo->method('find')->willReturn($value);

		$doctrine = $this->getMockBuilder(RegistryInterface::class)
			->disableOriginalConstructor()
			->getMockForAbstractClass();
		$doctrine->method('getRepository')->willReturn($repo);

		$definition = new StringDefinition('test.property');
		$resolver = new ConfigurationResolver($doctrine, $definition);

		$this->assertEquals('test.property.value', $resolver->resolve());
	}

	public function testGetDefinition() {
		$definition = new StringDefinition('test.property');
		$doctrine = $this->createMock(RegistryInterface::class);
		$resolver = new ConfigurationResolver($doctrine, $definition);

		$this->assertSame($definition, $resolver->getDefinition());
	}
}
