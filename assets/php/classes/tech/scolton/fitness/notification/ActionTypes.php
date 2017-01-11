<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/11/17
 * Time: 2:22 PM
 */

namespace tech\scolton\fitness\notification;


abstract class ActionTypes
{
    const HYPERLINK = "HYPERLINK";
    const CONGRATULATE = "CONGRATULATE";

    const MAP = [
        "HYPERLINK" => null,
        "CONGRATULATE" => "CongratulateAction"
    ];
}