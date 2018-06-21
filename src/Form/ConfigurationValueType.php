<?php

namespace OneGuard\Bundle\DynamicConfigurationBundle\Form;

use OneGuard\Bundle\DynamicConfigurationBundle\DefinitionRegistry;
use OneGuard\Bundle\DynamicConfigurationBundle\Entity\ConfigurationValue;
use OneGuard\Bundle\DynamicConfigurationBundle\EntityDefinition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigurationValueType extends AbstractType {
	/**
	 * @var DefinitionRegistry
	 */
	private $registry;

	private $translationDomain;
	private $translationPrefix;

	public function __construct(
		DefinitionRegistry $registry,
		string $translationDomain,
		string $translationPrefix
	) {
		$this->registry = $registry;
		$this->translationDomain = $translationDomain;
		$this->translationPrefix = $translationPrefix;
	}

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
				/* @var $configurationValue ConfigurationValue */
				$configurationValue = $event->getData();
				$definition = $this->registry->get($configurationValue->getKey());
				switch (get_class($definition)) {
					case EntityDefinition::class:
						/* @var $definition EntityDefinition */
						$form = $event->getForm();
						$form->add('value', EntityConfigurationValueType::class, [
							'class' => $definition->getClass(),
							'choice_label' => $definition->getChoiceLabel(),
							'label' => $this->translationPrefix . $configurationValue->getKey(),
							'translation_domain' => $this->translationDomain,
							'placeholder' => 'Please choose',
							'empty_data' => null,
							'required' => false
						]);
						break;
					default: // assume StringDefinition
						$event->getForm()->add('value', TextType::class, [
							'label' => $this->translationPrefix . $configurationValue->getKey(),
							'translation_domain' => $this->translationDomain,
							'required' => false
						]);
				}
			});
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefault('data_class', ConfigurationValue::class);
	}
}
