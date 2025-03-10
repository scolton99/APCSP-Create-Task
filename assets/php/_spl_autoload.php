<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/10/17
 * Time: 10:53 AM
 */

/**
 * @param $class
 */
function __fitness_spl_autoload($class) {
    $cName = str_replace("\\", DIRECTORY_SEPARATOR, $class);

    if (@require_once(TOPDIR . "assets/php/classes/" . $cName . ".php"))
        return;

    $i = new RecursiveDirectoryIterator(TOPDIR . "assets/php/classes/", RecursiveDirectoryIterator::SKIP_DOTS);
    $j = new RecursiveIteratorIterator($i, RecursiveIteratorIterator::SELF_FIRST);

    foreach ($j as $item) {
        if (strtolower($item->getExtension()) != "php")
            continue;

        if (strtolower($item->getBasename(".php")) != $class && strtolower($item->getBasename(".class.php")) != $class)
            continue;

        require_once($item->getPath());
    }
}

spl_autoload_register("__fitness_spl_autoload");