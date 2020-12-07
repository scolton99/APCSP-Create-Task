<?php
/**
 * Created by PhpStorm.
 * User: scolton
 * Date: 2017-01-13
 * Time: 22:19
 */
require_once("../../var.php");

$type = $_POST["type"];
$user = \tech\scolton\fitness\model\User::get($_SESSION["id"]);
$amount = $_POST["amount"];

\tech\scolton\fitness\model\Goal::g_new($type, $user, $amount);

exit(json_encode(array("result" => "success")));