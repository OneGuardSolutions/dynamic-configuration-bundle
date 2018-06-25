<?php

/*
 * This file is part of the OneGuard DynamicConfigurationBundle.
 *
 * (c) OneGuard <contact@oneguard.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OneGuard\Bundle\DynamicConfigurationBundle;

use OneGuard\Bundle\DynamicConfigurationBundle\Entity\ConfigurationValue;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ConfigurationResolver {
	/**
	 * @var RegistryInterface
	 */
	private $doctrine;

	/**
	 * @var Definition
	 */
	private $definition;

	public function __construct(RegistryInterface $doctrine, Definition $definition) {
		$this->doctrine = $doctrine;
		$this->definition = $definition;
	}

	public function getDefinition() : Definition {
		return $this->definition;
	}

	/**
	 * @return null|object
	 * @throws \UnexpectedValueException
	 */
	public function resolve() {
		$repo = $this->doctrine->getRepository(ConfigurationValue::class);
		$configurationValue = $repo->find($this->definition->getKey());
		if ($configurationValue === null) {
			return null;
		}

		switch (get_class($this->definition)) {
			case StringDefinition::class:
				return $configurationValue->getValue();
			case EntityDefinition::class:
				/* @var $entityDefinition EntityDefinition */
				$entityDefinition = $this->definition;
				return $this->doctrine->getRepository($entityDefinition->getClass())->find($configurationValue->getValue());
			default:
				throw new \UnexpectedValueException();
		}
	}
}
