<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/11/17
 * Time: 8:19 AM
 */

namespace tech\scolton\fitness\views;


use tech\scolton\fitness\model\User;

class UserView
{
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function render(): string {
        $d = <<< EOT

EOT;
        return $d;
    }
}