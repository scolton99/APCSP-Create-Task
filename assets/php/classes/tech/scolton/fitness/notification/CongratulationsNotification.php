<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/11/17
 * Time: 2:14 PM
 */

namespace tech\scolton\fitness\notification;


use tech\scolton\fitness\model\User;

class CongratulationsNotification implements Notification
{
    public function getContent(): string
    {
        return null;
        // TODO: Implement getContent() method.
    }

    public function send()
    {
        // TODO: Implement send() method.
    }

    public function setup(int $id, string $content, User $target, bool $read)
    {
        // TODO: Implement setup() method.
    }

    public function getTarget(): User
    {
        return null;
        // TODO: Implement getTarget() method.
    }

    public function getType(): string
    {
        return null;
        // TODO: Implement getType() method.
    }

    public function getId(): int
    {
        return null;
        // TODO: Implement getId() method.
    }

    public static function fromDatabase(int $id): Notification
    {
        return null;
        // TODO: Implement fromDatabase() method.
    }

    public function isRead(): bool
    {
        return null;
        // TODO: Implement isRead() method.
    }

    public function update()
    {
        // TODO: Implement update() method.
    }

    public function isExecuted(): bool
    {
        return null;
        // TODO: Implement isExecuted() method.
    }
}