<?php
/**
 * Created by PhpStorm.
 * User: scolton
 * Date: 2017-01-11
 * Time: 17:49
 */
require_once("../../var.php");

$db = getDB();
$id = $db->Login($_POST["username"], $_POST["password"]);

if ($id <= 0) {
    echo json_encode(array("result" => "failure", "message" => "Invalid username or password."));
} else {
    echo json_encode(array("result" => "success"));
}