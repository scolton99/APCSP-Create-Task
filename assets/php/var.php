<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/10/17
 * Time: 10:56 AM
 */
define("TOPDIR", dirname(__FILE__, 3) . "/");

if (session_status() != PHP_SESSION_ACTIVE)
    session_start();

function shutdown() {
    if (error_get_last() != null)
        var_dump(error_get_last());
}

// register_shutdown_function("shutdown");

require_once("_spl_autoload.php");

function getDB(): \tech\scolton\fitness\database\DBProvider {
    $cfg = \tech\scolton\fitness\util\Config::getConfigSection("db");

    $provider = $cfg["provider"];

    $c = "\\tech\\scolton\\fitness\\database\\".$provider;
    $db = new $c();
    return $db;
}

function userLoggedIn(): bool {
    return isset($_SESSION["id"]) && $_SESSION["id"] >= 0;
}

function getHome(): string {
	$home = \tech\scolton\fitness\util\Config::getConfigSection("home");

	return $home;
}