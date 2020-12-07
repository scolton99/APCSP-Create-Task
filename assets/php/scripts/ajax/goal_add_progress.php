<?php
/**
 * Created by PhpStorm.
 * User: scolton
 * Date: 2017-03-07
 * Time: 13:46
 */
if (!isset($_POST["goal"]) || !isset($_POST["amount"]))
    die(json_encode(array("result" => "failure", "message" => "Required parameter was not specified.")));

$amount = floatval($_POST["amount"]);
$goal = intval($_POST["goal"]);

require_once("../../var.php");

$g = \tech\scolton\fitness\model\Goal::get($goal);

$cur = $g->getProgress(new DateTime());

$db = getDB();

try {
   $g->setProgress($amount + $cur);
} catch (Exception $e) {
    die(json_encode(array("result" => "failure", "message" => $e->getMessage())));
}

exit (json_encode(array("result" => "success")));
