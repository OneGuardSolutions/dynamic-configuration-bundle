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

abstract class Definition {
	private $key;

	/**
	 * Definition constructor.
	 * @param $key
	 */
	public function __construct($key) {
		$this->key = $key;
	}

	/**
	 * @return mixed
	 */
	public function getKey() {
		return $this->key;
	}
}
