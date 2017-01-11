<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/10/17
 * Time: 10:56 AM
 */

define("DIR", "/");
define("ROOT", $_SERVER["DOCUMENT_ROOT"]);

require_once("_spl_autoload.php");

function getDB(): \tech\scolton\fitness\database\DBProvider {
    $cfg = \tech\scolton\fitness\util\Config::getConfig();

    $provider = $cfg["DBProvider"];


    $c = "\\tech\\scolton\\fitness\\database\\".$provider;
    $db = new $c;
    return $db;

}