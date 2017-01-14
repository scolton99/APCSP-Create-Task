<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/10/17
 * Time: 2:41 PM
 */

namespace tech\scolton\fitness\exception;


use Exception;

class MySQLException extends \Exception {
	public function __construct($message = "MySQL encountered an error.", $code = 0, Exception $previous = null) {
		parent::__construct($message, $code, $previous);
	}
}