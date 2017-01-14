<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/10/17
 * Time: 12:39 PM
 */

namespace tech\scolton\fitness\exception;


use Exception;

class RequiredValueMissingException extends \Exception {
	public function __construct($message = "A required value for class instantiation was left blank.", $code = 0, Exception $previous = null) {
		parent::__construct($message, $code, $previous);
	}
}