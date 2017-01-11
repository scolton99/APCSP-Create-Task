<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/11/17
 * Time: 2:19 PM
 */

namespace tech\scolton\fitness\notification;


abstract class NotificationTypes
{
    const CONGRATULATE = "CONGRATULATE";
    const CONGRATS = "CONGRATS";

    const MAP = [
        "CONGRATULATE" => "CongratulateNotification",
        "CONGRATS" => "CongratulationsNotification"
    ];
}