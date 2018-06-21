<?php

namespace OneGuard\Bundle\DynamicConfigurationBundle\DependencyInjection;

use OneGuard\Bundle\DynamicConfigurationBundle\DefinitionRegistry;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class OneGuardDynamicConfigurationExtension extends Extension {
	/**
	 * @param array $configs
	 * @param ContainerBuilder $container
	 * @throws \Exception
	 */
	public function load(array $configs, ContainerBuilder $container) {
		$loader = new YamlFileLoader(
			$container,
			new FileLocator(__DIR__.'/../Resources/config')
		);
		$loader->load('services.yml');

		$configuration = new Configuration();
		$config = $this->processConfiguration($configuration, $configs);

		$container->setParameter('one_guard.dynamic_configuration.translation_domain', $config['translation_domain']);
		$container->setParameter('one_guard.dynamic_configuration.translation_prefix', $config['translation_prefix']);

		$register = $container->getDefinition(DefinitionRegistry::class);

		foreach ($config['definitions'] as $key => $definitionConfig) {
			switch ($definitionConfig['type']) {
				case 'entity':
					$register->addMethodCall('registerEntity', [
						$key,
						$definitionConfig['options']['class'],
						$definitionConfig['options']['choice_label']
					]);
					break;
				case 'string':
					$register->addMethodCall('registerString', [$key]);
					break;
			}
		}
	}
}
