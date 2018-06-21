<?php

namespace OneGuard\Bundle\DynamicConfigurationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class ConfigurationValuesType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('configurationValues', CollectionType::class, [
				'entry_type' => ConfigurationValueType::class,
				'attr' => ['class' => 'panel panel-default panel-body'],
			]);
	}
}
