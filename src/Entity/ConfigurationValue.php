<?php

namespace OneGuard\Bundle\DynamicConfigurationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class ConfigurationValue {
	/**
	 * @var string
	 *
	 * @ORM\Id()
	 * @ORM\Column(name="`key`", nullable=false, type="string", length=64)
	 *
	 * @Assert\NotNull()
	 * @Assert\NotBlank()
	 * @Assert\Length(max="64")
	 */
	private $key;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="`value`", nullable=false, type="text")
	 */
	private $value;

	/**
	 * @return string|null
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * @param string|null $key
	 */
	public function setKey(string $key = null) {
		$this->key = $key;
	}

	/**
	 * @return string|null
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @param string|null $value
	 */
	public function setValue(string $value = null) {
		$this->value = $value;
	}
}
