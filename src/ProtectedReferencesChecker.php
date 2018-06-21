<?php

namespace OneGuard\Bundle\DynamicConfigurationBundle;

use OneGuard\Bundle\DynamicConfigurationBundle\Entity\ConfigurationValue;
use OneGuard\Bundle\DynamicConfigurationBundle\Exception\ProtectedReferenceException;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ProtectedReferencesChecker {
	/**
	 * @var RegistryInterface
	 */
	private $doctrine;
	/**
	 * @var DefinitionRegistry
	 */
	private $registry;

	public function __construct(RegistryInterface $doctrine, DefinitionRegistry $registry) {
		$this->doctrine = $doctrine;
		$this->registry = $registry;
	}

	/**
	 * @param string $class
	 * @param array|null $ids
	 * @return array
	 */
	public function findProtectedIdsByClass(string $class, array $ids = null) : array {
		$keys = [];
		foreach ($this->registry->keys() as $key) {
			$definition = $this->registry->get($key);
			if ($definition instanceof EntityDefinition and is_a($class, $definition->getClass(), true)) {
				$keys[] = $definition->getKey();
			}
		}

		if (empty($keys)) {
			return [];
		}

		$qb = $this->doctrine->getEntityManagerForClass(ConfigurationValue::class)->createQueryBuilder();
		$qb
			->select('DISTINCT (v.value)')
			->from(ConfigurationValue::class, 'v')
			->where($qb->expr()->in('v.key', ':keys'))
			->setParameter('keys', $keys);

		if ($ids !== null) {
			$qb
				->andWhere($qb->expr()->in('v.value', ':ids'))
				->setParameter('ids', $ids);
		}

		$protectedIds = $qb->getQuery()->getScalarResult();
		return empty($protectedIds) ? [] : array_merge(...$protectedIds);
	}

	/**
	 * @param $object
	 * @throws ProtectedReferenceException
	 * @throws \Doctrine\ORM\NoResultException
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function checkReferenceForProtection($object) {
		$id = $this->doctrine->getEntityManagerForClass(get_class($object))->getUnitOfWork()->getSingleIdentifierValue($object);

		$keys = [];
		foreach ($this->registry->keys() as $key) {
			$definition = $this->registry->get($key);
			if ($definition instanceof EntityDefinition and is_a($object, $definition->getClass())) {
				$keys[] = $definition->getKey();
			}
		}

		if (empty($keys)) {
			return;
		}

		$repo = $this->doctrine->getEntityManagerForClass(ConfigurationValue::class);
		$qb = $repo->createQueryBuilder();
		$countProtected = $qb
			->select('count(v)')
			->from(ConfigurationValue::class, 'v')
			->where($qb->expr()->in('v.key', ':keys'))
			->andWhere($qb->expr()->eq('v.value', ':id'))
			->getQuery()
			->setParameter('keys', $keys)
			->setParameter('id', $id)
			->getSingleScalarResult();

		if ($countProtected > 0) {
			throw new ProtectedReferenceException("Can't delete protected reference.");
		}
	}
}
