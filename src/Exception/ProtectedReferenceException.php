<?php

namespace OneGuard\Bundle\DynamicConfigurationBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ProtectedReferenceException extends \Exception implements HttpExceptionInterface {
	public function __construct($message = "", $code = 0, \Throwable $previous = null) {
		parent::__construct($message, $code, $previous);
	}

	public function getStatusCode() {
		return 400;
	}

	public function getHeaders() {
		return [];
	}
}
