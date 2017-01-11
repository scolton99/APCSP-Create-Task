<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/11/17
 * Time: 8:18 AM
 */

namespace tech\scolton\fitness\views;


interface GoalView
{
    public function render(): string;
}