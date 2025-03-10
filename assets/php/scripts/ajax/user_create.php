<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/10/17
 * Time: 4:24 PM
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = urldecode($_POST["username"]);
$password = hash("sha256", urldecode($_POST["password"]));
$weight = $_POST["weight"];
$height = $_POST["height"];
$team = 0;
$units = $_POST["units"];
$name = urldecode($_POST["name"]);
$birthday = urldecode($_POST["birthday"]);

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

function fteam()
{
    $db = getDB();

    $t = $db->FindTeam();
    $u = \tech\scolton\fitness\model\User::get($_SESSION["id"]);

    if ($u->getTeamId() != 0)
        return;

    if ($t == 0) {
        $team = \tech\scolton\fitness\model\Team::g_new(array("name" => "Team"));
        $u->setTeam($team->getId());
    } else {
        $u->setTeam($t);
    }
}

try {
	$user = \tech\scolton\fitness\model\User::g_new($values);

	$_SESSION["id"] = $user->getId();

    fteam();

	exit(json_encode(array("result" => "success")));
} catch (Exception $e) {
	die(json_encode(array("result" => "failure", "message" => $e->getMessage())));
}