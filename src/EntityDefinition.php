<?php

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
