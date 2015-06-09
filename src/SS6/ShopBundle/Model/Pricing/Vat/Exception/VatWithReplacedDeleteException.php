<?php

namespace SS6\ShopBundle\Model\Pricing\Vat\Exception;

use Exception;
use SS6\ShopBundle\Model\Pricing\Vat\Exception\VatException;

class VatWithReplacedDeleteException extends Exception implements VatException {

	/**
	 * @param string $message
	 * @param \Exception|null $previous
	 */
	public function __construct($message = '', Exception $previous = null) {
		parent::__construct($message, 0, $previous);
	}

}