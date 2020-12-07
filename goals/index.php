<?php
/**
 * Created by PhpStorm.
 * User: scolton
 * Date: 2017-01-13
 * Time: 22:12
 */

require_once("../assets/php/var.php");

if (!userLoggedIn())
	header("Location: ../register/");

$user = \tech\scolton\fitness\model\User::get($_SESSION["id"]);

$userGoals = $user->hasGoals() ? \tech\scolton\fitness\model\Goal::getAll($user) : [];

$goalTypes = \tech\scolton\fitness\model\GoalType::getTypes();
$goalSuperTypes = \tech\scolton\fitness\model\GoalSuperType::getSuperTypes();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Edit Goals - Fitness</title>
		<link rel="stylesheet" href="../assets/css/fitness.css" />
		<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<script src="../assets/js/fitness.js"></script>
    </head>
    <body class="main">
		<?php include ("../assets/php/menu.php"); ?>
		<h1 id="goals-header">Your Goals</h1>
		<table id="user-goals">
			<?php
			foreach ($userGoals as $goal) {
				assert($goal instanceof \tech\scolton\fitness\model\Goal);
				$str = $goal->renderSentence();
				$id = $goal->getId();

				// TODO: add icons and delete button to these goals.

				echo "<tr>";
					echo "<td>";
						echo $str;
					echo "</td>";
				/*	echo "<td>";
						echo "<button onclick=\"deleteGoal($id)\">Delete</button>";
					echo "</td>"; */
				echo "</tr>";
			}

			if (sizeof($userGoals) < 5) {
				echo "<tr><td colspan='2'><a class='goal-button' onclick='startGoalAdding()'>add a goal</a></td></tr>";
			}
			?>
		</table>
    </body>
</html>
