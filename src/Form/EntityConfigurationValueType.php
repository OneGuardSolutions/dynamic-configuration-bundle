<?php

/*
 * This file is part of the OneGuard DynamicConfigurationBundle.
 *
 * (c) OneGuard <contact@oneguard.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OneGuard\Bundle\DynamicConfigurationBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;

class EntityConfigurationValueType extends EntityType {
	/**
	 * @var RegistryInterface
	 */
	private $doctrine;

	public function __construct(RegistryInterface $doctrine) {
		parent::__construct($doctrine);
		$this->doctrine = $doctrine;
	}

	public function buildForm(FormBuilderInterface $builder, array $options) {
		parent::buildForm($builder, $options);
		$builder->addModelTransformer(new CallbackTransformer(
			function ($id) use ($options) {
				return $id === null ? null : $this->doctrine->getRepository($options['class'])->find($id);
			},
			function ($entity) use ($options) {
				return $entity === null ?
					null : $this->doctrine->getEntityManagerForClass($options['class'])->getUnitOfWork()->getSingleIdentifierValue($entity);
			}
		));
	}
}
