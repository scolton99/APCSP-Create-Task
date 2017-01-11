<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/10/17
 * Time: 9:08 PM
 */
namespace tech\scolton\fitness\tests;

use tech\scolton\fitness\util\Config;

require_once("../../../../../_config.php");

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testLoading() {
        $cfg = Config::getConfig();

        $this->assertNotNull($cfg);
    }

    public function testLoadingSections() {
        $cfg = Config::getConfigSection("DB");

        $this->assertNotNull($cfg);
    }
}
