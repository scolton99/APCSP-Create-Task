<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/10/17
 * Time: 4:24 PM
 */
$username = $_POST["username"];
$password = hash("sha256", $_POST["password"]);
$weight = $_POST["weight"];
$height = $_POST["height"];
$team = 0;
$units = $_POST["units"];
$name = $_POST["name"];
$birthday = $_POST["birthday"];

require_once("../../var.php");

$values = array(
    "username" => $username,
    "password" => $password,
    "weight" => $weight,
    "name" => $name,
    "height" => $height,
    "team" => $team,
    "units" => $units,
    "birthday" => $birthday
);

$user = \tech\scolton\fitness\model\User::g_new($values);

// TODO: replace this with a try-catch block when the MySQL function is updated to use a throws statement
if ($user->getId() == -1) {
    die(json_encode(array("result" => "failure", "message" => "Couldn't create user.")));
}

$_SESSION["id"] = $user->getId();

exit(json_encode(array("result" => "success")));