<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/10/17
 * Time: 1:19 PM
 */

namespace tech\scolton\fitness\util;

define("DIRNAME", dirname(__FILE__, 8) . "/");

class Config
{
    /**
     * @var array
     */
    private static $cfg;

    public static function getConfig() {
        return self::$cfg;
    }

    public static function getConfigSection($section) {
        return self::$cfg[$section];
    }

    public static function init(array $cfg) {
        self::$cfg = $cfg;
    }
}

$json = file_get_contents(DIRNAME . "assets/config/config.json");
$cfg = json_decode($json, true);
Config::init($cfg);

