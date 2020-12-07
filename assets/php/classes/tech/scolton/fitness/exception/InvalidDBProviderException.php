<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/10/17
 * Time: 1:50 PM
 */

namespace tech\scolton\fitness\exception;


use Exception;

class InvalidDBProviderException extends \Exception {
	public function __construct($message = "The DB provider specified in the configuration is invalid.", $code = 0, Exception $previous = null) {
		parent::__construct($message, $code, $previous);
	}
}