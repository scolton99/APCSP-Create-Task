<?php
/**
 * Created by PhpStorm.
 * User: scolton
 * Date: 2017-01-13
 * Time: 22:12
 */

require_once("../../assets/php/var.php");

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
    <script
            src="https://code.jquery.com/jquery-3.1.1.min.js"
            integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
            crossorigin="anonymous"></script>

    <link rel="stylesheet" href="../../assets/css/fitness.css" />
    <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="../../assets/js/fitness.js"></script>
</head>
<body class="main">
<?php include ("../../assets/php/menu.php"); ?>
<h1 id="goals-header">Add A Goal</h1>
<div id="add-goal">
    <form id="add-goal-form" action="javascript:void(0);" onsubmit="addGoal()">
        <select class="form-control" id="goal-types">
            <?php
            $types = \tech\scolton\fitness\model\GoalType::getTypes();

            foreach ($types as $type) {
                assert ($type instanceof \tech\scolton\fitness\model\GoalType);
                $id = $type->getId();
                $units = $type->getUnits();
                $item = $type->getItem() == "" ? "" : "of ".$type->getItem();
                $comparator = $type->getComparator() == "LT" ? "less than" : "at least";
                $per = $type->getPer() == "WEEK" ? "per week" : "per day";
                $participle = $type->getParticiple();

                echo "<option value=$id>$participle $comparator X $units $item $per</option>";
            }
            ?>
        </select>
        <input type="number" id="amount" class="form-control">
        <input type="submit" value="Add Goal" class="form-control">
    </form>
</div>
</body>
</html>
