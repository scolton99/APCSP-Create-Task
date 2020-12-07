<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/11/17
 * Time: 2:23 PM
 */

namespace tech\scolton\fitness\notification;


use tech\scolton\fitness\model\User;

class ActionCongratulate extends ActionBase
{
    public function execute()
    {
        $id = intval($this->target);
        $user = User::get($id);

        $sender = User::get($this->source->getTarget())->getName();
        // TODO: Add goal phrase here once goals are in place
        $message = "$sender congratulated you on reaching your goal of [[GOAL]] this week!";

        $notification = new Notification(null, $message, $user, false, NotificationType::getTypeByName("Congratulations"));
        $notification->send();
    }
}