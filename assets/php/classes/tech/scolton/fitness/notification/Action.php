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
    public function __construct(string $target, ActionType $type, Notification $source, array $actionMeta);
    public function execute();
    public function getTarget(): string;
    public function getType(): ActionType;
    public function getSource(): Notification;
    public function getActionMeta(): array;
}