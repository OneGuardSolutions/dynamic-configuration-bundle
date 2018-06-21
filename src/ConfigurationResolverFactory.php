<?php

namespace OneGuard\Bundle\DynamicConfigurationBundle;

use Symfony\Bridge\Doctrine\RegistryInterface;

class ConfigurationResolverFactory {
	/**
	 * @var RegistryInterface
	 */
	private $doctrine;

	/**
	 * @var DefinitionRegistry
	 */
	private $registry;

	public function __construct(RegistryInterface $doctrine, DefinitionRegistry $registry) {
		$this->doctrine = $doctrine;
		$this->registry = $registry;
	}

	public function create(string $key) {
		return new ConfigurationResolver(
			$this->doctrine,
			$this->registry->get($key)
		);
	}
}
