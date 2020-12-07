<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/13/17
 * Time: 1:33 PM
 */

namespace tech\scolton\fitness\exception;


use Exception;

class UserLoginException extends \Exception
{
    /**
     * @var int
     */
    private $type;

    const USER_NOT_FOUND = -2;
    const INCORRECT_PASSWORD = -3;

    public function __construct($message = "", $code = 0, Exception $previous = null, int $type = -1)
    {
        $this->type = $type;
        parent::__construct($message, $code, $previous);
    }

    public function getType() {
        return $this->type;
    }
}