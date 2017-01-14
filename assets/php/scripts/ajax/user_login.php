<?php
/**
 * Created by PhpStorm.
 * User: scolton
 * Date: 2017-01-11
 * Time: 17:49
 */
require_once("../../var.php");

$db = getDB();
try {
    $user = $db->Login($_POST["username"], $_POST["password"]);

    $_SESSION["id"] = $user;

    exit(json_encode(array("result" => "success")));
} catch (Exception $e) {
    if ($e instanceof \tech\scolton\fitness\exception\UserLoginException) {
        if ($e->getType() == \tech\scolton\fitness\exception\UserLoginException::INCORRECT_PASSWORD) {
            die(json_encode(array("result" => "failure", "message" => "Your password was incorrect.  Please try again.")));
        } else if ($e->getType() == \tech\scolton\fitness\exception\UserLoginException::USER_NOT_FOUND ){
            die(json_encode(array("result" => "failure", "message" => "Couldn't find a user with that username.  Try a different username or sign up.")));
        } else {
            die(json_encode(array("result" => "failure", "message" => "An unknown error occurred while logging you in.")));
        }
    } else if ($e instanceof \tech\scolton\fitness\exception\MySQLException) {
        die(json_encode(array("result" => "failure", "message" => "There was an error connecting to the database.<br />".$e->getMessage())));
    } else {
        die(json_encode(array("result" => "failure", "message" => $e->getMessage())));
    }
}