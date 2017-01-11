<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/11/17
 * Time: 2:14 PM
 */

namespace tech\scolton\fitness\notification;

use tech\scolton\fitness\model\User;

require_once("../../../../../var.php");

class CongratulateNotification implements Notification,ActionableNotification
{
    /**
     * @var Action
     */
    private $action;

    /**
     * @var string
     */
    private $content;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $runType;

    /**
     * @var User
     */
    private $target;

    /**
     * @var string
     */
    private $type = NotificationTypes::CONGRATULATE;

    /**
     * @var bool
     */
    private $executed;

    /**
     * @var bool
     */
    private $read;

    public function __construct(string $content, Action $action, User $target) {
        $this->action = $action;
        $this->content = $content;
        $this->target = $target;
    }

    public function send()
    {
        $db = getDB();
        $db->SendNotification($this);
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getAction(): Action
    {
        return $this->action;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRunType(): string
    {
        return $this->runType;
    }

    public function getTarget(): User
    {
        return $this->target;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isExecuted(): bool
    {
        return $this->executed;
    }

    public function isRead(): bool
    {
        return $this->read;
    }

    public function update()
    {
        $db = getDB();
        $db->UpdateNotification($this);
    }

    public static function fromDatabase(int $id): Notification
    {
        return getDB()->GetNotification($id);
    }

    public function setup(int $id, string $content, User $target, bool $read)
    {
        $this->id = $id;
        $this->content = $content;
        $this->target = $target;
        $this->read = $read;
    }

    public function setupAction(Action $action, string $actionType, bool $actionExecuted)
    {
        $this->action = $action;
        $this->runType = $actionType;
        $this->executed = $actionExecuted;
    }
}