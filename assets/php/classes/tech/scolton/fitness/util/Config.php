<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/10/17
 * Time: 1:19 PM
 */

namespace tech\scolton\fitness\util;

require_once("../../../../../_config.php");

class Config
{
    public static function getConfig() {
        global $cfg;

        return $cfg;
    }

    public static function getConfigSection($section) {
        global $cfg;

        return $cfg[$section];
    }
}