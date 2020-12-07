<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 2/15/17
 * Time: 1:12 PM
 */

include("../../var.php");

session_destroy();

exit(json_encode(["result" => "success"]));