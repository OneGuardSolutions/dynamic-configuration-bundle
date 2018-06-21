<?php

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
