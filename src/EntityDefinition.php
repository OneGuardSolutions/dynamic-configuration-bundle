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

class EntityDefinition extends Definition {
	private $class;
	private $choiceLabel;

	/**
	 * EntityDefinition constructor.
	 * @param $key
	 * @param $class
	 * @param $choiceLabel
	 */
	public function __construct($key, $class, $choiceLabel) {
		parent::__construct($key);
		$this->class = $class;
		$this->choiceLabel = $choiceLabel;
	}

	/**
	 * @return mixed
	 */
	public function getClass() {
		return $this->class;
	}

	/**
	 * @return mixed
	 */
	public function getChoiceLabel() {
		return $this->choiceLabel;
	}
}
