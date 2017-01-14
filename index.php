<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/10/17
 * Time: 10:47 AM
 */
require_once("assets/php/var.php");

if (!userLoggedIn())
	header("Location: ./register/");

$user = \tech\scolton\fitness\model\User::get($_SESSION["id"]);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8"/>
		<title>Fitness Tracker</title>
		<link rel="stylesheet" href="assets/css/fitness.css"/>
		<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
		<script src="assets/js/fitness.js"></script>
	</head>
	<body class="main">
		<a class="metro-button" id="logout-button" href="javascript:void(0);">Log out</a>
	</body>
</html>