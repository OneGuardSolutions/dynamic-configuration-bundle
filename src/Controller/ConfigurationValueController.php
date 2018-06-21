<?php

/*
 * This file is part of the OneGuard DynamicConfigurationBundle.
 *
 * (c) OneGuard <contact@oneguard.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OneGuard\Bundle\DynamicConfigurationBundle\Controller;

use OneGuard\Bundle\DynamicConfigurationBundle\ConfigurationResolverFactory;
use OneGuard\Bundle\DynamicConfigurationBundle\DefinitionRegistry;
use OneGuard\Bundle\DynamicConfigurationBundle\Entity\ConfigurationValue;
use OneGuard\Bundle\DynamicConfigurationBundle\EntityDefinition;
use OneGuard\Bundle\DynamicConfigurationBundle\Form\ConfigurationValuesType;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/configuration-value")
 */
class ConfigurationValueController extends Controller {
	/**
	 * @var \Doctrine\ORM\EntityManagerInterface
	 */
	private $entityManager;

	/**
	 * @var DefinitionRegistry
	 */
	private $registry;

	/**
	 * @var ConfigurationResolverFactory
	 */
	private $factory;

	public function __construct(
		RegistryInterface $doctrine,
		DefinitionRegistry $registry,
		ConfigurationResolverFactory $factory
	) {
		$this->entityManager = $doctrine->getEntityManagerForClass(ConfigurationValue::class);
		$this->registry = $registry;
		$this->factory = $factory;
	}

	/**
	 * @Route(path="/assign", name="configurationValue.assign")
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public function assignAction(Request $request) {
		$configurationValues = $this->entityManager->getRepository(ConfigurationValue::class)->findAll();
		$existingValueKeys = array_map(function (ConfigurationValue $value) {
			return $value->getKey();
		}, $configurationValues);
		$missingValueKeys = array_diff($this->registry->keys(), $existingValueKeys);
		$configurationValues = array_merge(
			$configurationValues,
			array_map(function (string $key) {
				$value = new ConfigurationValue();
				$value->setKey($key);
				return $value;
			}, $missingValueKeys)
		);

		$form = $this->createForm(ConfigurationValuesType::class, ['configurationValues' => $configurationValues]);
		$form->handleRequest($request);
		if ($form->isSubmitted() and $form->isValid()) {
			/* @var $configurationValue ConfigurationValue */
			foreach ($form->get('configurationValues')->getData() as $configurationValue) {
				$isPersisted = \Doctrine\ORM\UnitOfWork::STATE_MANAGED ===
					$this->entityManager->getUnitOfWork()->getEntityState($configurationValue);

				if ($configurationValue->getValue() === null and $isPersisted) {
					$this->entityManager->remove($configurationValue);
				} else if ($configurationValue->getValue() !== null and !$isPersisted) {
					$this->entityManager->persist($configurationValue);
				}
			}
			$this->entityManager->flush();

			return $this->redirectToRoute('configurationValue.view');
		}

		return $this->render('OneGuardDynamicConfigurationBundle:ConfigurationValue:assign.html.twig', ['form' => $form->createView()]);
	}

	/**
	 * @Route(path="/view", name="configurationValue.view")
	 */
	public function viewAction() {
		/* @var $configurationValues ConfigurationValue[] */
		$configurationValues = $this->entityManager->getRepository(ConfigurationValue::class)->findAll();
		$labels = [];
		$propertyAccessor = new PropertyAccessor();
		foreach ($configurationValues as $configurationValue) {
			$definition = $this->registry->get($configurationValue->getKey());
			if ($definition instanceof EntityDefinition) {
				$object = $this->factory->create($definition->getKey())->resolve();
				$labels[$definition->getKey()] = $propertyAccessor->getValue($object, $definition->getChoiceLabel());
			}
		}

		return $this->render('OneGuardDynamicConfigurationBundle:ConfigurationValue:view.html.twig', [
			'configurationValues' => $configurationValues,
			'translationDomain' => $this->getParameter('one_guard.dynamic_configuration.translation_domain'),
			'translationPrefix' => $this->getParameter('one_guard.dynamic_configuration.translation_prefix'),
			'labels' => $labels
		]);
	}
}
