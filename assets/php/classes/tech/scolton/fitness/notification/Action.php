<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/11/17
 * Time: 2:22 PM
 */

namespace tech\scolton\fitness\notification;


interface Action
{
    public function execute();
    public function getType(): string;
    public function getTarget(): string;
    public function setup(string $target, string $type);
}