<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/11/17
 * Time: 2:13 PM
 */

namespace tech\scolton\fitness\notification;


interface ActionableNotification
{
    public function setupAction(Action $action, string $actionType, bool $actionExecuted);

    public function getAction(): Action;
    public function isExecuted(): bool;
    public function getRunType(): string;
}