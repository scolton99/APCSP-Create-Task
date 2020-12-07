<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/11/17
 * Time: 2:13 PM
 */

namespace tech\scolton\fitness\notification;


class NotificationActionable extends Notification
{
    /**
     * @var Action
     */
    private $action;

    /**
     * @var string
     */
    private $runType;

    /**
     * @var bool
     */
    private $executed;


    public function setupAction(Action $action, string $actionRunType, bool $actionExecuted)
    {
        $this->action = $action;
        $this->runType = $actionRunType;
        $this->executed = $actionExecuted;
    }

    public function getAction(): Action
    {
        return $this->action;
    }

    public function isExecuted(): bool
    {
        return $this->executed;
    }

    public function getRunType(): string
    {
        return $this->runType;
    }

    public function setActionExecuted(boolean $executed) {
        $this->executed = $executed;
        $this->_update();
    }
}