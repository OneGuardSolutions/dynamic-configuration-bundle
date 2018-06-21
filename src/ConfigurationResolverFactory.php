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
