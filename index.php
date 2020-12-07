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

if (!$user->hasGoals())
    header("Location: ./goals/add");

$goals = [];

try {
	$goals = \tech\scolton\fitness\model\Goal::getAll($user);
} catch (\tech\scolton\fitness\exception\MySQLException $e) {
	die($e->getMessage());
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8"/>
		<title>Fitness Tracker</title>
		<script
				src="https://code.jquery.com/jquery-3.1.1.min.js"
				integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
				crossorigin="anonymous"></script>
		<link rel="stylesheet" href="assets/css/fitness.css" />
		<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<script src="assets/js/fitness.js"></script>
	</head>
	<body class="main">
		<?php include ("assets/php/menu.php"); ?>
		<section class="nav-top-section">
			<div id="goals-section-home">
				<h1 class="section-header">Your Goals</h1>
				<div class="row" id="goals">
					<div class="col-md-4 goal-progress-container">
						<?php if(array_key_exists(0, $goals)): echo $goals[0]->renderHTML(); else: ?>
							<a class="add-goals" href="goals">Add a goal...</a>
						<?php endif; ?>
					</div>
					<div class="col-md-4 goal-progress-container">
						<?php if(array_key_exists(1, $goals)): echo $goals[1]->renderHTML(); else: ?>
							<a class="add-goals" href="goals">Add a goal...</a>
						<?php endif; ?>
					</div>
					<div class="col-md-4 goal-progress-container">
						<?php if(array_key_exists(2, $goals)): echo $goals[2]->renderHTML(); else: ?>
							<a class="add-goals" href="goals">Add a goal...</a>
						<?php endif; ?>
					</div>
				</div>
				<br />
				<div class="row">
					<div class="col-md-6 goal-progress-container">
						<?php if(array_key_exists(3, $goals)): echo $goals[3]->renderHTML(); else: ?>
							<a class="add-goals" href="goals">Add a goal...</a>
						<?php endif; ?>
					</div>
					<div class="col-md-6 goal-progress-container">
						<?php if(array_key_exists(4, $goals)): echo $goals[4]->renderHTML(); else: ?>
							<a class="add-goals" href="goals">Add a goal...</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>
	</body>
</html>