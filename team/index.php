<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/10/17
 * Time: 10:47 AM
 */
require_once("../assets/php/var.php");

if (!userLoggedIn())
	header("Location: ./register/");

$user = \tech\scolton\fitness\model\User::get($_SESSION["id"]);

$team = $user->getTeam();

$members = $team->getUsers();

// if (!$user->hasGoals())
//	header("Location: ./goals");

$goals = [];

foreach ($members as $member) {
	assert($member instanceof \tech\scolton\fitness\model\User);
	$goals[$member->getId()] = [];
	$m_goals = null;
	try {
		$m_goals = \tech\scolton\fitness\model\Goal::getAll($member);
	} catch (\tech\scolton\fitness\exception\MySQLException $e) {
		$m_goals = [];
	}
	foreach ($m_goals as $goal) {
		$goals[$member->getId()][] = $goal;
	}
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
		<link rel="stylesheet" href="../assets/css/fitness.css" />
		<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<script src="../assets/js/fitness.js"></script>
	</head>
	<body class="main">
		<?php include("../assets/php/menu.php"); ?>
        <div class="container-fluid">
            <section class="nav-top-section">
                <div id="goals-section">
                    <h1 id="your-team" class="section-header">Your Team</h1>
                    <div class="row" id="goals">
                        <div class="col-md-2">

                        </div>
                        <?php foreach($goals as $m => $g): ?>
                            <div class="col-md-2">
                                <h2><?php echo \tech\scolton\fitness\model\User::get($m)->getName(); ?></h2>
                                <?php if(array_key_exists(0, $g)) echo $g[0]->renderHTML(); ?>
                                <br />
                                <?php if(array_key_exists(1, $g)) echo $g[1]->renderHTML(); ?>
                                <br />
                                <?php if(array_key_exists(2, $g)) echo $g[2]->renderHTML(); ?>
                                <br />
                                <?php if(array_key_exists(3, $g)) echo $g[3]->renderHTML(); ?>
                                <br />
                                <?php if(array_key_exists(4, $g)) echo $g[4]->renderHTML(); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        </div>
	</body>
</html>