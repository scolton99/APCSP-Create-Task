<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Fitness - Login</title>
		<link rel="stylesheet" href="../assets/css/fitness.css" />
		<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<script src="../assets/js/fitness.js"></script>
    </head>
    <body class="main">
    	<?php include("../assets/php/menu.php"); ?>
    	<section id="form" class="main">
			<form id="login-form" onsubmit="login();">
				<h1>Login</h1>
				<input class="form-control" placeholder="username" id="username" type="text" autofocus />
				<input class="form-control" placeholder="password" id="password" type="password" />
				<input class="form-control" type="submit" value="Log In" />
			</form>
        </section>
    </body>
</html>