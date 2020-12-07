<?php
/**
 * Created by PhpStorm.
 * User: scolton
 * Date: 2017-01-14
 * Time: 00:33
 */

namespace tech\scolton\fitness\notification;

define("TOP", dirname(__FILE__, 6) . "/");

require_once(TOP. "var.php");

class ActionHyperlink extends ActionBase {
    public function execute() {
        header("Location: $this->target");
    }
}