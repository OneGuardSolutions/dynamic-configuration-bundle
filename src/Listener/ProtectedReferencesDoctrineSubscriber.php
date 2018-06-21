<?php

/*
 * This file is part of the OneGuard DynamicConfigurationBundle.
 *
 * (c) OneGuard <contact@oneguard.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OneGuard\Bundle\DynamicConfigurationBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use OneGuard\Bundle\DynamicConfigurationBundle\Exception\ProtectedReferenceException;
use OneGuard\Bundle\DynamicConfigurationBundle\ProtectedReferencesChecker;

class ProtectedReferencesDoctrineSubscriber implements EventSubscriber {
	/**
	 * @var ProtectedReferencesChecker
	 */
	private $checker;

	public function __construct(ProtectedReferencesChecker $checker) {
		$this->checker = $checker;
	}

	public function getSubscribedEvents() {
		return [
			'preRemove'
		];
	}

	/**
	 * @param LifecycleEventArgs $args
	 * @throws ProtectedReferenceException
	 * @throws \Doctrine\ORM\NoResultException
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function preRemove(LifecycleEventArgs $args) {
		$this->checker->checkReferenceForProtection($args->getObject());
	}
}
