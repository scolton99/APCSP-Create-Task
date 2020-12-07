<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/11/17
 * Time: 8:09 AM
 */
require_once("../assets/php/var.php");

if (userLoggedIn())
    header("Location: ../");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Fitness - Register</title>
        <script
                src="https://code.jquery.com/jquery-3.1.1.min.js"
                integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
                crossorigin="anonymous"></script>
        <link rel="stylesheet" href="../assets/css/fitness.css" />
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
		<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<script src="../assets/js/fitness.js"></script>
    </head>
    <body class="main">
		<div class="modal fade" id="error-modal">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title">Creating your account...</h4>
					</div>
					<div class="modal-body">
						<span class="error" id="action_error"></span>
						<button type="button" class="btn btn-default" data-dismiss="modal">
							Close
						</button>
					</div>
				</div>
			</div>
		</div>
		<?php include("../assets/php/menu.php"); ?>
        <div id="login-form">

        </div>
        <section id="form" class="main">
            <!-- <div id="logo-container">
                <img src="../assets/img/main_logo.svg" alt="Fitness Logo" class="logo" id="register-logo" />
            </div> -->
			<div id="registration-form">
                <h1 id="registration-header">Registration</h1>
				<form onsubmit="register();">
					<h1>Hello</h1>
					<input class="form-control" placeholder="your name" id="name" type="text" autocomplete="off" autofocus>
					<hr />
					<h1>Height and Weight</h1>
					<div class="radio">
						<label>
							<input type="radio" name="units" value="2" id="customary" onchange="switchCustomary();" checked>
							customary
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="units" value="1" id="metric" onchange="switchMetric();">
							metric
						</label>
					</div>
					<div id="customarybox">
						<div class="row">
							<div class="col-md-3">
								<div class="input-group" id="feetcont">
									<input class="form-control" title="Height (feet)" type="text" autocomplete="off" id="ft">
									<span class="input-group-addon">ft.</span>
								</div>
							</div>
							<div class="col-md-3">
								<div class="input-group" id="inchescont">
									<input class="form-control" title="Height (inches)" type="text" autocomplete="off" id="in">
									<span class="input-group-addon">in.</span>
								</div>
							</div>
							<div class="col-md-2">

							</div>
							<div class="col-md-4">
								<div class="input-group">
									<input class="form-control" title="Weight (pounds)" type="text" autocomplete="off"  id="lbs">
									<span class="input-group-addon">lbs.</span>
								</div>
							</div>
						</div>
					</div>
					<div id="metricbox">
						<div class="row">
							<div class="col-md-6">
								<div class="input-group" id="cmcont">
									<input class="form-control" title="Height (centimeters)" type="text" autocomplete="off" id="cm" onkeyup="if(event.keyCode == 13) next();">
									<span class="input-group-addon">cm.</span>
								</div>
							</div>
							<div class="col-md-2">

							</div>
							<div class="col-md-4">
								<div class="input-group" id="kgcont">
									<input class="form-control" title="Weight (kilograms)" type="text" autocomplete="off" id="kg" onkeyup="if(event.keyCode == 13) next();">
									<span class="input-group-addon">kg.</span>
								</div>
							</div>
						</div>
					</div>
					<hr />
					<h1>Date of Birth</h1>
					<div class="row">
						<div class="col-md-2">
							<select class="form-control" title="Date of Birth (Day)" id="d">
								<?php
								for ($i = 1; $i <= 31; $i++) {
									echo "<option value='$i'>$i</option>";
								}
								?>
							</select>
						</div>
						<div class="col-md-7">
							<select class="form-control" title="Date of Birth (Month)" id="m">
								<option value="1">January</option>
								<option value="2">February</option>
								<option value="3">March</option>
								<option value="4">April</option>
								<option value="5">May</option>
								<option value="6">June</option>
								<option value="7">July</option>
								<option value="8">August</option>
								<option value="9">September</option>
								<option value="10">October</option>
								<option value="11">November</option>
								<option value="12">December</option>
							</select>
						</div>
						<div class="col-md-3">
							<select class="form-control" title="Date of Birth (Year)" id="y">
								<?php
								for ($i = 1900; $i <= date("Y"); $i++) {
									echo "<option value='$i'>$i</option>";
								}
								?>
							</select>
						</div>
					</div>
					<hr />
					<h1>Login info</h1>
					<input class="form-control" type="text" id="username" placeholder="username" autocomplete="off">
					<br />
					<input class="form-control" type="password" id="password" placeholder="password" autocomplete="off">
					<hr>
					<input class="form-control" type="submit" value="submit">
				</form>
			</div>
        </section>
    </body>
</html>
