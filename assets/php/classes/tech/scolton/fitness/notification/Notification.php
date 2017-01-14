<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/11/17
 * Time: 2:08 PM
 */

namespace tech\scolton\fitness\notification;

define("TOP", dirname(__FILE__, 6) . "/");

require_once(TOP. "var.php");

use tech\scolton\fitness\model\User;

/**
 * Interface Notification
 *
 * Though there are setter functions for all local variables, these are solely for use by
 * instantiation from a database.  The ONLY properties whose changes will be reflected
 * on the database when the notification is updated are "read" and "action_executed".
 *
 * @package tech\scolton\fitness\notification
 */
class Notification
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var int
     */
    private $id;

    /**
     * @var User
     */
    private $target;

    /**
     * @var bool
     */
    private $read;

    /**
     * @var NotificationType
     */
    private $type;

    public function __construct(int $id, string $content, User $target, bool $read, NotificationType $type)
    {
        $this->id = $id;
        $this->content = $content;
        $this->target = $target;
        $this->read = $read;
        $this->type = $type;
    }

    public function _update()
    {
        $db = getDB();
        $db->UpdateNotification($this);
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getTarget(): User {
        return $this->target;
    }

    public function isRead(): bool {
        return $this->read;
    }

    public function setRead(boolean $read) {
        $this->read = $read;
        $this->_update();
    }

    public  function getType(): NotificationType {
        return $this->type;
    }

    public function send() {
        $db = getDB();

        $this->id = $db->SendNotification($this);
    }

    public static function fromDatabase(int $id): Notification {
        return getDB()->GetNotification($id);
    }
}