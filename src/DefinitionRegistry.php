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

class DefinitionRegistry {
	/**
	 * @var Definition[]
	 */
	private $definitions = [];

	/**
	 * @param Definition $definition
	 * @throws \InvalidArgumentException
	 */
	public function register(Definition $definition) {
		if (array_key_exists($definition->getKey(), $this->definitions)) {
			throw new \InvalidArgumentException('Definition already exists (Key: ' . $definition->getKey() . ').');
		}
		$this->definitions[$definition->getKey()] = $definition;
	}

	/**
	 * @param string $key
	 * @return Definition
	 * @throws \InvalidArgumentException
	 */
	public function get(string $key) : Definition {
		if (!array_key_exists($key, $this->definitions)) {
			throw new \InvalidArgumentException('Definition not found (Key: ' . $key . ').');
		}
		return $this->definitions[$key];
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function has(string $key) : bool {
		return array_key_exists($key, $this->definitions);
	}

	/**
	 * @return string[]
	 */
	public function keys() : array {
		return array_keys($this->definitions);
	}

	public function registerEntity($key, $class, $choiceLabel) {
		$this->register(new EntityDefinition($key, $class, $choiceLabel));
	}

	public function registerString($key) {
		$this->register(new StringDefinition($key));
	}
}
