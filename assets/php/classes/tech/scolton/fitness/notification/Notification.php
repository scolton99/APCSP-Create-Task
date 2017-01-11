<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/11/17
 * Time: 2:08 PM
 */

namespace tech\scolton\fitness\notification;

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
interface Notification
{
    public function setup(int $id, string $content, User $target, bool $read);

    public function getContent(): string;
    public function send();
    public function getTarget(): User;
    public function getType(): string;
    public function getId(): int;
    public static function fromDatabase(int $id): Notification;
    public function isRead(): bool;
    public function update();
    public function isExecuted(): bool;
}