<?php

namespace OneGuard\Bundle\DynamicConfigurationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface {
	public function getConfigTreeBuilder() {
		$treeBuilder = new TreeBuilder();
		$rootNode = $treeBuilder->root('one_guard_dynamic_configuration');

		$rootNode
			->addDefaultsIfNotSet()
			->children()
				->arrayNode('definitions')
					->prototype('array')
						->children()
							->enumNode('type')
								->values(['entity', 'string'])
								->isRequired()
							->end()
							->arrayNode('options')
								->children()
									->scalarNode('class')
										->validate()
											->ifTrue(function ($class) {
												return !class_exists($class);
											})
											->thenInvalid("Class doesn't exist.")
										->end()
									->end()
									->scalarNode('choice_label')->end()
								->end()
//								->validate()
//									->ifTrue(function ($options) {
//										if (!empty($options['class'])) {
//											$propertyAccessor = new PropertyAccessor();
//											return !$propertyAccessor->isReadable($options['class'], $options['choice_label']);
//										}
//										return true;
//									})
//									->thenInvalid("Property not accessible.")
//								->end()
							->end()
						->end()
					->end()
				->end()
				->scalarNode('translation_domain')
					->defaultValue('messages')
					->cannotBeEmpty()
				->end()
				->scalarNode('translation_prefix')
					->defaultValue('')
				->end()
			->end();

		return $treeBuilder;
	}
}
